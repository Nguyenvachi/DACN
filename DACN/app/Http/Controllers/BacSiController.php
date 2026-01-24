<?php

namespace App\Http\Controllers;

use App\Models\BacSi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BacSiController extends Controller
{
    public function publicIndex(Request $request)
    {
        // Kiểm tra xem bảng có cột trang_thai không
        $query = BacSi::query();

        if (Schema::hasColumn('bac_sis', 'trang_thai')) {
            $query->where('trang_thai', 'Đang hoạt động');
        }

        // Keyword search (tên bác sĩ hoặc tên user liên quan)
        if ($keyword = $request->query('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($uq) use ($keyword) {
                        $uq->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        // Filter by chuyen_khoa (can be slug or id or raw string stored in bac_sis.chuyen_khoa)
        if ($ck = $request->query('chuyen_khoa')) {
            // If numeric, assume id
            if (is_numeric($ck)) {
                $query->whereHas('chuyenKhoas', function ($q) use ($ck) {
                    $q->where('id', $ck);
                });
            } else {
                // try slug match on ChuyenKhoa
                $query->where(function ($q) use ($ck) {
                    $q->whereHas('chuyenKhoas', function ($hk) use ($ck) {
                        $hk->where('slug', $ck);
                    })->orWhere('chuyen_khoa', $ck);
                });
            }
        }

        // Eager load chuyenKhoas for view badge
        $bacSis = $query->with('chuyenKhoas')->orderBy('ho_ten')->get();

        // Provide chuyen khoa list for the dropdown
        $chuyenKhoas = \App\Models\ChuyenKhoa::orderBy('ten')->get();

        return view('public.bacsi.index', compact('bacSis', 'chuyenKhoas'));
    }
}
