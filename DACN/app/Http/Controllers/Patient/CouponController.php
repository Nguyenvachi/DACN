<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()
            ->where('kich_hoat', true)
            ->where(function($q) {
                $q->whereNull('ngay_bat_dau')
                  ->orWhere('ngay_bat_dau', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('ngay_ket_thuc')
                  ->orWhere('ngay_ket_thuc', '>=', now());
            })
            ->where(function($q) {
                $q->whereNull('so_lan_su_dung_toi_da')
                  ->orWhereRaw('so_lan_da_su_dung < so_lan_su_dung_toi_da');
            })
            ->latest()
            ->get();

        return view('patient.coupons.index', compact('coupons'));
    }

    public function show(Coupon $coupon)
    {
        // Kiểm tra mã có hợp lệ không
        if (!$coupon->kich_hoat) {
            abort(404, 'Mã giảm giá không tồn tại hoặc đã hết hạn');
        }

        return view('patient.coupons.show', compact('coupon'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'ma' => 'required|string',
            'tong_tien' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('ma_giam_gia', $request->ma)
            ->where('kich_hoat', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn'
            ], 404);
        }

        if (!$coupon->kiemTraHopLe($request->tong_tien)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã không hợp lệ. ' . ($coupon->don_toi_thieu ? 'Đơn hàng tối thiểu ' . number_format($coupon->don_toi_thieu, 0, ',', '.') . 'đ' : '')
            ], 400);
        }

        $giam_gia = $coupon->tinhGiamGia($request->tong_tien);

        return response()->json([
            'success' => true,
            'coupon' => [
                'id' => $coupon->id,
                'ma' => $coupon->ma_giam_gia,
                'ten' => $coupon->ten,
                'loai' => $coupon->loai,
                'gia_tri' => $coupon->gia_tri,
                'giam_gia' => $giam_gia,
                'giam_gia_formatted' => number_format($giam_gia, 0, ',', '.') . 'đ',
            ],
            'message' => 'Áp dụng mã thành công'
        ]);
    }
}
