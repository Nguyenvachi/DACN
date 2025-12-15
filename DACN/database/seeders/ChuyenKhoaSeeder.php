<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ChuyenKhoa;

class ChuyenKhoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $specialties = [
                [
                    'ten' => 'Sản Khoa',
                    'mo_ta' => 'Chuyên sâu về theo dõi thai kỳ, quản lý thai nghén nguy cơ cao (tiền sản giật, đái tháo đường thai kỳ), tư vấn dinh dưỡng và chuẩn bị cho cuộc vượt cạn an toàn.',
                ],
                [
                    'ten' => 'Phụ Khoa',
                    'mo_ta' => 'Khám và điều trị các bệnh lý viêm nhiễm phụ khoa, lộ tuyến cổ tử cung, u xơ tử cung, u nang buồng trứng, rối loạn kinh nguyệt và sức khỏe tiền mãn kinh.',
                ],
                [
                    'ten' => 'Hiếm muộn & Vô sinh',
                    'mo_ta' => 'Tư vấn sức khỏe sinh sản cặp đôi, khám tìm nguyên nhân chậm con. Thực hiện các kỹ thuật hỗ trợ sinh sản như bơm tinh trùng (IUI) và tư vấn thụ tinh ống nghiệm (IVF).',
                ],
                [
                    'ten' => 'Siêu âm & Chẩn đoán hình ảnh',
                    'mo_ta' => 'Thực hiện các kỹ thuật chẩn đoán hình ảnh hiện đại: Siêu âm thai 4D/5D hình thái học, siêu âm Doppler màu tim thai, siêu âm tuyến vú và đầu dò âm đạo.',
                ],
                [
                    'ten' => 'Sàng lọc trước sinh',
                    'mo_ta' => 'Chuyên khoa Di truyền học. Thực hiện và tư vấn các xét nghiệm NIPT, Double Test, Triple Test, chọc ối để phát hiện sớm các dị tật bẩm sinh ở thai nhi.',
                ],
                [
                    'ten' => 'Kế hoạch hóa gia đình',
                    'mo_ta' => 'Tư vấn và thực hiện các biện pháp tránh thai an toàn, hiện đại: Cấy que tránh thai Implanon, đặt vòng nội tiết Mirena, tiêm thuốc tránh thai.',
                ],
                [
                    'ten' => 'Sàn chậu & Thẩm mỹ nữ',
                    'mo_ta' => 'Điều trị các bệnh lý sa tạng chậu, són tiểu sau sinh. Thực hiện các dịch vụ thẩm mỹ, trẻ hóa vùng kín và phục hồi chức năng sàn chậu cho phụ nữ sau sinh.',
                ],
                [
                    'ten' => 'Xét nghiệm',
                    'mo_ta' => 'Trung tâm xét nghiệm thực hiện các chỉ định cận lâm sàng: Huyết học, Sinh hóa, Miễn dịch, Vi sinh và Nội tiết tố phục vụ cho chẩn đoán của bác sĩ lâm sàng.',
                ],
            ];

            foreach ($specialties as $spec) {
                ChuyenKhoa::updateOrCreate([
                    'ten' => $spec['ten'],
                ], [
                    'slug' => Str::slug($spec['ten']),
                    'mo_ta' => $spec['mo_ta'],
                ]);
            }
        });

        $this->command->info('✅ ChuyenKhoaSeeder: Ensured specialties exist.');
    }
}
