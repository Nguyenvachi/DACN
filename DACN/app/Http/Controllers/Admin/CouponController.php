<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()->latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ma_giam_gia' => 'required|string|max:50|unique:coupons,ma_giam_gia',
            'ten' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai' => 'required|string|in:phan_tram,tien_mat',
            'gia_tri' => 'required|numeric|min:0',
            'giam_toi_da' => 'nullable|numeric|min:0',
            'don_toi_thieu' => 'nullable|numeric|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'so_lan_su_dung_toi_da' => 'nullable|integer|min:0',
            'kich_hoat' => 'boolean',
        ]);

        $data['kich_hoat'] = $request->boolean('kich_hoat');
        $coupon = Coupon::create($data);

        return redirect()->route('admin.coupons.show', $coupon)->with('status', 'Tạo mã giảm giá thành công');
    }

    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'ma_giam_gia' => 'required|string|max:50|unique:coupons,ma_giam_gia,' . $coupon->id,
            'ten' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai' => 'required|string|in:phan_tram,tien_mat',
            'gia_tri' => 'required|numeric|min:0',
            'giam_toi_da' => 'nullable|numeric|min:0',
            'don_toi_thieu' => 'nullable|numeric|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'so_lan_su_dung_toi_da' => 'nullable|integer|min:0',
            'kich_hoat' => 'boolean',
        ]);

        $data['kich_hoat'] = $request->boolean('kich_hoat');
        $coupon->update($data);

        return redirect()->route('admin.coupons.show', $coupon)->with('status', 'Cập nhật mã giảm giá thành công');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('status', 'Xóa mã giảm giá thành công');
    }
}
