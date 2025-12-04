<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\LichHen;
use App\Models\ThanhToan;
use App\Models\PaymentLog;
use App\Mail\HoaDonHoanTien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class HoaDonController extends Controller
{
    public function index()
    {
        $items = HoaDon::with(['lichHen', 'user'])->orderByDesc('id')->paginate(20);
        return view('admin.hoadon.index', compact('items'));
    }

    public function show(HoaDon $hoaDon)
    {
        $hoaDon->load(['lichHen', 'user', 'thanhToans', 'paymentLogs']);
        return view('admin.hoadon.show', compact('hoaDon'));
    }

    // Tạo hóa đơn từ lịch hẹn nếu chưa có
    public function createFromAppointment(LichHen $lichHen)
    {
        // SỬA: Lấy giá từ lich_hen.tong_tien (đã lưu từ lúc đặt)
        $tongTien = $lichHen->tong_tien ?? 0;

        $hoaDon = HoaDon::firstOrCreate(
            ['lich_hen_id' => $lichHen->id],
            [
                'user_id'    => $lichHen->user_id ?? ($lichHen->benh_nhan_id ?? auth()->id()),
                'tong_tien'  => $tongTien, // SỬA: Dùng giá từ lịch hẹn
                'trang_thai' => 'Chưa thanh toán',
                'phuong_thuc' => null,
                'ghi_chu'    => null,
            ]
        );

        return redirect()->route('admin.hoadon.show', $hoaDon)->with('success', 'Đã tạo/Xem hóa đơn');
    }

    // Thu tiền mặt nhanh: tạo bản ghi thanh toán + cập nhật trạng thái
    public function cashCollect(Request $request, HoaDon $hoaDon)
    {
        $data = $request->validate([
            'so_tien'  => 'required|numeric|min:0',
            'ghi_chu'  => 'nullable|string|max:255',
        ]);

        $payment = ThanhToan::create([
            'hoa_don_id'     => $hoaDon->id,
            'provider'       => 'cash',
            'so_tien'        => $data['so_tien'],
            'tien_te'        => 'VND',
            'trang_thai'     => 'succeeded',
            'transaction_ref' => 'CASH-' . now()->format('YmdHis'),
            'paid_at'        => now(),
            'payload'        => ['note' => $data['ghi_chu'] ?? null],
        ]);

        // Cập nhật số tiền đã thanh toán và trạng thái hóa đơn
        $hoaDon->so_tien_da_thanh_toan += $data['so_tien'];
        $hoaDon->phuong_thuc = 'Tiền mặt';
        $hoaDon->ghi_chu = trim(($hoaDon->ghi_chu ? $hoaDon->ghi_chu . ' | ' : '') . ($data['ghi_chu'] ?? ''));
        $hoaDon->save(); // Tự động cập nhật status qua updatePaymentStatus()

        return back()->with('success', 'Đã ghi nhận thanh toán tiền mặt (#' . $payment->id . ')');
    }

    // THÊM: Xem payment logs (audit trail)
    public function paymentLogs(HoaDon $hoaDon)
    {
        $logs = $hoaDon->paymentLogs()->orderByDesc('created_at')->paginate(20);
        return view('admin.hoadon.payment_logs', compact('hoaDon', 'logs'));
    }

    /**
     * Hiển thị form hoàn tiền
     */
    public function showRefundForm(HoaDon $hoaDon)
    {
        // Kiểm tra có thể hoàn tiền không
        if ($hoaDon->so_tien_da_thanh_toan <= 0) {
            return back()->with('error', 'Hóa đơn chưa thanh toán, không thể hoàn tiền.');
        }

        if ($hoaDon->status === 'refunded') {
            return back()->with('error', 'Hóa đơn đã được hoàn tiền toàn bộ.');
        }

        return view('admin.hoadon.refund_form', compact('hoaDon'));
    }

    /**
     * Xử lý hoàn tiền
     */
    public function refund(Request $request, HoaDon $hoaDon)
    {
        $validated = $request->validate([
            'so_tien' => 'required|numeric|min:0.01|max:' . $hoaDon->so_tien_da_thanh_toan,
            'ly_do' => 'required|string|max:500',
            'phuong_thuc' => 'required|in:tien_mat,chuyen_khoan,hoan_cong',
        ]);

        // Kiểm tra điều kiện hoàn tiền
        if ($hoaDon->so_tien_da_thanh_toan <= 0) {
            return back()->with('error', 'Hóa đơn chưa thanh toán, không thể hoàn tiền.');
        }

        $soTienHoan = floatval($validated['so_tien']);
        $maxRefund = $hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan;

        if ($soTienHoan > $maxRefund) {
            return back()->with('error', 'Số tiền hoàn vượt quá số tiền đã thanh toán.');
        }

        // Tạo bản ghi hoàn tiền
        $hoanTien = \App\Models\HoanTien::create([
            'hoa_don_id' => $hoaDon->id,
            'so_tien' => $soTienHoan,
            'ly_do' => $validated['ly_do'],
            'trang_thai' => 'Đang xử lý',
            'provider' => $validated['phuong_thuc'],
            'provider_ref' => 'REFUND-' . now()->format('YmdHis') . '-' . $hoaDon->id,
            'payload' => [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'created_at' => now()->toDateTimeString(),
            ],
        ]);

        // Cập nhật trạng thái hoàn tiền thành công (trong thực tế có thể cần xử lý với payment gateway)
        $hoanTien->update(['trang_thai' => 'Hoàn thành']);

        // Cập nhật số tiền đã hoàn và tính lại trạng thái hóa đơn
        $hoaDon->recalculatePaidAmount();

        // Gửi email thông báo cho bệnh nhân
        try {
            $user = $hoaDon->user;
            if ($user && $user->email) {
                Mail::to($user->email)->send(new HoaDonHoanTien($hoaDon, $hoanTien));
            }
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email hoàn tiền: ' . $e->getMessage());
        }

        return redirect()->route('admin.hoadon.show', $hoaDon)
            ->with('success', 'Đã hoàn tiền ' . number_format($soTienHoan, 0, ',', '.') . ' VNĐ cho hóa đơn #' . $hoaDon->ma_hoa_don);
    }

    /**
     * Danh sách các khoản hoàn tiền
     */
    public function refundsList(HoaDon $hoaDon)
    {
        $hoanTiens = $hoaDon->hoanTiens()->orderByDesc('created_at')->get();
        return view('admin.hoadon.refunds_list', compact('hoaDon', 'hoanTiens'));
    }
}
