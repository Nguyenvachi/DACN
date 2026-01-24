<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DichVu;
use App\Models\ChuyenKhoa;

class DichVuSeeder extends Seeder
{
    /**
     * Consolidated service seeder: removes old services and inserts canonical list.
     */
    public function run(): void
    {
        $forceReplace = (bool) env('SEED_FORCE_REPLACE', true); // set to false to keep existing services and only add/update

        DB::transaction(function () use ($forceReplace) {
            if ($forceReplace) {
                $existing = DichVu::all();
                foreach ($existing as $d) {
                    try { $d->chuyenKhoas()->detach(); } catch (\Exception $e) {}
                    $d->delete();
                }
            }

            $services = [
                ['ten' => 'Khám Thai định kỳ & Tư vấn dinh dưỡng', 'gia' => 200000, 'thoi_gian' => 15, 'mo_ta' => 'Khám lâm sàng, đo bề cao tử cung, vòng bụng, nghe tim thai bằng Doppler. Tư vấn dinh dưỡng và lịch tiêm phòng cho mẹ bầu.', 'chuyenkhoas' => ['Sản Khoa']],
                ['ten' => 'Khám Phụ khoa tổng quát', 'gia' => 250000, 'thoi_gian' => 20, 'mo_ta' => 'Kiểm tra cơ quan sinh dục ngoài và trong, phát hiện sớm các bệnh lý viêm nhiễm, u xơ, u nang. Bao gồm phí dụng cụ dùng một lần.', 'chuyenkhoas' => ['Phụ Khoa']],
                ['ten' => 'Khám & Tư vấn sức khỏe Tiền hôn nhân', 'gia' => 800000, 'thoi_gian' => 30, 'mo_ta' => 'Gói khám sức khỏe sinh sản tổng quát cho cả vợ và chồng trước khi cưới. Tư vấn di truyền và chuẩn bị mang thai an toàn.', 'chuyenkhoas' => ['Hiếm muộn & Vô sinh','Kế hoạch hóa gia đình']],
                ['ten' => 'Tư vấn chuyên sâu Vô sinh - Hiếm muộn', 'gia' => 500000, 'thoi_gian' => 45, 'mo_ta' => 'Bác sĩ chuyên khoa xem hồ sơ cũ, tư vấn phác đồ điều trị và chỉ định các xét nghiệm chuyên sâu cần thiết cho các cặp vợ chồng mong con.', 'chuyenkhoas' => ['Hiếm muộn & Vô sinh']],
                ['ten' => 'Siêu âm thai 5D (Hình thái học)', 'gia' => 500000, 'thoi_gian' => 30, 'mo_ta' => 'Công nghệ dựng hình 5D sắc nét, khảo sát dị tật thai nhi toàn diện (mặt, tim, chi...). Tặng kèm file video và hình ảnh bé qua Zalo/Email.', 'chuyenkhoas' => ['Sản Khoa','Siêu âm & Chẩn đoán hình ảnh']],
                ['ten' => 'Siêu âm đầu dò âm đạo (Transvaginal)', 'gia' => 300000, 'thoi_gian' => 15, 'mo_ta' => 'Kỹ thuật siêu âm qua đường âm đạo giúp quan sát tử cung, buồng trứng rõ nét nhất. Phát hiện thai sớm, u nang buồng trứng, đa nang.', 'chuyenkhoas' => ['Sản Khoa','Phụ Khoa','Siêu âm & Chẩn đoán hình ảnh','Hiếm muộn & Vô sinh']],
                ['ten' => 'Siêu âm Tuyến vú 2 bên', 'gia' => 300000, 'thoi_gian' => 15, 'mo_ta' => 'Tầm soát nang vú, nhân xơ tuyến vú lành tính/ác tính bằng sóng siêu âm. Không đau, không xâm lấn.', 'chuyenkhoas' => ['Phụ Khoa','Siêu âm & Chẩn đoán hình ảnh']],
                ['ten' => 'Sàng lọc trước sinh không xâm lấn (NIPT - 23 cặp NST)', 'gia' => 6500000, 'thoi_gian' => 10, 'mo_ta' => 'Sàng lọc toàn bộ 23 cặp nhiễm sắc thể từ máu mẹ, phát hiện các bất thường vi mất đoạn với độ chính xác >99%. An toàn tuyệt đối cho thai nhi.', 'chuyenkhoas' => ['Sàng lọc trước sinh','Sản Khoa']],
                ['ten' => 'Xét nghiệm tế bào cổ tử cung (Pap Smear - ThinPrep)', 'gia' => 400000, 'thoi_gian' => 10, 'mo_ta' => 'Tầm soát ung thư cổ tử cung phương pháp mới. Phát hiện sớm các biến đổi tế bào tiền ung thư. Khuyên dùng định kỳ hàng năm.', 'chuyenkhoas' => ['Phụ Khoa','Sàng lọc trước sinh']],
                ['ten' => 'Định lượng Beta-hCG (Máu)', 'gia' => 150000, 'thoi_gian' => 5, 'mo_ta' => 'Xét nghiệm máu chẩn đoán thai sớm chính xác nhất (có thể phát hiện trước khi chậm kinh). Theo dõi sự phát triển của thai giai đoạn đầu.', 'chuyenkhoas' => ['Sản Khoa','Hiếm muộn & Vô sinh','Xét nghiệm']],
                ['ten' => 'Cấy que tránh thai Implanon (3 năm)', 'gia' => 3200000, 'thoi_gian' => 20, 'mo_ta' => 'Que cấy tránh thai nội tiết (xuất xứ Mỹ/Châu Âu), hiệu quả ngừa thai lên đến 3 năm. Thủ thuật nhanh, không đau (có gây tê tại chỗ).', 'chuyenkhoas' => ['Kế hoạch hóa gia đình']],
                ['ten' => 'Bơm tinh trùng vào buồng tử cung (IUI)', 'gia' => 3500000, 'thoi_gian' => 60, 'mo_ta' => 'Kỹ thuật lọc rửa và bơm tinh trùng vào buồng tử cung. Hỗ trợ sinh sản cho các cặp vợ chồng hiếm muộn nhẹ, tinh trùng yếu.', 'chuyenkhoas' => ['Hiếm muộn & Vô sinh']],
                ['ten' => 'Gói Tầm soát Ung thư Cổ tử cung (Cơ bản)', 'gia' => 1200000, 'thoi_gian' => 30, 'mo_ta' => 'Bao gồm: Khám phụ khoa, Soi cổ tử cung kỹ thuật số, Xét nghiệm Pap Smear và Test nhanh dịch âm đạo.', 'chuyenkhoas' => ['Phụ Khoa','Xét nghiệm']],
                ['ten' => 'Gói Khám Phụ khoa Tổng quát (VIP)', 'gia' => 2500000, 'thoi_gian' => 45, 'mo_ta' => 'Khám lâm sàng, Siêu âm đầu dò, Soi tươi dịch âm đạo, Tầm soát ung thư (HPV + Pap Smear), Siêu âm tuyến vú.', 'chuyenkhoas' => ['Phụ Khoa','Siêu âm & Chẩn đoán hình ảnh','Xét nghiệm']],
                ['ten' => 'Xét nghiệm dự trữ buồng trứng (AMH)', 'gia' => 800000, 'thoi_gian' => 10, 'mo_ta' => 'Chỉ số quan trọng nhất để đánh giá khả năng sinh sản của phụ nữ. Bắt buộc thực hiện trước khi làm IVF/IUI.', 'chuyenkhoas' => ['Hiếm muộn & Vô sinh','Xét nghiệm']],
                ['ten' => 'Bộ xét nghiệm Nội tiết tố nữ (6 chỉ số)', 'gia' => 1500000, 'thoi_gian' => 15, 'mo_ta' => 'Kiểm tra 6 chỉ số: FSH, LH, Estradiol, Prolactin, Progesterone, Testosterone. Đánh giá rối loạn kinh nguyệt và rụng trứng.', 'chuyenkhoas' => ['Hiếm muộn & Vô sinh','Sản Khoa','Xét nghiệm']],
                ['ten' => 'Tinh dịch đồ', 'gia' => 350000, 'thoi_gian' => 60, 'mo_ta' => 'Phân tích số lượng, khả năng di động và hình dạng của tinh trùng. Bước đầu tiên khám vô sinh nam.', 'chuyenkhoas' => ['Hiếm muộn & Vô sinh','Xét nghiệm']],
                ['ten' => 'Xét nghiệm Bệnh lây truyền (STD Panel)', 'gia' => 1800000, 'thoi_gian' => 20, 'mo_ta' => 'Xét nghiệm PCR đa mồi phát hiện 9 tác nhân lây qua đường tình dục: Lậu, Giang mai, Chlamydia, Nấm, Trichomonas...', 'chuyenkhoas' => ['Phụ Khoa','Xét nghiệm']],
                ['ten' => 'Xét nghiệm Rubella (IgM/IgG)', 'gia' => 300000, 'thoi_gian' => 10, 'mo_ta' => 'Tầm soát kháng thể Rubella cho phụ nữ chuẩn bị mang thai hoặc đang mang thai 3 tháng đầu.', 'chuyenkhoas' => ['Sản Khoa','Xét nghiệm']],
                ['ten' => 'Tập vật lý trị liệu sàn chậu (1 buổi)', 'gia' => 400000, 'thoi_gian' => 45, 'mo_ta' => 'Bài tập chuyên sâu với máy tập Biofeedback giúp co hồi cơ sàn chậu, chống sa tử cung và són tiểu sau sinh.', 'chuyenkhoas' => ['Sàn chậu & Thẩm mỹ nữ']],
                ['ten' => 'Làm hồng & Trẻ hóa vùng kín (Laser)', 'gia' => 5000000, 'thoi_gian' => 60, 'mo_ta' => 'Sử dụng công nghệ Laser CO2 Fractional giúp se khít, làm hồng và trẻ hóa vùng kín không phẫu thuật.', 'chuyenkhoas' => ['Sàn chậu & Thẩm mỹ nữ']],
            ];

            foreach ($services as $s) {
                $dv = DichVu::create(['ten_dich_vu' => $s['ten'], 'mo_ta' => $s['mo_ta'] ?? null, 'gia' => $s['gia'] ?? 0, 'thoi_gian_uoc_tinh' => $s['thoi_gian'] ?? 30]);
                $attach = [];
                foreach ($s['chuyenkhoas'] as $ckname) { $ck = ChuyenKhoa::where('ten', $ckname)->first(); if ($ck) $attach[] = $ck->id; }
                if (!empty($attach)) $dv->chuyenKhoas()->syncWithoutDetaching($attach);
            }

            $this->command->info('✅ DichVuSeeder: replaced all services with canonical list ('.DichVu::count().' services)');
        });
    }
}
