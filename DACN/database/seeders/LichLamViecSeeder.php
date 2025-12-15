<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\BacSi;
use App\Models\Phong;
use App\Models\LichLamViec;
use App\Models\LichNghi;
use App\Models\CaDieuChinhBacSi;

class LichLamViecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Define shifts times
            $times = [
                'sang' => ['bat_dau' => '07:30:00', 'ket_thuc' => '11:30:00'],
                'chieu' => ['bat_dau' => '13:30:00', 'ket_thuc' => '17:00:00'],
                'toi' => ['bat_dau' => '17:30:00', 'ket_thuc' => '20:30:00'],
            ];

            // 1) Ensure ca_khams table contains three shifts (if the table exists in this schema)
            $caShifts = [
                ['ten' => 'Ca Sáng', 'bat_dau' => $times['sang']['bat_dau'], 'ket_thuc' => $times['sang']['ket_thuc']],
                ['ten' => 'Ca Chiều', 'bat_dau' => $times['chieu']['bat_dau'], 'ket_thuc' => $times['chieu']['ket_thuc']],
                ['ten' => 'Ca Tối', 'bat_dau' => $times['toi']['bat_dau'], 'ket_thuc' => $times['toi']['ket_thuc']],
            ];
            if (Schema::hasTable('ca_khams')) {
                foreach ($caShifts as $c) {
                    DB::table('ca_khams')->updateOrInsert(
                        ['ten' => $c['ten']],
                        ['bat_dau' => $c['bat_dau'], 'ket_thuc' => $c['ket_thuc'], 'updated_at' => now(), 'created_at' => now()]
                    );
                }
            }

            // Helper to create weekly schedule, optionally with default phong_id
            $createSchedules = function (BacSi $doctor, array $entries, $defaultPhongId = null) use ($times) {
                foreach ($entries as $entry) {
                    LichLamViec::updateOrCreate([
                        'bac_si_id' => $doctor->id,
                        'ngay_trong_tuan' => $entry['ngay_trong_tuan'],
                        'thoi_gian_bat_dau' => $entry['thoi_gian_bat_dau'],
                        'thoi_gian_ket_thuc' => $entry['thoi_gian_ket_thuc'],
                    ], [
                        'phong_id' => $entry['phong_id'] ?? $defaultPhongId,
                    ]);
                }
            };

            // Groups
            $group1Emails = ['lananh@vietcare.com','hatran@vietcare.com','tuanhoang@vietcare.com','minh.xetnghiem@vietcare.com'];
            $group2Emails = ['hunghoang@vietcare.com','vannguyen@vietcare.com','ngocvo@vietcare.com','thuypham@vietcare.com'];
            $group3Emails = ['maile@vietcare.com','linhdo@vietcare.com','phuocnguyen@vietcare.com','lan.thammy@vietcare.com'];

            // Define entries for groups using day of week 0=Sun .. 6=Sat
            // Group 1: Mon-Fri (sang + chieu), Sat (sang)
            $g1 = [];
            for ($d=1;$d<=5;$d++) {
                $g1[] = ['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['sang']['bat_dau'],'thoi_gian_ket_thuc'=>$times['sang']['ket_thuc']];
                $g1[] = ['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['chieu']['bat_dau'],'thoi_gian_ket_thuc'=>$times['chieu']['ket_thuc']];
            }
            $g1[] = ['ngay_trong_tuan'=>6,'thoi_gian_bat_dau'=>$times['sang']['bat_dau'],'thoi_gian_ket_thuc'=>$times['sang']['ket_thuc']];

            // Group 2: Mon,Wed,Fri (full 3), Tue,Thu,Sat (sang+chieu)
            $g2 = [];
            foreach ([1,3,5] as $d) {
                $g2[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['sang']['bat_dau'],'thoi_gian_ket_thuc'=>$times['sang']['ket_thuc']];
                $g2[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['chieu']['bat_dau'],'thoi_gian_ket_thuc'=>$times['chieu']['ket_thuc']];
                $g2[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['toi']['bat_dau'],'thoi_gian_ket_thuc'=>$times['toi']['ket_thuc']];
            }
            foreach ([2,4,6] as $d) {
                $g2[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['sang']['bat_dau'],'thoi_gian_ket_thuc'=>$times['sang']['ket_thuc']];
                $g2[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['chieu']['bat_dau'],'thoi_gian_ket_thuc'=>$times['chieu']['ket_thuc']];
            }

            // Group 3: Tue,Thu,Sat (full 3), Mon,Wed,Fri (chieu+toi), Sat(full 3), Sun(sang)
            $g3 = [];
            foreach ([2,4,6] as $d) {
                $g3[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['sang']['bat_dau'],'thoi_gian_ket_thuc'=>$times['sang']['ket_thuc']];
                $g3[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['chieu']['bat_dau'],'thoi_gian_ket_thuc'=>$times['chieu']['ket_thuc']];
                $g3[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['toi']['bat_dau'],'thoi_gian_ket_thuc'=>$times['toi']['ket_thuc']];
            }
            foreach ([1,3,5] as $d) {
                $g3[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['chieu']['bat_dau'],'thoi_gian_ket_thuc'=>$times['chieu']['ket_thuc']];
                $g3[]=['ngay_trong_tuan'=>$d,'thoi_gian_bat_dau'=>$times['toi']['bat_dau'],'thoi_gian_ket_thuc'=>$times['toi']['ket_thuc']];
            }
            $g3[]=['ngay_trong_tuan'=>6,'thoi_gian_bat_dau'=>$times['sang']['bat_dau'],'thoi_gian_ket_thuc'=>$times['sang']['ket_thuc']];
            $g3[]=['ngay_trong_tuan'=>6,'thoi_gian_bat_dau'=>$times['chieu']['bat_dau'],'thoi_gian_ket_thuc'=>$times['chieu']['ket_thuc']];
            $g3[]=['ngay_trong_tuan'=>6,'thoi_gian_bat_dau'=>$times['toi']['bat_dau'],'thoi_gian_ket_thuc'=>$times['toi']['ket_thuc']];
            $g3[]=['ngay_trong_tuan'=>0,'thoi_gian_bat_dau'=>$times['sang']['bat_dau'],'thoi_gian_ket_thuc'=>$times['sang']['ket_thuc']];

            // Resolve rooms by name to mapping of name -> id
            $roomNames = [
                'Phòng khám Sản 01 (VIP)', 'Phòng khám Sản 02', 'Phòng Siêu âm Chẩn đoán hình ảnh',
                'Phòng khám Phụ khoa 01', 'Phòng Thủ thuật Kế hoạch hóa GĐ', 'Phòng Laser Thẩm mỹ & Sàn chậu',
                'Phòng Tư vấn Vô sinh - Hiếm muộn', 'Phòng Lab IUI & Lọc rửa tinh trùng', 'Trung tâm Xét nghiệm (Labo)',
                'Phòng Tư vấn Sàng lọc Trước sinh'
            ];
            $roomMap = Phong::whereIn('ten', $roomNames)->get()->keyBy('ten')->map(function($r){return $r->id;})->toArray();

            // Map each doctor email to preferred room name
            $emailToRoom = [
                'lananh@vietcare.com' => 'Phòng khám Sản 01 (VIP)',
                'hatran@vietcare.com' => 'Phòng khám Phụ khoa 01',
                'tuanhoang@vietcare.com' => 'Phòng Tư vấn Vô sinh - Hiếm muộn',
                'minh.xetnghiem@vietcare.com' => 'Trung tâm Xét nghiệm (Labo)',
                'hunghoang@vietcare.com' => 'Phòng khám Sản 02',
                'vannguyen@vietcare.com' => 'Phòng khám Phụ khoa 01',
                'ngocvo@vietcare.com' => 'Phòng Lab IUI & Lọc rửa tinh trùng',
                'thuypham@vietcare.com' => 'Phòng Siêu âm Chẩn đoán hình ảnh',
                'maile@vietcare.com' => 'Phòng khám Sản 02',
                'linhdo@vietcare.com' => 'Phòng Thủ thuật Kế hoạch hóa GĐ',
                'phuocnguyen@vietcare.com' => 'Phòng Tư vấn Sàng lọc Trước sinh',
                'lan.thammy@vietcare.com' => 'Phòng Laser Thẩm mỹ & Sàn chậu',
            ];

            // Apply schedules
            foreach ($group1Emails as $email) {
                $doc = BacSi::where('email', $email)->first();
                if ($doc) {
                    $roomName = $emailToRoom[$email] ?? null;
                    $roomId = $roomName && isset($roomMap[$roomName]) ? $roomMap[$roomName] : null;
                    $createSchedules($doc, $g1, $roomId);
                }
            }
            foreach ($group2Emails as $email) {
                $doc = BacSi::where('email', $email)->first();
                if ($doc) {
                    $roomName = $emailToRoom[$email] ?? null;
                    $roomId = $roomName && isset($roomMap[$roomName]) ? $roomMap[$roomName] : null;
                    $createSchedules($doc, $g2, $roomId);
                }
            }
            foreach ($group3Emails as $email) {
                $doc = BacSi::where('email', $email)->first();
                if ($doc) {
                    $roomName = $emailToRoom[$email] ?? null;
                    $roomId = $roomName && isset($roomMap[$roomName]) ? $roomMap[$roomName] : null;
                    $createSchedules($doc, $g3, $roomId);
                }
            }

            // Create Lich Nghi entries
            $bsHunghoang = BacSi::where('email', 'hunghoang@vietcare.com')->first();
            if ($bsHunghoang) {
                $tomorrow = Carbon::now()->addDay()->toDateString();
                LichNghi::updateOrCreate([
                    'bac_si_id' => $bsHunghoang->id, 'ngay' => $tomorrow, 'bat_dau' => $times['sang']['bat_dau'], 'ket_thuc' => $times['sang']['ket_thuc']
                ], ['ly_do' => 'Hội thảo']);
                LichNghi::updateOrCreate([
                    'bac_si_id' => $bsHunghoang->id, 'ngay' => $tomorrow, 'bat_dau' => $times['chieu']['bat_dau'], 'ket_thuc' => $times['chieu']['ket_thuc']
                ], ['ly_do' => 'Hội thảo']);
            }

            $bsThuypham = BacSi::where('email', 'thuypham@vietcare.com')->first();
            if ($bsThuypham) {
                $date = Carbon::now()->addDays(2)->toDateString();
                LichNghi::updateOrCreate(['bac_si_id' => $bsThuypham->id, 'ngay' => $date, 'bat_dau' => '00:00:00', 'ket_thuc' => '23:59:59'], ['ly_do' => 'Việc riêng']);
            }

            // Create CaDieuChinh (add Sunday morning for lananh)
            $bsLananh = BacSi::where('email', 'lananh@vietcare.com')->first();
            if ($bsLananh) {
                $sun = Carbon::now()->endOfWeek();
                $ngay = $sun->toDateString();
                CaDieuChinhBacSi::updateOrCreate([
                    'bac_si_id' => $bsLananh->id, 'ngay' => $ngay, 'gio_bat_dau' => $times['sang']['bat_dau'], 'gio_ket_thuc' => $times['sang']['ket_thuc'], 'hanh_dong' => 'add'
                ], ['ly_do' => 'Thêm ca Chủ nhật theo yêu cầu']);
            }
        });
        $this->command->info('✅ LichLamViecSeeder: Seeded weekly schedules, exceptions and adjustments for doctors.');
    }
}
