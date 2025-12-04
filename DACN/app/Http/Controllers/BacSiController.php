<?php

namespace App\Http\Controllers;

use App\Models\BacSi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BacSiController extends Controller
{
    public function publicIndex()
    {
        // Kiểm tra xem bảng có cột trang_thai không
        $query = BacSi::query();

        if (Schema::hasColumn('bac_sis', 'trang_thai')) {
            $query->where('trang_thai', 'Đang hoạt động');
        }

        $bacSis = $query->orderBy('ho_ten')->get();

        return view('public.bacsi.index', compact('bacSis'));
    }
}
