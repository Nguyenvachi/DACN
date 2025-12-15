<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Phong;
use App\Models\BacSi;

class PhongKhamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $rooms = [
                [
                    'ten' => 'Phòng khám Sản 01 (VIP)',
                    'loai' => 'phong_kham',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 1, Khu A (Cạnh lễ tân)',
                    'dien_tich' => 25,
                    'suc_chua' => 3,
                    'mo_ta' => 'Phòng khám tiêu chuẩn VIP, không gian riêng tư, trang bị ghế khám thông minh.',
                    'bac_si_email' => 'lananh@vietcare.com',
                ],
                [
                    'ten' => 'Phòng khám Sản 02',
                    'loai' => 'phong_kham',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 1, Khu A',
                    'dien_tich' => 20,
                    'suc_chua' => 3,
                    'mo_ta' => 'Chuyên khám thai định kỳ và tư vấn dinh dưỡng.',
                    'bac_si_email' => 'hunghoang@vietcare.com',
                ],
                [
                    'ten' => 'Phòng Siêu âm Chẩn đoán hình ảnh',
                    'loai' => 'phong_xet_nghiem',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 1, Khu B (Đối diện phòng khám Sản)',
                    'dien_tich' => 30,
                    'suc_chua' => 5,
                    'mo_ta' => 'Trang bị máy siêu âm Voluson E10 hiện đại nhất, màn hình LED lớn cho gia đình cùng xem.',
                    'bac_si_email' => 'thuypham@vietcare.com',
                ],
                [
                    'ten' => 'Phòng khám Phụ khoa 01',
                    'loai' => 'phong_kham',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 2, Khu A',
                    'dien_tich' => 20,
                    'suc_chua' => 3,
                    'mo_ta' => 'Phòng khám phụ khoa tổng quát, có khu vực thay đồ kín đáo cho khách hàng.',
                    'bac_si_email' => 'hatran@vietcare.com',
                ],
                [
                    'ten' => 'Phòng Thủ thuật Kế hoạch hóa GĐ',
                    'loai' => 'phong_thu_thuat',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 2, Khu B (Khu vực vô trùng)',
                    'dien_tich' => 35,
                    'suc_chua' => 4,
                    'mo_ta' => 'Chuyên thực hiện cấy que, đặt vòng, hút thai an toàn. Đảm bảo tiêu chuẩn vô khuẩn tuyệt đối.',
                    'bac_si_email' => 'linhdo@vietcare.com',
                ],
                [
                    'ten' => 'Phòng Laser Thẩm mỹ & Sàn chậu',
                    'loai' => 'phong_chuc_nang',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 2, Khu C (Khu vực yên tĩnh)',
                    'dien_tich' => 25,
                    'suc_chua' => 2,
                    'mo_ta' => 'Trang bị máy Laser CO2 và máy tập sàn chậu Biofeedback. Không gian Spa thư giãn.',
                    'bac_si_email' => 'lan.thammy@vietcare.com',
                ],
                [
                    'ten' => 'Phòng Tư vấn Vô sinh - Hiếm muộn',
                    'loai' => 'phong_kham',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 3, Khu A',
                    'dien_tich' => 25,
                    'suc_chua' => 4,
                    'mo_ta' => 'Không gian tư vấn riêng tư, kín đáo cho các cặp đôi.',
                    'bac_si_email' => 'tuanhoang@vietcare.com',
                ],
                [
                    'ten' => 'Phòng Lab IUI & Lọc rửa tinh trùng',
                    'loai' => 'phong_thu_thuat',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 3, Khu B (Cách ly đặc biệt)',
                    'dien_tich' => 40,
                    'suc_chua' => 5,
                    'mo_ta' => 'Phòng Lab đạt chuẩn ISO, kiểm soát nhiệt độ và độ ẩm nghiêm ngặt để nuôi cấy phôi/tinh trùng.',
                    'bac_si_email' => 'ngocvo@vietcare.com',
                ],
                [
                    'ten' => 'Trung tâm Xét nghiệm (Labo)',
                    'loai' => 'phong_xet_nghiem',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 3, Khu C',
                    'dien_tich' => 50,
                    'suc_chua' => 10,
                    'mo_ta' => 'Hệ thống máy xét nghiệm tự động hoàn toàn (Roche/Abbott).',
                    'bac_si_email' => 'minh.xetnghiem@vietcare.com',
                ],
                [
                    'ten' => 'Phòng Tư vấn Sàng lọc Trước sinh',
                    'loai' => 'phong_kham',
                    'trang_thai' => 'Sẵn sàng',
                    'vi_tri' => 'Tầng 1, Khu A (Gần khu lấy máu)',
                    'dien_tich' => 15,
                    'suc_chua' => 3,
                    'mo_ta' => 'Tư vấn chuyên sâu về kết quả NIPT và chọc ối.',
                    'bac_si_email' => 'phuocnguyen@vietcare.com',
                ],
            ];

            foreach ($rooms as $r) {
                $room = Phong::updateOrCreate(
                    ['ten' => $r['ten']],
                    [
                        'loai' => $r['loai'],
                        'mo_ta' => $r['mo_ta'],
                        'trang_thai' => $r['trang_thai'],
                        'vi_tri' => $r['vi_tri'],
                        'dien_tich' => $r['dien_tich'],
                        'suc_chua' => $r['suc_chua'],
                    ]
                );

                // Find doctor by email and attach
                $bacSi = BacSi::where('email', $r['bac_si_email'])->first();
                if ($bacSi) {
                    try {
                        $room->bacSis()->syncWithoutDetaching([$bacSi->id]);
                    } catch (\Throwable $e) {
                        // ignore if pivot fails
                    }
                }
            }

            $this->command->info('✅ PhongKhamSeeder: Seeded or updated ' . count($rooms) . ' rooms.');
        });
    }
}
