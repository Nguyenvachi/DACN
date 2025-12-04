<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ThuocKho;
use App\Models\User;
use Carbon\Carbon;

class CheckThuocHetHan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ngayHienTai = Carbon::now();
        $ngayGioiHan = $ngayHienTai->copy()->addDays(30);

        // Lấy danh sách thuốc sắp hết hạn trong 30 ngày
        $thuocSapHetHan = ThuocKho::with('thuoc', 'nhaCungCap')
            ->whereNotNull('han_su_dung')
            ->whereBetween('han_su_dung', [$ngayHienTai->toDateString(), $ngayGioiHan->toDateString()])
            ->where('so_luong', '>', 0)
            ->orderBy('han_su_dung')
            ->get();

        // Thuốc đã hết hạn
        $thuocDaHetHan = ThuocKho::with('thuoc', 'nhaCungCap')
            ->whereNotNull('han_su_dung')
            ->where('han_su_dung', '<', $ngayHienTai->toDateString())
            ->where('so_luong', '>', 0)
            ->orderBy('han_su_dung')
            ->get();

        if ($thuocSapHetHan->isEmpty() && $thuocDaHetHan->isEmpty()) {
            Log::info('CheckThuocHetHan: Không có thuốc sắp hết hạn hoặc đã hết hạn');
            return;
        }

        // Gửi mail cho admin và nhân viên kho
        $recipients = User::whereIn('role', ['admin', 'nhan_vien'])
            ->whereHas('nhanVien', function ($q) {
                // Chỉ gửi cho nhân viên có vai trò quản lý kho (nếu có phân quyền)
                // Tạm thời gửi cho tất cả admin và nhân viên
            })
            ->get();

        foreach ($recipients as $user) {
            try {
                Mail::send('emails.thuoc_het_han', [
                    'user' => $user,
                    'thuocSapHetHan' => $thuocSapHetHan,
                    'thuocDaHetHan' => $thuocDaHetHan,
                    'ngayKiemTra' => $ngayHienTai,
                ], function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('[Cảnh báo] Thuốc sắp hết hạn - ' . now()->format('d/m/Y'));
                });

                Log::info("CheckThuocHetHan: Đã gửi email cho {$user->email}");
            } catch (\Exception $e) {
                Log::error("CheckThuocHetHan: Lỗi gửi email cho {$user->email}: " . $e->getMessage());
            }
        }

        Log::info('CheckThuocHetHan: Hoàn thành kiểm tra', [
            'sap_het_han' => $thuocSapHetHan->count(),
            'da_het_han' => $thuocDaHetHan->count(),
        ]);
    }
}
