<?php

namespace Database\Seeders;

use App\Models\ChuyenKhoa;
use App\Models\LoaiXQuang;
use App\Models\Phong;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder cho Loại X-Quang
 * Parent file: database/seeders/DatabaseSeeder.php
 * Related files:
 * - app/Models/LoaiXQuang.php
 * - database/migrations/2025_12_20_000001_create_loai_x_quangs_table.php
 * - database/migrations/2025_12_20_000002_create_chuyen_khoa_loai_x_quang_table.php
 */
class LoaiXQuangSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Ưu tiên phòng Chẩn đoán hình ảnh (đang seed sẵn ở PhongKhamSeeder)
            $phongChanDoanHinhAnh = Phong::where('ten', 'Phòng Siêu âm Chẩn đoán hình ảnh')->first()
                ?? Phong::where('ten', 'LIKE', '%Chẩn đoán hình ảnh%')->first()
                ?? Phong::where('ten', 'LIKE', '%Siêu âm%')->first();

            $phongId = $phongChanDoanHinhAnh?->id;

            // Dataset mẫu (idempotent) - bám sát kiểu dữ liệu bảng loai_x_quangs
            $items = [
                [
                    'ma' => 'XQ_NGUC_THANG',
                    'ten' => 'X-Quang ngực thẳng',
                    'mo_ta' => 'Đánh giá phổi, tim, lồng ngực; hỗ trợ chẩn đoán viêm phổi, tràn dịch màng phổi, bất thường tim phổi.',
                    'gia' => 180000,
                    'thoi_gian_uoc_tinh' => 10,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ma' => 'XQ_COT_SONG_CO',
                    'ten' => 'X-Quang cột sống cổ',
                    'mo_ta' => 'Đánh giá thoái hóa, chấn thương, lệch trục cột sống cổ.',
                    'gia' => 220000,
                    'thoi_gian_uoc_tinh' => 15,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ma' => 'XQ_COT_SONG_LUNG',
                    'ten' => 'X-Quang cột sống thắt lưng',
                    'mo_ta' => 'Đánh giá thoái hóa, trượt đốt sống, chấn thương cột sống thắt lưng.',
                    'gia' => 250000,
                    'thoi_gian_uoc_tinh' => 15,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ma' => 'XQ_KHUNG_CHAU',
                    'ten' => 'X-Quang khung chậu',
                    'mo_ta' => 'Đánh giá xương chậu, khớp háng; hỗ trợ chẩn đoán chấn thương, thoái hóa.',
                    'gia' => 240000,
                    'thoi_gian_uoc_tinh' => 15,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ma' => 'XQ_BAN_TAY',
                    'ten' => 'X-Quang bàn tay',
                    'mo_ta' => 'Đánh giá gãy xương, trật khớp, thoái hóa khớp bàn tay.',
                    'gia' => 150000,
                    'thoi_gian_uoc_tinh' => 10,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ma' => 'XQ_BAN_CHAN',
                    'ten' => 'X-Quang bàn chân',
                    'mo_ta' => 'Đánh giá gãy xương, tổn thương khớp bàn chân.',
                    'gia' => 150000,
                    'thoi_gian_uoc_tinh' => 10,
                    'phong_id' => $phongId,
                    'active' => true,
                    'chuyenkhoas' => ['Siêu âm & Chẩn đoán hình ảnh'],
                ],
            ];

            foreach ($items as $item) {
                $chuyenkhoaNames = $item['chuyenkhoas'] ?? [];
                unset($item['chuyenkhoas']);

                $loai = LoaiXQuang::updateOrCreate(
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

                if (!empty($attachIds)) {
                    $loai->chuyenKhoas()->syncWithoutDetaching($attachIds);
                }
            }

            $this->command->info('✅ LoaiXQuangSeeder: Ensured sample X-ray catalog exists (' . LoaiXQuang::count() . ' items).');
        });
    }
}
