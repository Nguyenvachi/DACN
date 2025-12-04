<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Thuoc;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckThuocGiamTon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Lấy danh sách thuốc có tồn kho thấp hơn ngưỡng tối thiểu
        $thuocGiamTon = Thuoc::whereNotNull('ton_toi_thieu')
            ->where('ton_toi_thieu', '>', 0)
            ->withSum('kho as ton_hien_tai', 'so_luong')
            ->get()
            ->filter(function ($thuoc) {
                return ($thuoc->ton_hien_tai ?? 0) < $thuoc->ton_toi_thieu;
            })
            ->map(function ($thuoc) {
                $thuoc->ton_hien_tai = $thuoc->ton_hien_tai ?? 0;
                $thuoc->chenh_lech = $thuoc->ton_toi_thieu - $thuoc->ton_hien_tai;
                return $thuoc;
            })
            ->sortByDesc('chenh_lech');

        if ($thuocGiamTon->isEmpty()) {
            Log::info('CheckThuocGiamTon: Không có thuốc giảm tồn');
            return;
        }

        // Gửi mail cho admin và nhân viên kho
        $recipients = User::whereIn('role', ['admin', 'nhan_vien'])
            ->get();

        foreach ($recipients as $user) {
            try {
                Mail::send('emails.thuoc_giam_ton', [
                    'user' => $user,
                    'thuocGiamTon' => $thuocGiamTon,
                    'ngayKiemTra' => now(),
                ], function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('[Cảnh báo] Thuốc tồn kho thấp - ' . now()->format('d/m/Y'));
                });

                Log::info("CheckThuocGiamTon: Đã gửi email cho {$user->email}");
            } catch (\Exception $e) {
                Log::error("CheckThuocGiamTon: Lỗi gửi email cho {$user->email}: " . $e->getMessage());
            }
        }

        Log::info('CheckThuocGiamTon: Hoàn thành kiểm tra', [
            'so_luong' => $thuocGiamTon->count(),
        ]);
    }
}
