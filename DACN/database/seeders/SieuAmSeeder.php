<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SieuAmSeeder extends Seeder
{
    public function run(): void
    {
        $sieuAmServices = [
            [
                'ten_dich_vu' => 'Siêu âm thai thường',
                'mo_ta' => 'Siêu âm thai thường 2D, kiểm tra sức khỏe thai nhi cơ bản',
                'gia_tien' => 200000,
                'thoi_gian' => 20,
                'loai' => 'Cơ bản',
            ],
            [
                'ten_dich_vu' => 'Siêu âm 3D',
                'mo_ta' => 'Siêu âm thai 3D, hình ảnh rõ nét hơn siêu âm thường',
                'gia_tien' => 400000,
                'thoi_gian' => 30,
                'loai' => 'Cơ bản',
            ],
            [
                'ten_dich_vu' => 'Siêu âm 4D',
                'mo_ta' => 'Siêu âm thai 4D, quan sát chuyển động của thai nhi theo thời gian thực',
                'gia_tien' => 600000,
                'thoi_gian' => 40,
                'loai' => 'Cơ bản',
            ],
            [
                'ten_dich_vu' => 'Siêu âm hình thái thai nhi',
                'mo_ta' => 'Siêu âm hình thái thai, sàng lọc dị tật bẩm sinh (tuần 18-22)',
                'gia_tien' => 500000,
                'thoi_gian' => 45,
                'loai' => 'Cơ bản',
            ],
            [
                'ten_dich_vu' => 'Siêu âm Doppler',
                'mo_ta' => 'Siêu âm Doppler đánh giá lưu lượng máu thai nhi, rau thai, dây rốn',
                'gia_tien' => 350000,
                'thoi_gian' => 30,
                'loai' => 'Cơ bản',
            ],
            [
                'ten_dich_vu' => 'Siêu âm vú',
                'mo_ta' => 'Siêu âm vú, phát hiện u nang, u xơ hoặc khối bất thường',
                'gia_tien' => 250000,
                'thoi_gian' => 20,
                'loai' => 'Cơ bản',
            ],
            [
                'ten_dich_vu' => 'Siêu âm tuyến giáp',
                'mo_ta' => 'Siêu âm tuyến giáp, kiểm tra bướu giáp, u tuyến giáp',
                'gia_tien' => 200000,
                'thoi_gian' => 20,
                'loai' => 'Cơ bản',
            ],
        ];

        foreach ($sieuAmServices as $service) {
            DB::table('dich_vus')->insert([
                'ten_dich_vu' => $service['ten_dich_vu'],
                'mo_ta' => $service['mo_ta'],
                'gia_tien' => $service['gia_tien'],
                'thoi_gian' => $service['thoi_gian'],
                'loai' => $service['loai'],
                'hoat_dong' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('✓ Đã thêm ' . count($sieuAmServices) . ' dịch vụ siêu âm');
    }
}
