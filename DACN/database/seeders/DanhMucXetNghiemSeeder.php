<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhMucXetNghiemSeeder extends Seeder
{
    public function run(): void
    {
        $xetNghiems = [
            // Xét nghiệm máu cơ bản
            [
                'ten_xet_nghiem' => 'Công thức máu',
                'gia_tien' => 50000,
                'ghi_chu' => 'Đếm số lượng và phân loại tế bào máu (hồng cầu, bạch cầu, tiểu cầu)',
            ],
            [
                'ten_xet_nghiem' => 'Sinh hóa máu',
                'gia_tien' => 150000,
                'ghi_chu' => 'Kiểm tra các chỉ số glucose, cholesterol, triglyceride, ure, creatinine',
            ],
            [
                'ten_xet_nghiem' => 'Nhóm máu',
                'gia_tien' => 80000,
                'ghi_chu' => 'Xác định nhóm máu ABO và Rh',
            ],

            // Xét nghiệm liên quan thai sản
            [
                'ten_xet_nghiem' => 'Beta-HCG',
                'gia_tien' => 100000,
                'ghi_chu' => 'Xét nghiệm thai sớm, theo dõi thai kỳ',
            ],
            [
                'ten_xet_nghiem' => 'Triple test',
                'gia_tien' => 350000,
                'ghi_chu' => 'Sàng lọc dị tật thai nhi (AFP, HCG, uE3)',
            ],
            [
                'ten_xet_nghiem' => 'Double test',
                'gia_tien' => 300000,
                'ghi_chu' => 'Sàng lọc hội chứng Down ở thai nhi',
            ],
            [
                'ten_xet_nghiem' => 'NIPT (Xét nghiệm máu thai nhi)',
                'gia_tien' => 8000000,
                'ghi_chu' => 'Sàng lọc bất thường nhiễm sắc thể thai nhi không xâm lấn',
            ],
            [
                'ten_xet_nghiem' => 'Progesterone',
                'gia_tien' => 150000,
                'ghi_chu' => 'Đánh giá chức năng hoàng thể, nguy cơ sẩy thai',
            ],
            [
                'ten_xet_nghiem' => 'Estradiol (E2)',
                'gia_tien' => 150000,
                'ghi_chu' => 'Theo dõi phát triển nang trứng, sức khỏe thai kỳ',
            ],

            // Xét nghiệm hormone
            [
                'ten_xet_nghiem' => 'TSH (Hormone tuyến giáp)',
                'gia_tien' => 120000,
                'ghi_chu' => 'Đánh giá chức năng tuyến giáp',
            ],
            [
                'ten_xet_nghiem' => 'Prolactin',
                'gia_tien' => 130000,
                'ghi_chu' => 'Đánh giá chức năng sinh sản, tiết sữa',
            ],

            // Xét nghiệm nhiễm trùng
            [
                'ten_xet_nghiem' => 'HIV',
                'gia_tien' => 100000,
                'ghi_chu' => 'Xét nghiệm phát hiện virus HIV',
            ],
            [
                'ten_xet_nghiem' => 'HBsAg (Viêm gan B)',
                'gia_tien' => 80000,
                'ghi_chu' => 'Phát hiện kháng nguyên bề mặt virus viêm gan B',
            ],
            [
                'ten_xet_nghiem' => 'Anti-HCV (Viêm gan C)',
                'gia_tien' => 100000,
                'ghi_chu' => 'Phát hiện kháng thể virus viêm gan C',
            ],
            [
                'ten_xet_nghiem' => 'VDRL (Giang mai)',
                'gia_tien' => 70000,
                'ghi_chu' => 'Xét nghiệm sàng lọc giang mai',
            ],
            [
                'ten_xet_nghiem' => 'TORCH',
                'gia_tien' => 450000,
                'ghi_chu' => 'Sàng lọc nhiễm trùng: Toxoplasma, Rubella, CMV, HSV',
            ],
            [
                'ten_xet_nghiem' => 'Rubella IgG',
                'gia_tien' => 120000,
                'ghi_chu' => 'Kiểm tra miễn dịch với virus Rubella (quai bị)',
            ],

            // Xét nghiệm nước tiểu
            [
                'ten_xet_nghiem' => 'Tổng phân tích nước tiểu',
                'gia_tien' => 40000,
                'ghi_chu' => 'Kiểm tra protein, glucose, bạch cầu trong nước tiểu',
            ],
            [
                'ten_xet_nghiem' => 'Cấy nước tiểu',
                'gia_tien' => 150000,
                'ghi_chu' => 'Phát hiện nhiễm trùng đường tiết niệu',
            ],

            // Xét nghiệm khác
            [
                'ten_xet_nghiem' => 'Ferritin',
                'gia_tien' => 140000,
                'ghi_chu' => 'Đánh giá dự trữ sắt trong cơ thể',
            ],
            [
                'ten_xet_nghiem' => 'Vitamin D',
                'gia_tien' => 200000,
                'ghi_chu' => 'Đo nồng độ vitamin D trong máu',
            ],
            [
                'ten_xet_nghiem' => 'Đường huyết đói',
                'gia_tien' => 30000,
                'ghi_chu' => 'Đo glucose máu lúc đói',
            ],
            [
                'ten_xet_nghiem' => 'Test dung nạp glucose (OGTT)',
                'gia_tien' => 120000,
                'ghi_chu' => 'Sàng lọc đái tháo đường thai kỳ',
            ],
        ];

        foreach ($xetNghiems as $xn) {
            DB::table('danh_muc_xet_nghiem')->updateOrInsert(
                ['ten_xet_nghiem' => $xn['ten_xet_nghiem']],
                [
                    'gia_tien' => $xn['gia_tien'],
                    'ghi_chu' => $xn['ghi_chu'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Đã thêm ' . count($xetNghiems) . ' mẫu xét nghiệm!');
    }
}
