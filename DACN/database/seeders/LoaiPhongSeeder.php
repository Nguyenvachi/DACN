<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoaiPhongSeeder extends Seeder
{
    public function run(): void
    {
        $loaiPhongs = [
            [
                'ten' => 'Phòng Khám Thai',
                'slug' => 'phong-kham-thai',
                'mo_ta' => 'Phòng khám chuyên khoa thai sản',
            ],
            [
                'ten' => 'Phòng Xét Nghiệm',
                'slug' => 'phong-xet-nghiem',
                'mo_ta' => 'Phòng thực hiện các xét nghiệm máu, nước tiểu, sinh hóa',
            ],
            [
                'ten' => 'Phòng Siêu Âm',
                'slug' => 'phong-sieu-am',
                'mo_ta' => 'Phòng siêu âm thai nhi và các khám đoạn hình ảnh',
            ],
            [
                'ten' => 'Phòng Khám Tổng Quát',
                'slug' => 'phong-kham-tong-quat',
                'mo_ta' => 'Phòng khám bệnh tổng quát, khám sức khỏe định kỳ',
            ],
            [
                'ten' => 'Phòng Thủ Thuật',
                'slug' => 'phong-thu-thuat',
                'mo_ta' => 'Phòng thực hiện các thủ thuật y khoa',
            ],
        ];

        foreach ($loaiPhongs as $loai) {
            DB::table('loai_phongs')->insert([
                'ten' => $loai['ten'],
                'slug' => $loai['slug'],
                'mo_ta' => $loai['mo_ta'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
