<?php

namespace Database\Seeders;

use App\Models\ChuyenKhoa;
use App\Models\LoaiXetNghiem;
use App\Models\Phong;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiXetNghiemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $labRoom = Phong::where('ten', 'Trung tâm Xét nghiệm (Labo)')->first();
            $labRoomId = $labRoom?->id;

            $items = [
                [
                    'ma' => 'XN_BETA_HCG',
                    'ten' => 'Định lượng Beta-hCG (Máu)',
                    'gia' => 150000,
                    'thoi_gian_uoc_tinh' => 5,
                    'mo_ta' => 'Chẩn đoán thai sớm và theo dõi phát triển thai giai đoạn đầu.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Hiếm muộn & Vô sinh', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_AMH',
                    'ten' => 'Xét nghiệm dự trữ buồng trứng (AMH)',
                    'gia' => 800000,
                    'thoi_gian_uoc_tinh' => 10,
                    'mo_ta' => 'Đánh giá dự trữ buồng trứng, hỗ trợ chẩn đoán và điều trị hiếm muộn.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Hiếm muộn & Vô sinh', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_NOI_TIET_TO_NU_6',
                    'ten' => 'Bộ xét nghiệm Nội tiết tố nữ (6 chỉ số)',
                    'gia' => 1500000,
                    'thoi_gian_uoc_tinh' => 15,
                    'mo_ta' => 'FSH, LH, Estradiol, Prolactin, Progesterone, Testosterone.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Hiếm muộn & Vô sinh', 'Sản Khoa', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_RUBELLA_IGM_IGG',
                    'ten' => 'Xét nghiệm Rubella (IgM/IgG)',
                    'gia' => 300000,
                    'thoi_gian_uoc_tinh' => 10,
                    'mo_ta' => 'Tầm soát kháng thể Rubella trước/sớm trong thai kỳ.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_STD_PANEL',
                    'ten' => 'Xét nghiệm Bệnh lây truyền (STD Panel)',
                    'gia' => 1800000,
                    'thoi_gian_uoc_tinh' => 20,
                    'mo_ta' => 'PCR đa mồi sàng lọc các tác nhân lây qua đường tình dục.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Phụ Khoa', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_CONG_THUC_MAU',
                    'ten' => 'Công thức máu (CBC)',
                    'gia' => 120000,
                    'thoi_gian_uoc_tinh' => 10,
                    'mo_ta' => 'Đánh giá hồng cầu, bạch cầu, tiểu cầu; hỗ trợ chẩn đoán thiếu máu/viêm.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_TONG_PHAN_TICH_NUOC_TIEU',
                    'ten' => 'Tổng phân tích nước tiểu (10 thông số)',
                    'gia' => 80000,
                    'thoi_gian_uoc_tinh' => 10,
                    'mo_ta' => 'Sàng lọc viêm tiết niệu, đường huyết niệu, protein niệu...',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Phụ Khoa', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_DUONG_HUYET_LUC_DOI',
                    'ten' => 'Đường huyết lúc đói',
                    'gia' => 70000,
                    'thoi_gian_uoc_tinh' => 10,
                    'mo_ta' => 'Tầm soát rối loạn đường huyết/đái tháo đường.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_HIV_AB',
                    'ten' => 'HIV Ab/Ag (test nhanh)',
                    'gia' => 120000,
                    'thoi_gian_uoc_tinh' => 15,
                    'mo_ta' => 'Sàng lọc HIV Ab/Ag theo quy trình chuẩn an toàn.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Phụ Khoa', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_HBSAG',
                    'ten' => 'HBsAg (Viêm gan B)',
                    'gia' => 120000,
                    'thoi_gian_uoc_tinh' => 15,
                    'mo_ta' => 'Sàng lọc viêm gan B, đặc biệt quan trọng trong thai kỳ.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Sản Khoa', 'Xét nghiệm'],
                ],
                [
                    'ma' => 'XN_PAP_SMEAR_THINPREP',
                    'ten' => 'Xét nghiệm tế bào cổ tử cung (Pap Smear - ThinPrep)',
                    'gia' => 400000,
                    'thoi_gian_uoc_tinh' => 10,
                    'mo_ta' => 'Tầm soát ung thư cổ tử cung, phát hiện sớm biến đổi tế bào.',
                    'phong_id' => $labRoomId,
                    'active' => true,
                    'chuyenkhoas' => ['Phụ Khoa', 'Sàng lọc trước sinh', 'Xét nghiệm'],
                ],
            ];

            foreach ($items as $item) {
                $chuyenkhoaNames = $item['chuyenkhoas'] ?? [];
                unset($item['chuyenkhoas']);

                $loai = LoaiXetNghiem::updateOrCreate(
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

            $this->command->info('✅ LoaiXetNghiemSeeder: Ensured sample test catalog exists (' . LoaiXetNghiem::count() . ' items).');
        });
    }
}
