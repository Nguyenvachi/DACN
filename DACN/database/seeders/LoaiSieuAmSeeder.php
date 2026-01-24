<?php
// filepath: database/seeders/LoaiSieuAmSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LoaiSieuAm;
use App\Models\ChuyenKhoa;
use App\Models\Phong;

/**
 * Seeder cho Loại Siêu âm
 * Parent file: database/seeders/DatabaseSeeder.php
 * Related files:
 * - app/Models/LoaiSieuAm.php
 * - database/migrations/2025_01_15_100000_create_loai_sieu_ams_table.php
 */
class LoaiSieuAmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Tìm phòng siêu âm trước (bám sát pattern LoaiXetNghiemSeeder)
            $phongSieuAm = Phong::where('ten', 'LIKE', '%Siêu âm%')->first();
            $phongSieuAmId = $phongSieuAm?->id;

            // Dữ liệu loại siêu âm bám sát thực tế phòng khám Sản - Phụ Khoa
            $items = [
                // ========================================
                // NHÓM 1: SIÊU ÂM THAI KỲ
                // ========================================
                [
                    'ten' => 'Siêu âm thai 2D',
                    'mo_ta' => 'Siêu âm thai thông thường, kiểm tra phát triển thai nhi cơ bản: đo CRL, BPD, FL, AC, HC. Theo dõi nhịp tim thai, nước ối và nhau thai.',
                    'gia_mac_dinh' => 200000,
                    'thoi_gian_uoc_tinh' => 20,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm thai 3D/4D',
                    'mo_ta' => 'Siêu âm thai 3 chiều, 4 chiều để quan sát rõ hơn hình ảnh thai nhi, khuôn mặt, chi. Ghi lại video động để gia đình lưu niệm.',
                    'gia_mac_dinh' => 500000,
                    'thoi_gian_uoc_tinh' => 30,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm Doppler thai',
                    'mo_ta' => 'Đánh giá tuần hoàn máu thai: động mạch rốn, động mạch não giữa, tĩnh mạch ống, động mạch tử cung. Phát hiện sớm thiếu oxy thai.',
                    'gia_mac_dinh' => 350000,
                    'thoi_gian_uoc_tinh' => 25,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm hình thái học thai (20-24 tuần)',
                    'mo_ta' => 'Siêu âm tầm soát dị tật cấu trúc thai nhi giai đoạn 20-24 tuần: tim, não, cột sống, chi, môi, khẩu cái. Chuẩn FMF London.',
                    'gia_mac_dinh' => 600000,
                    'thoi_gian_uoc_tinh' => 45,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Sàng lọc trước sinh', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm sàng lọc sớm (11-13 tuần)',
                    'mo_ta' => 'Đo độ mờ da gáy (NT), xương mũi, Doppler tĩnh mạch ống. Ước tính nguy cơ Down, Edward, Patau. Là phần quan trọng của Double Test/Triple Test.',
                    'gia_mac_dinh' => 400000,
                    'thoi_gian_uoc_tinh' => 30,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Sàng lọc trước sinh', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm tim thai',
                    'mo_ta' => 'Siêu âm chuyên sâu tim thai, đánh giá 4 buồng, đường ra/vào, van tim. Phát hiện dị tật tim bẩm sinh. Bắt buộc khi có tiền sử gia đình hoặc Double Test bất thường.',
                    'gia_mac_dinh' => 700000,
                    'thoi_gian_uoc_tinh' => 40,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Sàng lọc trước sinh', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],

                // ========================================
                // NHÓM 2: SIÊU ÂM PHỤ KHOA
                // ========================================
                [
                    'ten' => 'Siêu âm phụ khoa qua bụng',
                    'mo_ta' => 'Siêu âm tử cung, buồng trứng qua thành bụng. Thường dùng cho trinh nữ hoặc không thể siêu âm qua âm đạo.',
                    'gia_mac_dinh' => 150000,
                    'thoi_gian_uoc_tinh' => 15,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Phụ Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm tử cung qua đường âm đạo',
                    'mo_ta' => 'Siêu âm nội soi phụ khoa (Transvaginal), hình ảnh rõ nét hơn. Phát hiện u xơ tử cung, u nang buồng trứng, polyp nội mạc tử cung, viêm nhiễm.',
                    'gia_mac_dinh' => 250000,
                    'thoi_gian_uoc_tinh' => 20,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Phụ Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm đếm noãn',
                    'mo_ta' => 'Theo dõi phát triển nang trứng trong chu kỳ kinh để dự đoán ngày rụng trứng (Follicular tracking). Cần thiết cho giao hợp có chỉ định hoặc IUI.',
                    'gia_mac_dinh' => 150000,
                    'thoi_gian_uoc_tinh' => 15,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Hiếm muộn & Vô sinh', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
                [
                    'ten' => 'Siêu âm vòi trứng - SIS',
                    'mo_ta' => 'Đánh giá độ thông thoáng vòi trứng bằng cách bơm dung dịch muối sinh lý vào buồng tử cung (Saline Infusion Sonography). Thay thế chụp buồng tử cung có thuốc cản quang.',
                    'gia_mac_dinh' => 450000,
                    'thoi_gian_uoc_tinh' => 30,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Hiếm muộn & Vô sinh', 'Phụ Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],

                // ========================================
                // NHÓM 3: SIÊU ÂM VÚ
                // ========================================
                [
                    'ten' => 'Siêu âm vú 2 bên',
                    'mo_ta' => 'Tầm soát u nang, u xơ, khối đặc tuyến vú. Phân biệt khối lành tính/ác tính. Khuyên làm định kỳ hàng năm cho phụ nữ từ 35 tuổi.',
                    'gia_mac_dinh' => 250000,
                    'thoi_gian_uoc_tinh' => 20,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Phụ Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],

                // ========================================
                // NHÓM 4: SIÊU ÂM SAU SINH
                // ========================================
                [
                    'ten' => 'Siêu âm hậu sản',
                    'mo_ta' => 'Kiểm tra tử cung phục hồi sau sinh (6 tuần sau sinh), phát hiện sót rau/màng nhau, nhiễm trùng buồng tử cung.',
                    'gia_mac_dinh' => 180000,
                    'thoi_gian_uoc_tinh' => 20,
                    'phong_id' => $phongSieuAmId,
                    'is_active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Siêu âm & Chẩn đoán hình ảnh'],
                ],
            ];

            foreach ($items as $item) {
                $chuyenkhoaNames = $item['chuyenkhoas'] ?? [];
                unset($item['chuyenkhoas']);

                $loai = LoaiSieuAm::updateOrCreate(
                    ['ten' => $item['ten']],
                    $item
                );

                // Link chuyên khoa (bám sát pattern LoaiXetNghiemSeeder)
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

            $this->command->info('✅ LoaiSieuAmSeeder: Ensured sample ultrasound catalog exists (' . LoaiSieuAm::count() . ' items).');
        });
    }
}
