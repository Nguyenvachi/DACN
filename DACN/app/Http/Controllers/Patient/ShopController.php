<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\Thuoc;
use App\Models\Coupon;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $thuocs = Thuoc::orderBy('ten')
            ->paginate(12);

        return view('patient.shop.index', compact('thuocs'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['gia'] * $item['so_luong'];
        }

        return view('patient.shop.cart', compact('cart', 'total'));
    }

    public function addToCart(Request $request, $id)
    {
        $thuoc = Thuoc::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['so_luong']++;
        } else {
            $cart[$id] = [
                'ten_thuoc' => $thuoc->ten,
                // Dùng accessor `gia_ban` (đã thêm vào model Thuoc) để đồng bộ giá hiển thị
                'gia' => $thuoc->gia_ban ?? ($thuoc->gia_tham_khao ?? 0),
                'so_luong' => 1,
                'hinh_anh' => $thuoc->hinh_anh ?? null,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng',
            'cart_count' => count($cart)
        ]);
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;
        $quantity = max(1, (int)$request->quantity);

        if (isset($cart[$id])) {
            $cart[$id]['so_luong'] = $quantity;
            session()->put('cart', $cart);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['gia'] * $item['so_luong'];
        }

        return response()->json([
            'success' => true,
            'total' => number_format($total, 0, ',', '.')
        ]);
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => count($cart)
        ]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('patient.shop.index')->with('error', 'Giỏ hàng trống');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['gia'] * $item['so_luong'];
        }

        return view('patient.shop.checkout', compact('cart', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'dia_chi_giao' => 'required|string|max:255',
            'sdt_nguoi_nhan' => 'required|string|max:15',
            'ghi_chu' => 'nullable|string',
            // Bảng coupons dùng cột `ma_giam_gia` theo schema
            'coupon_code' => 'nullable|string|exists:coupons,ma_giam_gia',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('patient.shop.index')->with('error', 'Giỏ hàng trống');
        }

        $tongTien = 0;
        foreach ($cart as $item) {
            $tongTien += $item['gia'] * $item['so_luong'];
        }

        $couponId = null;
        if ($request->coupon_code) {
            // Sử dụng cột `ma_giam_gia` theo cấu trúc DB
            $coupon = Coupon::where('ma_giam_gia', $request->coupon_code)
                ->where('kich_hoat', true)
                ->first();

            if ($coupon && $coupon->kiemTraHopLe($tongTien)) {
                $couponId = $coupon->id;
            }
        }

        $donHang = DonHang::create([
            'user_id' => auth()->id(),
            'coupon_id' => $couponId,
            'tong_tien' => $tongTien,
            'giam_gia' => 0, // Sẽ tự động tính trong saving event
            'thanh_toan' => $tongTien, // Sẽ tự động tính trong saving event
            'dia_chi_giao' => $request->dia_chi_giao,
            'sdt_nguoi_nhan' => $request->sdt_nguoi_nhan,
            'ghi_chu' => $request->ghi_chu,
            // Lưu theo enum định nghĩa trong migration (chuỗi tiếng Việt)
            'trang_thai' => 'Chờ xử lý',
            'trang_thai_thanh_toan' => 'Chưa thanh toán',
            'ngay_dat' => now(),
        ]);

        // Lưu items (sử dụng cột `don_gia` tương ứng migration)
        foreach ($cart as $thuocId => $item) {
            $donHang->items()->create([
                'thuoc_id' => $thuocId,
                'so_luong' => $item['so_luong'],
                'don_gia' => $item['gia'],
            ]);
        }

        // Tăng số lần đã sử dụng coupon (model có method tangSuDung)
        if ($couponId) {
            $coupon = Coupon::find($couponId);
            if ($coupon) {
                $coupon->tangSuDung();
            }
        }

        // Xóa giỏ hàng
        session()->forget('cart');

        return redirect()->route('patient.shop.order-success', $donHang->id)
            ->with('success', 'Đặt hàng thành công!');
    }

    public function orderSuccess($id)
    {
        $donHang = DonHang::with('coupon')->findOrFail($id);

        if ($donHang->user_id !== auth()->id()) {
            abort(403);
        }

        return view('patient.shop.order-success', compact('donHang'));
    }

    // File mẹ: app/Http/Controllers/Patient/ShopController.php
    public function orders()
    {
        $donHangs = DonHang::where('user_id', auth()->id())
            ->with(['items.thuoc'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patient.shop.orders', compact('donHangs'));
    }

    // File mẹ: app/Http/Controllers/Patient/ShopController.php
    public function orderDetail(DonHang $donHang)
    {
        abort_if($donHang->user_id !== auth()->id(), 403);

        $donHang->load(['items.thuoc', 'coupon']);

        return view('patient.shop.order-detail', compact('donHang'));
    }

    // File mẹ: app/Http/Controllers/Patient/ShopController.php
    public function cancelOrder(DonHang $donHang)
    {
        abort_if($donHang->user_id !== auth()->id(), 403);

        if ($donHang->trang_thai !== 'Chờ xử lý') {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng này');
        }

        $donHang->update(['trang_thai' => 'Đã hủy']);

        return redirect()->route('patient.shop.orders')
            ->with('success', 'Đã hủy đơn hàng thành công');
    }
}

