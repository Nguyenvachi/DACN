<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Thuoc;
use App\Models\ThuocKho;
use App\Models\NhaCungCap;
use App\Models\PhieuNhap;
use App\Models\PhieuNhapItem;
use App\Models\PhieuXuat;
use App\Models\PhieuXuatItem;
use Carbon\Carbon;

class KhoThuocController extends Controller
{
    // Trang tổng quan kho: tồn theo thuốc
    public function index()
    {
        $thuocs = Thuoc::withSum('kho as ton_kho', 'so_luong')->orderBy('ten')->get();
        return view('admin.kho.index', compact('thuocs'));
    }

    // Form nhập kho
    public function nhapForm()
    {
        $thuocs = Thuoc::orderBy('ten')->get();
        $nccs = NhaCungCap::orderBy('ten')->get();
        return view('admin.kho.nhap', compact('thuocs','nccs'));
    }

    // Lưu phiếu nhập
    public function nhapStore(Request $request)
    {
        $data = $request->validate([
            'nha_cung_cap_id' => 'nullable|integer|exists:nha_cung_caps,id',
            'ngay_nhap' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.thuoc_id' => 'required|integer|exists:thuocs,id',
            'items.*.so_luong' => 'required|integer|min:1',
            'items.*.don_gia' => 'required|numeric|min:0',
            'items.*.ma_lo' => 'nullable|string',
            'items.*.han_su_dung' => 'nullable|date',
        ]);

        return DB::transaction(function () use ($data, $request) {
            $pn = PhieuNhap::create([
                'ma_phieu' => 'PN'.now()->format('YmdHis'),
                'ngay_nhap' => $data['ngay_nhap'],
                'nha_cung_cap_id' => $data['nha_cung_cap_id'] ?? null,
                'user_id' => $request->user()->id ?? null,
                'tong_tien' => 0,
            ]);

            $tong = 0;
            foreach ($data['items'] as $it) {
                $tt = $it['so_luong'] * $it['don_gia'];
                PhieuNhapItem::create([
                    'phieu_nhap_id' => $pn->id,
                    'thuoc_id' => $it['thuoc_id'],
                    'ma_lo' => $it['ma_lo'] ?? null,
                    'han_su_dung' => $it['han_su_dung'] ?? null,
                    'so_luong' => $it['so_luong'],
                    'don_gia' => $it['don_gia'],
                    'thanh_tien' => $tt,
                ]);

                ThuocKho::create([
                    'thuoc_id' => $it['thuoc_id'],
                    'ma_lo' => $it['ma_lo'] ?? null,
                    'han_su_dung' => $it['han_su_dung'] ?? null,
                    'so_luong' => $it['so_luong'],
                    'gia_nhap' => $it['don_gia'],
                    'gia_xuat' => $it['gia_xuat'] ?? $it['don_gia'], // Cho phép nhập giá xuất riêng
                    'nha_cung_cap_id' => $data['nha_cung_cap_id'] ?? null,
                ]);
                $tong += $tt;
            }

            $pn->update(['tong_tien' => $tong]);
            return redirect()->route('admin.kho.index')->with('success','Đã nhập kho thành công');
        });
    }

    // Form xuất kho
    public function xuatForm()
    {
        $thuocs = Thuoc::orderBy('ten')->get();
        return view('admin.kho.xuat', compact('thuocs'));
    }

    // Lưu phiếu xuất
    public function xuatStore(Request $request)
    {
        $data = $request->validate([
            'ngay_xuat' => 'required|date',
            'doi_tuong' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.thuoc_id' => 'required|integer|exists:thuocs,id',
            'items.*.so_luong' => 'required|integer|min:1',
            'items.*.don_gia' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($data, $request) {
            $px = PhieuXuat::create([
                'ma_phieu' => 'PX'.now()->format('YmdHis'),
                'ngay_xuat' => $data['ngay_xuat'],
                'doi_tuong' => $data['doi_tuong'] ?? null,
                'user_id' => $request->user()->id ?? null,
                'tong_tien' => 0,
            ]);

            $tong = 0;
            foreach ($data['items'] as $it) {
                $tt = $it['so_luong'] * $it['don_gia'];
                PhieuXuatItem::create([
                    'phieu_xuat_id' => $px->id,
                    'thuoc_id' => $it['thuoc_id'],
                    'so_luong' => $it['so_luong'],
                    'don_gia' => $it['don_gia'],
                    'thanh_tien' => $tt,
                ]);

                // Xuất theo FIFO đơn giản: trừ dần từ các lô còn số lượng
                $remain = $it['so_luong'];
                $lots = ThuocKho::where('thuoc_id', $it['thuoc_id'])
                    ->orderBy('han_su_dung')
                    ->orderBy('id')
                    ->get();
                foreach ($lots as $lot) {
                    if ($remain <= 0) break;
                    $take = min($remain, $lot->so_luong);
                    $lot->so_luong -= $take;
                    $lot->save();
                    $remain -= $take;
                }

                if ($remain > 0) {
                    abort(422, 'Tồn kho không đủ để xuất');
                }

                $tong += $tt;
            }

            $px->update(['tong_tien' => $tong]);
            return redirect()->route('admin.kho.index')->with('success','Đã xuất kho thành công');
        });
    }

    // Báo cáo tổng quan kho
    public function baoCao()
    {
        $val = ThuocKho::selectRaw('COALESCE(SUM(so_luong),0) as tong_sl, COALESCE(SUM(so_luong * gia_nhap),0) as gia_tri_nhap, COALESCE(SUM(so_luong * gia_xuat),0) as gia_tri_xuat')->first();
        $recentCogs = PhieuXuatItem::join('phieu_xuats','phieu_xuat_items.phieu_xuat_id','=','phieu_xuats.id')
            ->where('phieu_xuats.ngay_xuat','>=', now()->subDays(30)->toDateString())
            ->sum('phieu_xuat_items.thanh_tien');
        $topThuocs = ThuocKho::select('thuoc_id')
            ->selectRaw('SUM(so_luong) as ton')
            ->groupBy('thuoc_id')
            ->orderByDesc('ton')
            ->with('thuoc')
            ->limit(10)
            ->get();
        return view('admin.kho.bao_cao', [
            'val' => $val,
            'recentCogs' => $recentCogs,
            'topThuocs' => $topThuocs,
        ]);
    }

    // Chi tiết các lô theo thuốc (sẽ dùng cho view lots sau)
    public function lots(Thuoc $thuoc)
    {
        $lots = ThuocKho::where('thuoc_id',$thuoc->id)
            ->orderByRaw('CASE WHEN han_su_dung IS NULL THEN 1 ELSE 0 END, han_su_dung')
            ->orderBy('id')
            ->get();
        $tong_sl = $lots->sum('so_luong');
        $tong_val = $lots->sum(function($l){ return $l->so_luong * $l->gia_nhap; });
        return view('admin.kho.lots', compact('thuoc','lots','tong_sl','tong_val')); // view sẽ tạo ở bước sau
    }

    /**
     * API: Lấy danh sách thuốc theo nhà cung cấp
     */
    public function getThuocsByNCC(Request $request)
    {
        $nccId = $request->input('ncc_id');

        if (!$nccId) {
            return response()->json([
                'success' => true,
                'data' => Thuoc::orderBy('ten')->get(['id', 'ten', 'ham_luong', 'don_vi'])
            ]);
        }

        $ncc = NhaCungCap::find($nccId);
        if (!$ncc) {
            return response()->json(['success' => false, 'message' => 'NCC không tồn tại'], 404);
        }

        $thuocs = $ncc->thuocs()
            ->orderBy('ten')
            ->get(['thuocs.id', 'ten', 'ham_luong', 'don_vi', 'gia_nhap_mac_dinh']);

        return response()->json([
            'success' => true,
            'data' => $thuocs->map(function($t) {
                return [
                    'id' => $t->id,
                    'ten' => $t->ten,
                    'ham_luong' => $t->ham_luong,
                    'don_vi' => $t->don_vi,
                    'gia_nhap_mac_dinh' => $t->pivot->gia_nhap_mac_dinh,
                ];
            })
        ]);
    }
}
