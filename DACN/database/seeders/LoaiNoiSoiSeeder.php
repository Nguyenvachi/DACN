<?php

namespace Database\Seeders;

use App\Models\ChuyenKhoa;
use App\Models\LoaiNoiSoi;
use App\Models\Phong;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder cho Loại Nội soi
 * Parent file: database/seeders/DatabaseSeeder.php
 * Related files:
 * - app/Models/LoaiNoiSoi.php
 * - database/migrations/2025_12_20_000003_create_loai_noi_sois_table.php
 * - database/migrations/2025_12_20_000004_create_chuyen_khoa_loai_noi_soi_table.php
 */
class LoaiNoiSoiSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $phongThuThuat = Phong::where('ten', 'Phòng Thủ thuật Kế hoạch hóa GĐ')->first()
                ?? Phong::where('ten', 'LIKE', '%Thủ thuật%')->first()
                ?? Phong::where('ten', 'LIKE', '%Phụ khoa%')->first();

            $phongId = $phongThuThuat?->id;

            $items = [
                [
                    'ma' => 'NS_CTC',
                    'ten' => 'Nội soi cổ tử cung',
                    'mo_ta' => 'Quan sát cổ tử cung, đánh giá bất thường và hỗ trợ chẩn đoán viêm/nhiễm hoặc tổn thương nghi ngờ.',
                    'gia' => 350000,
                    'thoi_gian_uoc_tinh' => 20,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Phụ Khoa'],
                ],
                [
                    'ma' => 'NS_BUONG_TU_CUNG',
                    'ten' => 'Nội soi buồng tử cung',
                    'mo_ta' => 'Đánh giá buồng tử cung; hỗ trợ chẩn đoán polyp, u xơ dưới niêm, bất thường nội mạc tử cung.',
                    'gia' => 650000,
                    'thoi_gian_uoc_tinh' => 30,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Phụ Khoa', 'Hiếm muộn & Vô sinh'],
                ],
                [
                    'ma' => 'NS_AM_DAO',
                    'ten' => 'Nội soi âm đạo',
                    'mo_ta' => 'Quan sát thành âm đạo và cổ tử cung; hỗ trợ phát hiện tổn thương, viêm nhiễm, bất thường bề mặt.',
                    'gia' => 300000,
                    'thoi_gian_uoc_tinh' => 20,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Phụ Khoa'],
                ],
            ];

            foreach ($items as $item) {
                $chuyenkhoaNames = $item['chuyenkhoas'] ?? [];
                unset($item['chuyenkhoas']);

                $loai = LoaiNoiSoi::updateOrCreate(
                    ['ma' => $item['ma']],
                    $item
                );

                $attachIds = [];
                foreach ($chuyenkhoaNames as $ckName) {
                    $ck = ChuyenKhoa::where('ten', $ckName)->first();
                    if ($ck) {
                        $attachIds[] = $ck->id;
                    }
                }

                if (! empty($attachIds)) {
                    $loai->chuyenKhoas()->syncWithoutDetaching($attachIds);
                }
            }

            $this->command->info('✅ LoaiNoiSoiSeeder: Ensured sample endoscopy catalog exists (' . LoaiNoiSoi::count() . ' items).');
        });
    }
}
