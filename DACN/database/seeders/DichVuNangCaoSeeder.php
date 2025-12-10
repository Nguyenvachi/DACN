<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DichVu;

class DichVuNangCaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dichVuNangCao = [
            [
                'ten_dich_vu' => 'Đo tim thai',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Theo dõi nhịp tim thai nhi để đánh giá sức khỏe thai nhi',
                'gia' => 150000,
                'thoi_gian_uoc_tinh' => 20,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Chọc ối',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Xét nghiệm dịch ối để phát hiện bất thường về nhiễm sắc thể hoặc các vấn đề di truyền',
                'gia' => 3500000,
                'thoi_gian_uoc_tinh' => 45,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Siêu âm 4D',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Siêu âm 4D cho phép quan sát hình ảnh thai nhi chi tiết và sinh động',
                'gia' => 800000,
                'thoi_gian_uoc_tinh' => 30,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Xét nghiệm máu thai nhi',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Xét nghiệm máu để sàng lọc các bệnh lý di truyền và nhiễm sắc thể',
                'gia' => 2500000,
                'thoi_gian_uoc_tinh' => 15,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Test sàng lọc trước sinh',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Xét nghiệm sàng lọc các bất thường thai nhi (Double test, Triple test)',
                'gia' => 1200000,
                'thoi_gian_uoc_tinh' => 15,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Đo độ mờ da gáy',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Đo độ dày của da gáy thai nhi để sàng lọc hội chứng Down',
                'gia' => 600000,
                'thoi_gian_uoc_tinh' => 25,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Sinh thiết nhau thai',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Lấy mẫu mô nhau thai để xét nghiệm di truyền',
                'gia' => 4000000,
                'thoi_gian_uoc_tinh' => 40,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Đo co bóp tử cung',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Theo dõi và đo tần suất co bóp tử cung trong quá trình chuyển dạ',
                'gia' => 200000,
                'thoi_gian_uoc_tinh' => 30,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Xét nghiệm NIPT',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Xét nghiệm sàng lọc DNA thai nhi không xâm lấn qua máu mẹ',
                'gia' => 7000000,
                'thoi_gian_uoc_tinh' => 20,
                'hoat_dong' => true,
            ],
            [
                'ten_dich_vu' => 'Siêu âm Doppler',
                'loai' => 'Nâng cao',
                'mo_ta' => 'Siêu âm Doppler đánh giá lưu lượng máu của thai nhi và dây rốn',
                'gia' => 500000,
                'thoi_gian_uoc_tinh' => 30,
                'hoat_dong' => true,
            ],
        ];

        foreach ($dichVuNangCao as $dv) {
            DichVu::updateOrCreate(
                ['ten_dich_vu' => $dv['ten_dich_vu']],
                $dv
            );
        }

        // Cập nhật các dịch vụ cơ bản hiện có
        DichVu::whereNull('loai')->update(['loai' => 'Cơ bản']);
    }
}
