<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\HoaDonChiTiet;
use App\Models\BenhAn;
use App\Models\LichHen;
use App\Models\ThanhToan;
use App\Models\PaymentLog;
use App\Models\DichVu;
use App\Models\HoanTien;
use App\Mail\HoaDonHoanTien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HoaDonController extends Controller
{
    public function index()
    {
        $items = HoaDon::with(['lichHen', 'user'])->orderByDesc('id')->paginate(20);
        return view('admin.hoadon.index', compact('items'));
    }

    public function show(HoaDon $hoaDon)
    {
        $hoaDon->load(['lichHen', 'user', 'thanhToans', 'paymentLogs', 'chiTiets']);
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
                'trang_thai' => \App\Models\HoaDon::STATUS_UNPAID_VN,
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
        $hoaDon->save(); // Tự động cập nhật status + đồng bộ LichHen qua model event

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
                'created_by' => auth()->id(),
                'created_by_name' => auth()->user()->name,
                'created_at' => now()->toDateTimeString(),
            ],
        ]);

        // Admin tự tạo yêu cầu có thể tự động phê duyệt
        $hoanTien->update([
            'trang_thai' => 'Hoàn thành',
            'payload' => array_merge($hoanTien->payload ?? [], [
                'approved_by' => auth()->id(),
                'approved_by_name' => auth()->user()->name,
                'approved_at' => now()->toDateTimeString(),
            ]),
        ]);

        // Cập nhật số tiền đã hoàn và tính lại trạng thái hóa đơn
        $hoaDon->so_tien_da_hoan += $soTienHoan;
        $hoaDon->save();

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

    /**
     * Danh sách tất cả yêu cầu hoàn tiền (trang tổng hợp)
     */
    public function allRefunds(Request $request)
    {
        $query = HoanTien::with(['hoaDon.user', 'hoaDon.lichHen']);

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Sắp xếp theo thời gian mới nhất
        $hoanTiens = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.hoadon.all_refunds', compact('hoanTiens'));
    }

    /**
     * Phê duyệt yêu cầu hoàn tiền
     */
    public function approveRefund(HoanTien $hoanTien)
    {
        if ($hoanTien->trang_thai !== 'Đang xử lý') {
            return back()->with('error', 'Yêu cầu hoàn tiền này đã được xử lý.');
        }

        $hoanTien->update([
            'trang_thai' => 'Hoàn thành',
            'payload' => array_merge($hoanTien->payload ?? [], [
                'approved_by' => auth()->id(),
                'approved_by_name' => auth()->user()->name,
                'approved_at' => now()->toDateTimeString(),
            ]),
        ]);

        // Gửi email thông báo
        try {
            $hoaDon = $hoanTien->hoaDon;
            $user = $hoaDon->user;
            if ($user && $user->email) {
                Mail::to($user->email)->send(new HoaDonHoanTien($hoaDon, $hoanTien));
            }
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email hoàn tiền: ' . $e->getMessage());
        }

        return back()->with('success', 'Đã phê duyệt hoàn tiền ' . number_format($hoanTien->so_tien, 0, ',', '.') . ' VNĐ');
    }

    /**
     * Từ chối yêu cầu hoàn tiền
     */
    public function rejectRefund(Request $request, HoanTien $hoanTien)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500',
        ]);

        if ($hoanTien->trang_thai !== 'Đang xử lý') {
            return back()->with('error', 'Yêu cầu hoàn tiền này đã được xử lý.');
        }

        $hoanTien->update([
            'trang_thai' => 'Từ chối',
            'payload' => array_merge($hoanTien->payload ?? [], [
                'rejected_by' => auth()->id(),
                'rejected_by_name' => auth()->user()->name,
                'rejected_at' => now()->toDateTimeString(),
                'ly_do_tu_choi' => $request->ly_do_tu_choi,
            ]),
        ]);

        // Cập nhật lại hóa đơn (không hoàn tiền nữa)
        $hoaDon = $hoanTien->hoaDon;
        $hoaDon->so_tien_da_hoan -= $hoanTien->so_tien;
        $hoaDon->save();

        return back()->with('success', 'Đã từ chối yêu cầu hoàn tiền');
    }

    /**
     * Hiển thị form tạo hóa đơn từ bệnh án
     */
    public function createFromBenhAn(BenhAn $benhAn)
    {
        // Load tất cả dịch vụ đã làm
        $benhAn->load([
            'noiSois',
            'xQuangs',
            'xetNghiems',
            'thuThuats',
            'donThuocs.items.thuoc',
            'lichHen'
        ]);

        $thuThuatNames = $benhAn->thuThuats->pluck('ten_thu_thuat')->filter()->unique()->all();
        $thuThuatPriceMap = empty($thuThuatNames)
            ? collect()
            : DichVu::whereIn('ten_dich_vu', $thuThuatNames)->pluck('gia_tien', 'ten_dich_vu');

        // Kiểm tra xem đã có hóa đơn cho bệnh án này chưa
        $existingHoaDon = null;
        if ($benhAn->lich_hen_id) {
            $existingHoaDon = HoaDon::where('lich_hen_id', $benhAn->lich_hen_id)->first();
        }

        return view('admin.hoadon.create_from_benh_an', compact('benhAn', 'existingHoaDon', 'thuThuatPriceMap'));
    }

    /**
     * Lưu hóa đơn từ bệnh án với chi tiết dịch vụ
     */
    public function storeFromBenhAn(Request $request, BenhAn $benhAn)
    {
        $validated = $request->validate([
            'dich_vu' => 'required|array',
            'dich_vu.*.loai' => 'required|in:noi_soi,x_quang,xet_nghiem,thu_thuat,thuoc',
            'dich_vu.*.id' => 'required|integer',
            'dich_vu.*.ten' => 'required|string',
            'dich_vu.*.so_luong' => 'required|integer|min:1',
            'dich_vu.*.don_gia' => 'required|numeric|min:0',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Tạo hoặc lấy hóa đơn
            if ($benhAn->lich_hen_id) {
                $hoaDon = HoaDon::firstOrCreate(
                    ['lich_hen_id' => $benhAn->lich_hen_id],
                    [
                        'user_id' => $benhAn->user_id,
                        'tong_tien' => 0,
                        'trang_thai' => HoaDon::STATUS_UNPAID_VN,
                        'ghi_chu' => $validated['ghi_chu'] ?? null,
                    ]
                );
            } else {
                // Tạo hóa đơn mới không liên kết lịch hẹn
                $hoaDon = HoaDon::create([
                    'lich_hen_id' => null,
                    'user_id' => $benhAn->user_id,
                    'tong_tien' => 0,
                    'trang_thai' => HoaDon::STATUS_UNPAID_VN,
                    'ghi_chu' => $validated['ghi_chu'] ?? null,
                ]);
            }

            // Xóa chi tiết cũ (nếu đang chỉnh sửa)
            $hoaDon->chiTiets()->delete();

            // Thêm chi tiết dịch vụ
            $tongTien = 0;
            foreach ($validated['dich_vu'] as $dv) {
                $thanhTien = $dv['so_luong'] * $dv['don_gia'];
                $tongTien += $thanhTien;

                HoaDonChiTiet::create([
                    'hoa_don_id' => $hoaDon->id,
                    'loai_dich_vu' => $dv['loai'],
                    'dich_vu_id' => $dv['id'],
                    'ten_dich_vu' => $dv['ten'],
                    'mo_ta' => $dv['mo_ta'] ?? null,
                    'so_luong' => $dv['so_luong'],
                    'don_gia' => $dv['don_gia'],
                    'thanh_tien' => $thanhTien,
                ]);
            }

            // Cập nhật tổng tiền hóa đơn
            $hoaDon->update([
                'tong_tien' => $tongTien,
                'so_tien_con_lai' => $tongTien,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.hoadon.show', $hoaDon)
                ->with('success', 'Đã tạo hóa đơn từ bệnh án với ' . count($validated['dich_vu']) . ' dịch vụ.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo hóa đơn từ bệnh án: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
