<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NhanVien;
use App\Models\CaLamViecNhanVien;

class CaLamViecNhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $start = Carbon::today();
            $end = (clone $start)->addDays(13); // seed 2 weeks (14 days)

            // Shift templates
            $S = ['bat_dau' => '07:30:00', 'ket_thuc' => '11:30:00']; // sáng
            $A = ['bat_dau' => '08:00:00', 'ket_thuc' => '17:00:00']; // full-day
            $C = ['bat_dau' => '13:30:00', 'ket_thuc' => '17:00:00']; // chiều
            $N = ['bat_dau' => '17:30:00', 'ket_thuc' => '20:30:00']; // tối
            $M = ['bat_dau' => '09:00:00', 'ket_thuc' => '18:00:00']; // manager/IT

            // Per-email schedules (dow 0=Sun .. 6=Sat)
            $schedules = [
                // Lê Minh Nhật: allow old dev email and new local test email
                'nhat.le@vietcare.com' => [1 => [$S, $C], 2 => [$S, $C], 3 => [$S, $C], 4 => [$S, $C], 5 => [$S, $C], 6 => [$S]],
                'henvaemhen@gmail.com' => [1 => [$S, $C], 2 => [$S, $C], 3 => [$S, $C], 4 => [$S, $C], 5 => [$S, $C], 6 => [$S]],
                'hanh.nguyen@vietcare.com' => [1 => [$A], 2 => [$A], 3 => [$A], 4 => [$A], 5 => [$A]],
                'dung.tran@vietcare.com' => [2 => [$N], 3 => [$N], 4 => [$N], 5 => [$N], 6 => [$N]],
                'thao.pham@vietcare.com' => [1 => [['bat_dau' => '08:00:00','ket_thuc' => '16:00:00']], 2 => [['bat_dau' => '08:00:00','ket_thuc' => '16:00:00']], 3 => [['bat_dau' => '08:00:00','ket_thuc' => '16:00:00']], 4 => [['bat_dau' => '08:00:00','ket_thuc' => '16:00:00']], 5 => [['bat_dau' => '08:00:00','ket_thuc' => '16:00:00']], 6 => [['bat_dau' => '08:00:00','ket_thuc' => '12:00:00']]],
                'truc.le@vietcare.com' => [1 => [['bat_dau' => '08:00:00','ket_thuc' => '16:30:00']], 2 => [['bat_dau' => '08:00:00','ket_thuc' => '16:30:00']], 3 => [['bat_dau' => '08:00:00','ket_thuc' => '16:30:00']], 4 => [['bat_dau' => '08:00:00','ket_thuc' => '16:30:00']], 5 => [['bat_dau' => '08:00:00','ket_thuc' => '16:30:00']]],
                'huyen.nguyen@vietcare.com' => [1 => [$A], 2 => [$A], 3 => [$A], 4 => [$A], 5 => [$A]],
                'anh.vu@vietcare.com' => [1 => [$A], 2 => [$A], 3 => [$A], 4 => [$A], 5 => [$A], 6 => [['bat_dau' => '08:00:00','ket_thuc' => '12:00:00']]],
                'nam.hoang@vietcare.com' => [1 => [$S], 2 => [$S], 3 => [$S], 4 => [$S], 5 => [$S], 6 => [$S]],
                'phuong.nguyen@vietcare.com' => [1 => [$M], 2 => [$M], 3 => [$M], 4 => [$M], 5 => [$M]],
                'bao.tran@vietcare.com' => [1 => [$M], 2 => [$M], 3 => [$M], 4 => [$M], 5 => [$M]],
            ];

            for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addDay()) {
                foreach ($schedules as $email => $dowConfig) {
                    $nv = NhanVien::where('email_cong_viec', $email)->first();
                    if (!$nv) continue;
                    $dow = (int)$cursor->dayOfWeek;
                    if (!isset($dowConfig[$dow])) continue;
                    $entries = $dowConfig[$dow];
                    foreach ($entries as $entry) {
                        $bat_dau = $entry['bat_dau'];
                        $ket_thuc = $entry['ket_thuc'];

                        CaLamViecNhanVien::updateOrCreate(
                            [
                                'nhan_vien_id' => $nv->id,
                                'ngay' => $cursor->toDateString(),
                                'bat_dau' => $bat_dau,
                                'ket_thuc' => $ket_thuc,
                            ],
                            [
                                'ghi_chu' => $nv->chuc_vu ?? null,
                            ]
                        );
                    }
                }
            }
        });

        $this->command->info('✅ CaLamViecNhanVienSeeder: Seeded 10 staff schedules for 14 days');
    }
}
