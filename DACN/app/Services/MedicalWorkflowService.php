<?php

namespace App\Services;

use App\Models\LichHen;
use App\Models\BenhAn;
use App\Models\HoaDon;
use App\Models\XetNghiem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service xử lý luồng nghiệp vụ y tế chuẩn
 * Theo quy trình: Đặt lịch → Xác nhận → Check-in → Khám → Xét nghiệm → Hoàn thành → Thanh toán
 */
class MedicalWorkflowService
{
    /**
     * Bước 3: Nhân viên/Bác sĩ xác nhận lịch hẹn
     */
    /**
     * Bước 2: Nhân viên/Bác sĩ xác nhận lịch hẹn
     * Sử dụng status tiếng Việt theo quy ước cũ của dự án
     */
    public function confirmAppointment(LichHen $lichHen, $confirmedBy = null): bool
    {
        if ($lichHen->trang_thai !== LichHen::STATUS_PENDING_VN) {
            return false;
        }

        $lichHen->update([
            'trang_thai' => LichHen::STATUS_CONFIRMED_VN,
        ]);

        // ✅ Email sẽ được gửi tự động qua LichHenObserver khi status thay đổi
        // Observer sẽ queue email LichHenDaXacNhan

        Log::info("Lịch hẹn #{$lichHen->id} đã được xác nhận bởi {$confirmedBy}");

        return true;
    }

    /**
     * Bước 3: Bệnh nhân check-in khi đến phòng khám
     */
    public function checkInAppointment(LichHen $lichHen): bool
    {
        if ($lichHen->trang_thai !== LichHen::STATUS_CONFIRMED_VN) {
            return false;
        }

        $lichHen->update([
            'trang_thai' => LichHen::STATUS_CHECKED_IN_VN,
            'checked_in_at' => now(),
        ]);

        // TODO: Đưa bệnh nhân vào hàng đợi của bác sĩ

        Log::info("Bệnh nhân đã check-in lịch hẹn #{$lichHen->id}");

        return true;
    }

    /**
     * Bước 4: Bác sĩ bắt đầu khám - tạo bệnh án
     */
    public function startExamination(LichHen $lichHen, array $benhAnData): BenhAn
    {
        if (!in_array($lichHen->trang_thai, [LichHen::STATUS_CHECKED_IN_VN, LichHen::STATUS_CONFIRMED_VN])) {
            throw new \Exception("Lịch hẹn chưa được check-in");
        }

        return DB::transaction(function () use ($lichHen, $benhAnData) {
            // Chuyển trạng thái sang "đang khám"
            $lichHen->update([
                'trang_thai' => LichHen::STATUS_IN_PROGRESS_VN,
            ]);

            // Tạo bệnh án
            $benhAn = BenhAn::create([
                'user_id' => $lichHen->user_id,
                'bac_si_id' => $lichHen->bac_si_id,
                'lich_hen_id' => $lichHen->id,
                'ngay_kham' => now(),
                'tieu_de' => $benhAnData['tieu_de'] ?? 'Khám ' . ($lichHen->dichVu->ten_dich_vu ?? 'bệnh'),
                'trieu_chung' => $benhAnData['trieu_chung'] ?? null,
                'chuan_doan' => $benhAnData['chuan_doan'] ?? null,
                'dieu_tri' => $benhAnData['dieu_tri'] ?? null,
                'ghi_chu' => $benhAnData['ghi_chu'] ?? null,
            ]);

            Log::info("Bác sĩ bắt đầu khám lịch hẹn #{$lichHen->id}, tạo bệnh án #{$benhAn->id}");

            return $benhAn;
        });
    }

    /**
     * Bước 6: Chỉ định xét nghiệm
     */
    public function createTestRequest(BenhAn $benhAn, array $testData): XetNghiem
    {
        $xetNghiem = XetNghiem::create([
            'benh_an_id' => $benhAn->id,
            'user_id' => $benhAn->user_id,
            'bac_si_id' => $benhAn->bac_si_id,
            'loai' => $testData['loai'],
            'mo_ta' => $testData['mo_ta'] ?? null,
            'file_path' => '', // Để trống, sẽ upload sau
            'trang_thai' => XetNghiem::STATUS_PENDING,
        ]);

        Log::info("Tạo yêu cầu xét nghiệm #{$xetNghiem->id} cho bệnh án #{$benhAn->id}");

        return $xetNghiem;
    }

    /**
     * Bước 7: Kỹ thuật viên cập nhật kết quả xét nghiệm
     */
    public function updateTestResult(XetNghiem $xetNghiem, string $filePath, string $disk = 'public'): bool
    {
        $xetNghiem->update([
            'file_path' => $filePath,
            'disk' => $disk,
            'trang_thai' => XetNghiem::STATUS_COMPLETED,
        ]);

        // TODO: Thông báo cho bác sĩ có kết quả xét nghiệm mới

        Log::info("Cập nhật kết quả xét nghiệm #{$xetNghiem->id}");

        return true;
    }

    /**
     * Bước 8: Hoàn tất khám bệnh - cập nhật chẩn đoán cuối
     */
    public function completeExamination(LichHen $lichHen, array $finalData): bool
    {
        if ($lichHen->trang_thai !== LichHen::STATUS_IN_PROGRESS_VN) {
            return false;
        }

        return DB::transaction(function () use ($lichHen, $finalData) {
            // Cập nhật bệnh án với chẩn đoán cuối cùng
            $benhAn = BenhAn::where('lich_hen_id', $lichHen->id)->first();
            if ($benhAn) {
                $benhAn->update([
                    'chuan_doan' => $finalData['chuan_doan'] ?? $benhAn->chuan_doan,
                    'dieu_tri' => $finalData['dieu_tri'] ?? $benhAn->dieu_tri,
                    'ghi_chu' => $finalData['ghi_chu'] ?? $benhAn->ghi_chu,
                ]);
            }

            // Chuyển trạng thái lịch hẹn sang hoàn thành
            $lichHen->update([
                'trang_thai' => LichHen::STATUS_COMPLETED_VN,
                'completed_at' => now(),
            ]);

            // Tự động tạo/cập nhật hóa đơn
            $this->generateInvoice($lichHen);

            Log::info("Hoàn thành khám bệnh lịch hẹn #{$lichHen->id}");

            return true;
        });
    }

    /**
     * Bước 9: Tạo hóa đơn tự động (bao gồm phí khám + xét nghiệm)
     */
    public function generateInvoice(LichHen $lichHen): HoaDon
    {
        // Tính tổng tiền
        $tongTien = $lichHen->tong_tien ?? 0;

        // Thêm phí xét nghiệm (nếu có)
        $phiXetNghiem = 0;
        if ($lichHen->benhAn && $lichHen->benhAn->xetNghiems) {
            // TODO: Tính phí xét nghiệm theo bảng giá
            $phiXetNghiem = $lichHen->benhAn->xetNghiems->count() * 100000; // Giả định 100k/xét nghiệm
        }

        $tongTien += $phiXetNghiem;

        // Kiểm tra hóa đơn đã tồn tại chưa
        $hoaDon = $lichHen->hoaDon;

        if ($hoaDon) {
            // Cập nhật hóa đơn cũ
            $hoaDon->update([
                'tong_tien' => $tongTien,
                'so_tien_con_lai' => $tongTien - $hoaDon->so_tien_da_thanh_toan,
            ]);
        } else {
            // Tạo hóa đơn mới
            $hoaDon = HoaDon::create([
                'lich_hen_id' => $lichHen->id,
                'user_id' => $lichHen->user_id,
                'tong_tien' => $tongTien,
                'trang_thai' => HoaDon::STATUS_UNPAID_VN,
            ]);
        }

        Log::info("Tạo/cập nhật hóa đơn #{$hoaDon->id} cho lịch hẹn #{$lichHen->id}, tổng tiền: {$tongTien}");

        return $hoaDon;
    }

    /**
     * Hủy lịch hẹn
     */
    public function cancelAppointment(LichHen $lichHen, string $reason = null): bool
    {
        if (in_array($lichHen->trang_thai, [LichHen::STATUS_COMPLETED, LichHen::STATUS_CANCELLED])) {
            return false;
        }

        $lichHen->update([
            'trang_thai' => LichHen::STATUS_CANCELLED,
            'ghi_chu' => ($lichHen->ghi_chu ? $lichHen->ghi_chu . "\n" : '') . "Lý do hủy: " . ($reason ?? 'Không rõ'),
        ]);

        // TODO: Hoàn tiền nếu đã thanh toán

        Log::info("Hủy lịch hẹn #{$lichHen->id}: {$reason}");

        return true;
    }

    /**
     * Lấy bệnh án của lịch hẹn (tạo nếu chưa có)
     */
    public function getBenhAn(LichHen $lichHen): ?BenhAn
    {
        return BenhAn::firstOrCreate(
            ['lich_hen_id' => $lichHen->id],
            [
                'user_id' => $lichHen->user_id,
                'bac_si_id' => $lichHen->bac_si_id,
                'ngay_kham' => now(),
            ]
        );
    }
}
