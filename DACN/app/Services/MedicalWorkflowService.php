<?php

namespace App\Services;

use App\Models\LichHen;
use App\Models\BenhAn;
use App\Models\HoaDon;
use App\Models\User;
use App\Models\XetNghiem;
use App\Models\SieuAm;
use App\Models\XQuang;
use App\Notifications\MedicalUltrasoundRequested;
use App\Notifications\MedicalUltrasoundResultUploaded;
use App\Notifications\MedicalUltrasoundReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

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
     * Check-in with actor (staff user) and saved checked_in_by
     */
    public function checkIn(LichHen $lichHen, $actor = null): bool
    {
        if (! $lichHen->isCheckinAllowed()) {
            return false;
        }

        $lichHen->update([
            'trang_thai' => LichHen::STATUS_CHECKED_IN_VN,
            'checked_in_at' => now(),
            'checked_in_by' => $actor ? ($actor->id ?? null) : null,
        ]);

        if (function_exists('activity')) {
            try {
                call_user_func('activity')->performedOn($lichHen)->causedBy($actor)->log('Nhân viên check-in bệnh nhân');
            } catch (\Throwable $e) {
                Log::warning('Activity log failed: ' . $e->getMessage());
            }
        }

        return true;
    }

    /**
     * Bước 4: Bác sĩ bắt đầu khám - tạo bệnh án
     */
    public function startExamination(LichHen $lichHen, array $benhAnData): BenhAn
    {
        if (!in_array($lichHen->trang_thai, [
            LichHen::STATUS_CHECKED_IN_VN,
            LichHen::STATUS_CONFIRMED_VN,
            LichHen::STATUS_IN_PROGRESS_VN,
        ])) {
            throw new \Exception("Lịch hẹn chưa được check-in hoặc không hợp lệ");
        }

        return DB::transaction(function () use ($lichHen, $benhAnData) {
            // Chuyển trạng thái sang "đang khám" và đặt thời gian bắt đầu nếu chưa có
            $updates = ['trang_thai' => LichHen::STATUS_IN_PROGRESS_VN];
            if (empty($lichHen->thoi_gian_bat_dau_kham)) {
                $updates['thoi_gian_bat_dau_kham'] = now();
            }
            $lichHen->update($updates);

            // Tạo bệnh án
            // Nếu đã có bệnh án cho lịch hẹn thì trả về bệnh án hiện hữu (tránh tạo trùng)
            $existing = BenhAn::where('lich_hen_id', $lichHen->id)->first();
            if ($existing) {
                return $existing;
            }

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
     * Complete exam with actor
     */
    public function completeExam(LichHen $lichHen, $actor = null, array $finalData = []): bool
    {
        if ($lichHen->trang_thai !== LichHen::STATUS_IN_PROGRESS_VN) {
            return false;
        }

        return $this->completeExamination($lichHen, $finalData);
    }

    /**
     * Bước 9: Tạo hóa đơn tự động (bao gồm phí khám + xét nghiệm)
     */
    public function generateInvoice(LichHen $lichHen): HoaDon
    {
        // Ensure relations are available when computing totals
        $lichHen->loadMissing(['benhAn.xetNghiems', 'benhAn.sieuAms', 'benhAn.xQuangs']);

        $baseTongTien = (float) ($lichHen->tong_tien ?? 0);
        $phiXetNghiem = 0.0;
        if ($lichHen->benhAn && $lichHen->benhAn->xetNghiems) {
            // Use snapshot price stored on xet_nghiems.gia (set at request time)
            $phiXetNghiem = (float) $lichHen->benhAn->xetNghiems->sum('gia');
        }

        // THÊM: Phí siêu âm (dịch vụ lâm sàng) - đồng bộ cách cộng phí giống Xét nghiệm
        $phiSieuAm = 0.0;
        if ($lichHen->benhAn && $lichHen->benhAn->sieuAms) {
            $phiSieuAm = (float) $lichHen->benhAn->sieuAms->sum('gia');
        }

        // THÊM: Phí X-Quang (dịch vụ cận lâm sàng) - đồng bộ cách cộng phí giống Xét nghiệm
        $phiXQuang = 0.0;
        if ($lichHen->benhAn && $lichHen->benhAn->xQuangs) {
            $phiXQuang = (float) $lichHen->benhAn->xQuangs->sum('gia');
        }

        $tongTien = $baseTongTien + $phiXetNghiem + $phiSieuAm + $phiXQuang;

        // Kiểm tra hóa đơn đã tồn tại chưa
        $hoaDon = $lichHen->hoaDon;

        if ($hoaDon) {
            // Cập nhật hóa đơn cũ (tổng tiền + còn lại sẽ tự tính qua model event)
            $hoaDon->tong_tien = $tongTien;
            $hoaDon->save();
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
     * Tạo yêu cầu X-Quang (Bác sĩ chỉ định)
     * Parent: MedicalWorkflowService
     */
    public function createXQuangRequest(BenhAn $benhAn, array $data): XQuang
    {
        $xQuang = XQuang::create([
            'benh_an_id' => $benhAn->id,
            'user_id' => $benhAn->user_id,
            'bac_si_id' => $benhAn->bac_si_id,
            'loai_x_quang_id' => $data['loai_x_quang_id'] ?? null,
            'loai' => $data['loai'],
            'mo_ta' => $data['mo_ta'] ?? null,
            'gia' => $data['gia'] ?? 0,
            'ngay_chi_dinh' => $data['ngay_chi_dinh'] ?? now(),
            'trang_thai' => XQuang::STATUS_PENDING,
        ]);

        // Nếu lịch hẹn đã có hóa đơn, cập nhật ngay tổng tiền/còn lại để cộng phí X-Quang
        try {
            $benhAn->loadMissing(['lichHen', 'lichHen.hoaDon', 'xQuangs']);
            if ($benhAn->lichHen && $benhAn->lichHen->hoaDon) {
                $this->generateInvoice($benhAn->lichHen);
            }
        } catch (\Throwable $e) {
            Log::warning('Invoice recalculation skipped after creating X-Quang request', [
                'benh_an_id' => $benhAn->id,
                'error' => $e->getMessage(),
            ]);
        }

        Log::info("Bác sĩ #{$benhAn->bac_si_id} chỉ định X-Quang #{$xQuang->id} cho bệnh án #{$benhAn->id}");

        return $xQuang;
    }

    /**
     * Upload kết quả X-Quang (Kỹ thuật viên thực hiện)
     * Parent: MedicalWorkflowService
     */
    public function uploadXQuangResult(XQuang $xQuang, $file, ?string $nhanXet = null, ?string $ketQua = null): XQuang
    {
        // Xóa file cũ nếu có
        if ($xQuang->file_path) {
            Storage::disk($xQuang->disk ?? 'benh_an_private')->delete($xQuang->file_path);
        }

        // Lưu file mới
        $path = $file->store('x_quang', 'benh_an_private');

        $xQuang->update([
            'file_path' => $path,
            'disk' => 'benh_an_private',
            'nhan_xet' => $nhanXet,
            'ket_qua' => $ketQua,
            'trang_thai' => XQuang::STATUS_COMPLETED,
        ]);

        Log::info("Upload kết quả X-Quang #{$xQuang->id}");

        return $xQuang->fresh();
    }

    /**
     * Cập nhật nhận xét của bác sĩ cho X-Quang
     */
    public function updateXQuangDoctorComment(XQuang $xQuang, string $nhanXet): XQuang
    {
        $xQuang->update(['nhan_xet' => $nhanXet]);

        Log::info("Bác sĩ cập nhật nhận xét cho X-Quang #{$xQuang->id}");

        return $xQuang;
    }

    /**
     * Tạo yêu cầu siêu âm (Bác sĩ chỉ định) - Workflow riêng của Siêu âm
     * Parent: MedicalWorkflowService
     */
    public function createUltrasoundRequest(BenhAn $benhAn, array $data): SieuAm
    {
        $payload = [
            // Đồng bộ với DB: user_id, bac_si_chi_dinh_id là cột chính
            'user_id' => $benhAn->user_id,
            'benh_an_id' => $benhAn->id,
            'bac_si_chi_dinh_id' => $benhAn->bac_si_id,

            'loai_sieu_am_id' => $data['loai_sieu_am_id'] ?? null,
            'loai' => $data['loai'],
            'mo_ta' => $data['mo_ta'] ?? null,
            'gia' => $data['gia'] ?? 0,
            'trang_thai' => SieuAm::STATUS_PENDING,
            'ngay_chi_dinh' => $data['ngay_chi_dinh'] ?? now(),
            'phong_id' => $data['phong_id'] ?? null,
        ];

        // Backward compatibility: chỉ set bac_si_id nếu DB có cột
        try {
            if (Schema::hasColumn('sieu_ams', 'bac_si_id')) {
                $payload['bac_si_id'] = $benhAn->bac_si_id;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $sieuAm = SieuAm::create($payload);

        // Nếu lịch hẹn đã có hóa đơn, cập nhật ngay tổng tiền/còn lại để cộng phí siêu âm
        try {
            $benhAn->loadMissing(['lichHen', 'lichHen.hoaDon', 'sieuAms', 'xetNghiems']);
            if ($benhAn->lichHen && $benhAn->lichHen->hoaDon) {
                $this->generateInvoice($benhAn->lichHen);
            }
        } catch (\Throwable $e) {
            Log::warning('Invoice recalculation skipped after creating ultrasound request', [
                'benh_an_id' => $benhAn->id,
                'error' => $e->getMessage(),
            ]);
        }

        Log::info("Bác sĩ #{$benhAn->bac_si_id} chỉ định siêu âm #{$sieuAm->id} cho bệnh án #{$benhAn->id}");

        // THÊM: Thông báo in-app (database) cho bệnh nhân và staff
        try {
            $benhAn->loadMissing(['user']);

            $patient = $benhAn->user;
            if ($patient) {
                $actionUrl = null;
                try {
                    $actionUrl = route('patient.sieuam.show', $sieuAm->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $patient->notify(new MedicalUltrasoundRequested(
                    $sieuAm,
                    'Bạn có chỉ định siêu âm mới',
                    'Bác sĩ đã tạo chỉ định siêu âm. Vui lòng theo dõi trạng thái và kết quả khi có.',
                    $actionUrl
                ));
            }

            $staffUsers = User::role('staff')->get();
            foreach ($staffUsers as $staff) {
                $actionUrl = null;
                try {
                    $actionUrl = route('staff.sieuam.show', $sieuAm->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $staff->notify(new MedicalUltrasoundRequested(
                    $sieuAm,
                    'Có chỉ định siêu âm mới',
                    'Một ca siêu âm mới vừa được chỉ định. Vui lòng vào danh sách chờ để xử lý.',
                    $actionUrl
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Ultrasound requested notification skipped', [
                'sieu_am_id' => $sieuAm->id,
                'benh_an_id' => $benhAn->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $sieuAm;
    }

    /**
     * Đánh dấu siêu âm đang xử lý và set ngày_thuc_hien nếu chưa có
     */
    public function markUltrasoundProcessing(SieuAm $sieuAm): SieuAm
    {
        $updates = ['trang_thai' => SieuAm::STATUS_PROCESSING];
        if (empty($sieuAm->ngay_thuc_hien)) {
            $updates['ngay_thuc_hien'] = now();
        }

        $sieuAm->update($updates);
        Log::info("Siêu âm #{$sieuAm->id} chuyển trạng thái processing");

        return $sieuAm->fresh();
    }

    /**
     * Upload kết quả siêu âm (Kỹ thuật viên thực hiện)
     */
    public function uploadUltrasoundResult(SieuAm $sieuAm, $file, string $ketQua, ?string $disk = null): SieuAm
    {
        $disk = $disk ?: ($sieuAm->disk ?? 'sieu_am_private');

        // Xóa file cũ nếu có
        if ($sieuAm->file_path) {
            Storage::disk($disk)->delete($sieuAm->file_path);
        }

        // Lưu file mới
        $path = $file->store('sieu_am', $disk);

        $updates = [
            'file_path' => $path,
            'disk' => $disk,
            'ket_qua' => $ketQua,
            'trang_thai' => SieuAm::STATUS_COMPLETED,
            'ngay_hoan_thanh' => now(),
        ];
        if (empty($sieuAm->ngay_thuc_hien)) {
            $updates['ngay_thuc_hien'] = now();
        }

        $sieuAm->update($updates);
        Log::info("Upload kết quả siêu âm #{$sieuAm->id}");

        // THÊM: Thông báo cho bệnh nhân và bác sĩ chỉ định
        try {
            $sieuAm->loadMissing(['benhAn.user', 'bacSiChiDinh.user']);

            $patient = $sieuAm->benhAn?->user;
            if ($patient) {
                $actionUrl = null;
                try {
                    $actionUrl = route('patient.sieuam.show', $sieuAm->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $patient->notify(new MedicalUltrasoundResultUploaded(
                    $sieuAm,
                    'Đã có kết quả siêu âm',
                    'Kết quả siêu âm đã được cập nhật. Bạn có thể xem và tải file kết quả.',
                    $actionUrl
                ));
            }

            $doctorUser = $sieuAm->bacSiChiDinh?->user;
            if ($doctorUser) {
                $actionUrl = null;
                try {
                    $actionUrl = route('doctor.sieuam.show', $sieuAm->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $doctorUser->notify(new MedicalUltrasoundResultUploaded(
                    $sieuAm,
                    'Có kết quả siêu âm mới',
                    'Kỹ thuật viên đã upload kết quả siêu âm. Vui lòng vào xem và nhận xét.',
                    $actionUrl
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Ultrasound result uploaded notification skipped', [
                'sieu_am_id' => $sieuAm->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $sieuAm->fresh();
    }

    /**
     * Bác sĩ cập nhật nhận xét siêu âm (parity với updateDoctorComment của Xét nghiệm)
     */
    public function updateUltrasoundReview(SieuAm $sieuAm, string $nhanXet): SieuAm
    {
        $sieuAm->update(['nhan_xet' => $nhanXet]);
        Log::info("Bác sĩ cập nhật nhận xét cho siêu âm #{$sieuAm->id}");

        // THÊM: Thông báo cho bệnh nhân
        try {
            $sieuAm->loadMissing(['benhAn.user']);

            $patient = $sieuAm->benhAn?->user;
            if ($patient) {
                $actionUrl = null;
                try {
                    $actionUrl = route('patient.sieuam.show', $sieuAm->id);
                } catch (\Throwable $e) {
                    $actionUrl = null;
                }

                $patient->notify(new MedicalUltrasoundReviewed(
                    $sieuAm,
                    'Bác sĩ đã nhận xét kết quả siêu âm',
                    'Bác sĩ đã cập nhật nhận xét cho kết quả siêu âm của bạn.',
                    $actionUrl
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Ultrasound reviewed notification skipped', [
                'sieu_am_id' => $sieuAm->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $sieuAm;
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

    /**
     * Tạo yêu cầu xét nghiệm (Bác sĩ chỉ định)
     * Parent: MedicalWorkflowService
     * Child: createTestRequest()
     */
    public function createTestRequest(BenhAn $benhAn, array $data): XetNghiem
    {
        $xetNghiem = XetNghiem::create([
            'benh_an_id' => $benhAn->id,
            'bac_si_id' => $benhAn->bac_si_id,
            'loai_xet_nghiem_id' => $data['loai_xet_nghiem_id'] ?? null,
            'loai' => $data['loai'],
            'mo_ta' => $data['mo_ta'] ?? null,
            'gia' => $data['gia'] ?? 0,
            'trang_thai' => XetNghiem::STATUS_PENDING,
        ]);

        // Nếu lịch hẹn đã có hóa đơn, cập nhật ngay tổng tiền/còn lại để cộng phí xét nghiệm
        try {
            $benhAn->loadMissing(['lichHen', 'lichHen.hoaDon', 'xetNghiems']);
            if ($benhAn->lichHen && $benhAn->lichHen->hoaDon) {
                $this->generateInvoice($benhAn->lichHen);
            }
        } catch (\Throwable $e) {
            // Do not block test request creation if invoice recalculation fails
            Log::warning('Invoice recalculation skipped after creating test request', [
                'benh_an_id' => $benhAn->id,
                'error' => $e->getMessage(),
            ]);
        }

        Log::info("Bác sĩ #{$benhAn->bac_si_id} chỉ định xét nghiệm #{$xetNghiem->id} cho bệnh án #{$benhAn->id}");

        // TODO: Gửi thông báo cho kỹ thuật viên

        return $xetNghiem;
    }

    /**
     * Upload kết quả xét nghiệm (Kỹ thuật viên thực hiện)
     * Parent: MedicalWorkflowService
     * Child: uploadTestResult()
     */
    public function uploadTestResult(XetNghiem $xetNghiem, $file, ?string $nhanXet = null, ?string $ketQua = null): XetNghiem
    {
        // Xóa file cũ nếu có
        if ($xetNghiem->file_path) {
            Storage::disk($xetNghiem->disk ?? 'benh_an_private')->delete($xetNghiem->file_path);
        }

        // Lưu file mới
        $path = $file->store('xet_nghiem', 'benh_an_private');

        $xetNghiem->update([
            'file_path' => $path,
            'disk' => 'benh_an_private',
            'nhan_xet' => $nhanXet,
            'ket_qua' => $ketQua,
            'trang_thai' => XetNghiem::STATUS_COMPLETED,
        ]);

        Log::info("Upload kết quả xét nghiệm #{$xetNghiem->id}");

        // TODO: Gửi thông báo cho bác sĩ và bệnh nhân

        return $xetNghiem->fresh();
    }

    /**
     * Cập nhật nhận xét của bác sĩ
     * Parent: MedicalWorkflowService
     * Child: updateDoctorComment()
     */
    public function updateDoctorComment(XetNghiem $xetNghiem, string $nhanXet): XetNghiem
    {
        $xetNghiem->update(['nhan_xet' => $nhanXet]);

        Log::info("Bác sĩ cập nhật nhận xét cho xét nghiệm #{$xetNghiem->id}");

        return $xetNghiem;
    }
}
