<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\HoaDonChiTiet;
use App\Models\BenhAn;
use App\Models\DonThuoc;
use App\Models\ThanhToan;
use App\Models\NoiSoi;
use App\Models\XQuang;
use App\Models\XetNghiem;
use App\Models\ThuThuat;
use App\Models\DichVu;
use App\Mail\HoaDonHoanTien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HoaDonController extends Controller
{
    /**
     * Danh sách hóa đơn
     */
    public function index()
    {
        $items = HoaDon::with(['lichHen', 'user'])->orderByDesc('id')->paginate(20);
        return view('staff.hoadon.index', compact('items'));
    }

    /**
     * Chi tiết hóa đơn
     */
    public function show(HoaDon $hoaDon)
    {
        $hoaDon->load(['lichHen', 'user', 'thanhToans', 'paymentLogs', 'chiTiets']);
        return view('staff.hoadon.show', compact('hoaDon'));
    }

    /**
     * Xem toa thuốc của bác sĩ từ bệnh án
     */
    public function viewToaThuoc(BenhAn $benhAn)
    {
        // Load toa thuốc và thông tin bệnh án
        $benhAn->load(['donThuoc.thuoc', 'user', 'bacSi', 'lichHen']);

        return view('staff.hoadon.toa-thuoc', compact('benhAn'));
    }

    /**
     * Form tạo hóa đơn từ bệnh án (hiển thị tất cả dịch vụ đã chỉ định)
     */
    public function createFromBenhAn(BenhAn $benhAn)
    {
        // Kiểm tra đã có hóa đơn chưa (tìm theo lich_hen_id)
        $existingHoaDon = null;
        if ($benhAn->lich_hen_id) {
            $existingHoaDon = HoaDon::where('lich_hen_id', $benhAn->lich_hen_id)->first();

            // Nếu đã có hóa đơn VÀ có chi tiết rồi thì redirect
            if ($existingHoaDon && $existingHoaDon->chiTiets()->count() > 0) {
                return redirect()->route('staff.hoadon.show', $existingHoaDon)
                    ->with('info', 'Hóa đơn này đã có chi tiết dịch vụ.');
            }
        }

        // Load tất cả dịch vụ đã chỉ định
        $benhAn->load([
            'donThuocs.items.thuoc',
            'noiSois',
            'xQuangs',
            'xetNghiems',
            'thuThuats',
            'sieuAms',
            'user',
            'bacSi',
            'lichHen.dichVu'
        ]);

        // Lấy danh sách dịch vụ khám
        $dichVuKham = $benhAn->lichHen ? [$benhAn->lichHen->dichVu] : [];

        $thuThuatNames = $benhAn->thuThuats->pluck('ten_thu_thuat')->filter()->unique()->all();
        $thuThuatPriceMap = empty($thuThuatNames)
            ? collect()
            : DichVu::whereIn('ten_dich_vu', $thuThuatNames)->pluck('gia_tien', 'ten_dich_vu');

        return view('staff.hoadon.create-from-benh-an', compact('benhAn', 'dichVuKham', 'existingHoaDon', 'thuThuatPriceMap'));
    }

    /**
     * Lưu hóa đơn từ bệnh án (tính tổng tất cả dịch vụ đã chọn)
     */
    public function storeFromBenhAn(Request $request, BenhAn $benhAn)
    {
        $data = $request->validate([
            'dich_vu' => 'required|array|min:1',
            'dich_vu.*.loai' => 'required|string',
            'dich_vu.*.id' => 'required',
            'dich_vu.*.ten' => 'required|string',
            'dich_vu.*.so_luong' => 'required|integer|min:1',
            'dich_vu.*.don_gia' => 'required|numeric|min:0',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Tính tổng tiền
            $tongTien = 0;
            foreach ($data['dich_vu'] as $dv) {
                $tongTien += $dv['so_luong'] * $dv['don_gia'];
            }

            // Kiểm tra xem đã có hóa đơn chưa
            $hoaDon = null;
            if ($benhAn->lich_hen_id) {
                $hoaDon = HoaDon::where('lich_hen_id', $benhAn->lich_hen_id)->first();
            }

            if ($hoaDon) {
                // Cập nhật hóa đơn hiện tại
                $hoaDon->update([
                    'tong_tien' => $tongTien,
                    'so_tien_con_lai' => $tongTien - $hoaDon->so_tien_da_thanh_toan,
                    'ghi_chu' => $data['ghi_chu'] ?? $hoaDon->ghi_chu,
                ]);

                // Xóa chi tiết cũ (nếu có)
                $hoaDon->chiTiets()->delete();
            } else {
                // Tạo hóa đơn mới
                $hoaDon = HoaDon::create([
                    'user_id' => $benhAn->user_id,
                    'lich_hen_id' => $benhAn->lich_hen_id,
                    'tong_tien' => $tongTien,
                    'so_tien_da_thanh_toan' => 0,
                    'so_tien_con_lai' => $tongTien,
                    'trang_thai' => 'Chưa thanh toán',
                    'status' => 'unpaid',
                    'ghi_chu' => $data['ghi_chu'] ?? null,
                ]);
            }

            // Thêm chi tiết dịch vụ
            foreach ($data['dich_vu'] as $dv) {
                $thanhTien = $dv['so_luong'] * $dv['don_gia'];

                HoaDonChiTiet::create([
                    'hoa_don_id' => $hoaDon->id,
                    'loai_dich_vu' => $dv['loai'],
                    'dich_vu_id' => $dv['id'],
                    'ten_dich_vu' => $dv['ten'],
                    'so_luong' => $dv['so_luong'],
                    'don_gia' => $dv['don_gia'],
                    'thanh_tien' => $thanhTien,
                ]);
            }

            DB::commit();

            return redirect()->route('staff.hoadon.show', $hoaDon)
                ->with('success', $hoaDon->wasRecentlyCreated ? 'Tạo hóa đơn thành công!' : 'Cập nhật chi tiết hóa đơn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Thu tiền mặt nhanh
     */
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

    /**
     * Hiển thị form hoàn tiền
     */
    public function showRefundForm(HoaDon $hoaDon)
    {
        if ($hoaDon->so_tien_da_thanh_toan <= 0) {
            return back()->with('error', 'Hóa đơn chưa thanh toán, không thể hoàn tiền.');
        }

        if ($hoaDon->status === 'refunded') {
            return back()->with('error', 'Hóa đơn đã được hoàn tiền toàn bộ.');
        }

        return view('staff.hoadon.refund_form', compact('hoaDon'));
    }

    /**
     * Xử lý hoàn tiền
     */
    public function refund(Request $request, HoaDon $hoaDon)
    {
        $data = $request->validate([
            'so_tien_hoan' => 'required|numeric|min:0.01|max:' . $hoaDon->so_tien_da_thanh_toan,
            'ly_do' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $hoanTien = \App\Models\HoanTien::create([
                'hoa_don_id' => $hoaDon->id,
                'so_tien' => $data['so_tien_hoan'],
                'ly_do' => $data['ly_do'],
                'nguoi_duyet_id' => auth()->id(),
                'trang_thai' => 'da_duyet',
                'duyet_luc' => now(),
            ]);

            $hoaDon->so_tien_da_thanh_toan -= $data['so_tien_hoan'];
            $hoaDon->save();

            // Gửi email thông báo hoàn tiền
            if ($hoaDon->user && $hoaDon->user->email) {
                try {
                    Mail::to($hoaDon->user->email)->send(new HoaDonHoanTien($hoaDon, $hoanTien));
                } catch (\Exception $e) {
                    Log::warning('Không gửi được email hoàn tiền: ' . $e->getMessage());
                }
            }

            DB::commit();
            return redirect()->route('staff.hoadon.show', $hoaDon)
                ->with('success', 'Đã hoàn tiền thành công: ' . number_format($data['so_tien_hoan']) . ' VNĐ');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing refund: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi khi hoàn tiền: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách các lần hoàn tiền
     */
    public function refundsList(HoaDon $hoaDon)
    {
        $refunds = $hoaDon->hoanTiens()->with('nguoiDuyet')->orderByDesc('created_at')->paginate(20);
        return view('staff.hoadon.refunds_list', compact('hoaDon', 'refunds'));
    }
}
