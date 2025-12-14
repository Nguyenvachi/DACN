<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoaiThuThuatSeeder extends Seeder
{
    public function run(): void
    {
        $loaiThuThuats = [
            [
                'ten' => 'Đặt vòng tránh thai',
                'mo_ta' => 'Đặt vòng tránh thai IUD, phương pháp ngừa thai hiệu quả và an toàn',
                'gia_tien' => 500000,
                'thoi_gian' => 30,
            ],
            [
                'ten' => 'Lấy vòng tránh thai',
                'mo_ta' => 'Lấy vòng tránh thai IUD khi hết thời hạn hoặc có biến chứng',
                'gia_tien' => 300000,
                'thoi_gian' => 20,
            ],
            [
                'ten' => 'Nong ống cổ tử cung',
                'mo_ta' => 'Thủ thuật nong ống cổ tử cung để chuẩn bị cho các thủ thuật khác',
                'gia_tien' => 800000,
                'thoi_gian' => 40,
            ],
            [
                'ten' => 'Chọc ối',
                'mo_ta' => 'Chọc dò nước ối để chẩn đoán di truyền, nhiễm trùng thai nhi',
                'gia_tien' => 3500000,
                'thoi_gian' => 60,
            ],
            [
                'ten' => 'Sinh thiết nhau thai',
                'mo_ta' => 'Lấy mẫu nhau thai để chẩn đoán di truyền sớm',
                'gia_tien' => 4000000,
                'thoi_gian' => 45,
            ],
            [
                'ten' => 'Cắt polyp cổ tử cung',
                'mo_ta' => 'Cắt bỏ polyp cổ tử cung bằng điện đốt hoặc laser',
                'gia_tien' => 1500000,
                'thoi_gian' => 30,
            ],
            [
                'ten' => 'Đốt lạnh cổ tử cung',
                'mo_ta' => 'Điều trị viêm loét cổ tử cung bằng phương pháp đốt lạnh',
                'gia_tien' => 800000,
                'thoi_gian' => 25,
            ],
            [
                'ten' => 'Nạo hút thai',
                'mo_ta' => 'Thủ thuật nạo hút thai trong trường hợp sẩy thai, thai chết lưu',
                'gia_tien' => 2000000,
                'thoi_gian' => 45,
            ],
            [
                'ten' => 'Khâu tầng sinh môn',
                'mo_ta' => 'Khâu vết rách tầng sinh môn sau sinh, giảm đau và phục hồi nhanh',
                'gia_tien' => 1000000,
                'thoi_gian' => 35,
            ],
            [
                'ten' => 'Rạch tầng sinh môn',
                'mo_ta' => 'Thủ thuật rạch tầng sinh môn để hỗ trợ sinh con tự nhiên',
                'gia_tien' => 500000,
                'thoi_gian' => 15,
            ],
        ];

        foreach ($loaiThuThuats as $loai) {
            DB::table('loai_thu_thuats')->insert([
                'ten' => $loai['ten'],
                'mo_ta' => $loai['mo_ta'],
                'gia_tien' => $loai['gia_tien'],
                'thoi_gian' => $loai['thoi_gian'],
                'hoat_dong' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('✓ Đã thêm ' . count($loaiThuThuats) . ' loại thủ thuật');
    }
}
