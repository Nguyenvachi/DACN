<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\BaiViet;
use App\Models\DanhMuc;
use App\Models\Tag;
use App\Models\User;

class BaiVietSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Ensure categories exist
            $danhMucMap = [];
            $danhMucs = [
                'Cẩm nang Thai kỳ',
                'Vô sinh - Hiếm muộn',
                'Tin tức Y khoa',
                'Hoạt động Phòng khám',
            ];
            foreach ($danhMucs as $dm) {
                $danh = DanhMuc::updateOrCreate(['name' => $dm], ['slug' => Str::slug($dm)]);
                $danhMucMap[$dm] = $danh->id;
            }

            // Ensure tags exist helper
            $tagCache = [];
            $ensureTag = function($name) use (&$tagCache) {
                if (isset($tagCache[$name])) return $tagCache[$name];
                $t = Tag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
                $tagCache[$name] = $t;
                return $t;
            };

            // Find an admin user if exists
            $adminUser = User::role('admin')->first();
            // fallback: first user
            if (! $adminUser) {
                $adminUser = User::first();
            }

            // Posts data
            $posts = [
                [
                    'title' => 'Chế độ dinh dưỡng vàng cho bà bầu 3 tháng đầu: Ăn đúng để con khỏe, mẹ không tăng cân',
                    'danh_muc' => 'Cẩm nang Thai kỳ',
                    'tags' => ['Dinh dưỡng bà bầu', 'Lịch khám thai'],
                    'excerpt' => '3 tháng đầu là giai đoạn quan trọng nhất để hình thành các cơ quan của thai nhi. Cùng tìm hiểu thực đơn chuẩn giúp mẹ khỏe, bé phát triển toàn diện và giảm nghén hiệu quả.',
                    'content' => "Mang thai 3 tháng đầu (tam cá nguyệt thứ nhất) là giai đoạn quan trọng nhất để hình thành các cơ quan thiết yếu của thai nhi như tim, não và tủy sống. Tuy nhiên, đây cũng là giai đoạn mẹ bầu dễ bị nghén nhất. Vậy làm sao để ăn uống đủ chất mà vẫn thoải mái?\n\n1. Axit Folic – \"Thần dược\" ngăn ngừa dị tật\n\nNếu có một chất dinh dưỡng bắt buộc phải bổ sung ngay khi biết tin có thai, đó chính là Axit Folic (Vitamin B9). Dưỡng chất này đóng vai trò then chốt trong việc ngăn ngừa các dị tật ống thần kinh ở thai nhi (nứt đốt sống, vô sọ).\n\nNhu cầu khuyến nghị: 400mcg - 600mcg/ngày.\n\nThực phẩm giàu Folate: Các loại rau màu xanh đậm (súp lơ, cải bó xôi), các loại đậu, ngũ cốc nguyên hạt và trái cây họ cam quýt.\n\n2. Protein và Sắt – Xây dựng tế bào máu\n\nThể tích máu của mẹ sẽ tăng lên 50% trong thai kỳ để nuôi dưỡng bào thai. Do đó, thiếu sắt sẽ dẫn đến thiếu máu, gây mệt mỏi và chóng mặt. Mẹ nên bổ sung: Thịt bò nạc, ức gà, cá hồi (đã nấu chín kỹ), trứng gà và các loại hạt.\n\n3. Danh sách thực phẩm cần \"Tuyệt đối tránh\"\n\nĐể đảm bảo an toàn cho thai nhi, mẹ bầu 3 tháng đầu cần loại bỏ ngay các món sau khỏi thực đơn:\n\nThực phẩm sống: Sushi, gỏi cá, trứng lòng đào, thịt tái (nguy cơ nhiễm khuẩn Salmonella, E.coli).\n\nRau củ gây co thắt tử cung: Rau răm, đu đủ xanh, dứa (thơm), ngải cứu.\n\nChất kích thích: Rượu, bia, thuốc lá và hạn chế tối đa Cafein.\n\n4. Mẹo nhỏ giúp mẹ vượt qua cơn nghén\n\nNếu bạn bị nôn nghén nặng, hãy chia nhỏ bữa ăn thành 5-6 bữa/ngày thay vì 3 bữa chính. Luôn chuẩn bị sẵn bánh quy gừng hoặc uống nước chanh ấm vào buổi sáng để giảm cảm giác buồn nôn.",
                    'meta_title' => 'Dinh dưỡng bà bầu 3 tháng đầu: Ăn gì để vào con không vào mẹ?',
                    'meta_description' => 'Hướng dẫn chi tiết thực đơn cho mẹ bầu 3 tháng đầu. Danh sách thực phẩm giàu Axit Folic, Sắt và những món ăn cần kiêng kỵ tuyệt đối để tránh sảy thai.',
                    'thumbnail' => null,
                ],
                [
                    'title' => 'Quy trình Thụ tinh trong ống nghiệm (IVF) chuẩn Châu Âu tại Phòng khám',
                    'danh_muc' => 'Vô sinh - Hiếm muộn',
                    'tags' => ['Thụ tinh ống nghiệm (IVF)'],
                    'excerpt' => 'Giải đáp chi tiết quy trình IVF chuẩn y khoa, từ bước kích trứng, chọc hút đến chuyển phôi. Hy vọng mới cho các cặp vợ chồng mong con với tỷ lệ thành công cao.',
                    'content' => "Thụ tinh trong ống nghiệm (IVF) là kỹ thuật hỗ trợ sinh sản hiện đại nhất hiện nay, mang lại hy vọng cho hàng triệu cặp vợ chồng hiếm muộn. Tại phòng khám của chúng tôi, quy trình IVF được thực hiện khép kín với hệ thống phòng Lab đạt chuẩn ISO.\n\nGiai đoạn 1: Kích thích buồng trứng (Ngày 2 của chu kỳ)\n\nBác sĩ sẽ chỉ định tiêm thuốc kích thích buồng trứng liên tục trong khoảng 9-11 ngày. Mục đích là để thu được số lượng nang noãn tối ưu (thay vì chỉ 1 trứng rụng như chu kỳ tự nhiên). Trong thời gian này, bạn sẽ được siêu âm và xét nghiệm máu 3-4 lần để theo dõi sự phát triển của nang trứng.\n\nGiai đoạn 2: Chọc hút trứng và Lấy tinh trùng\n\nKhi nang trứng đạt kích thước chuẩn, mũi tiêm rụng trứng sẽ được thực hiện. 36 giờ sau, bác sĩ tiến hành chọc hút trứng. Quy trình này diễn ra nhẹ nhàng dưới sự hỗ trợ của gây mê, chỉ mất khoảng 15-20 phút. Song song đó, người chồng sẽ được lấy mẫu tinh trùng để lọc rửa, chọn ra những 'chiến binh' khỏe mạnh nhất.\n\nGiai đoạn 3: Tạo phôi và Nuôi cấy phôi\n\nTrứng và tinh trùng được kết hợp trong đĩa cấy tại phòng Lab. Các chuyên viên phôi học sẽ theo dõi quá trình phân chia tế bào:\n\nPhôi ngày 3: Phôi có khoảng 6-8 tế bào.\n\nPhôi ngày 5 (Phôi nang): Phôi có hàng trăm tế bào, khả năng làm tổ cao hơn.\n\nGiai đoạn 4: Chuyển phôi và Thử thai\n\nBác sĩ dùng một ống thông (catheter) rất nhỏ, mềm để đưa phôi vào buồng tử cung người mẹ. Đây là thủ thuật không đau. Sau 14 ngày, mẹ có thể xét nghiệm Beta-HCG để đón nhận tin vui.",
                    'meta_title' => 'Quy trình thụ tinh trong ống nghiệm (IVF) chuẩn Châu Âu - Tỷ lệ đậu thai cao',
                    'meta_description' => 'Tìm hiểu quy trình IVF khép kín tại phòng khám: Kích trứng, chọc hút, nuôi cấy phôi và chuyển phôi. Giải pháp tối ưu cho các cặp vợ chồng hiếm muộn lâu năm.',
                    'thumbnail' => null,
                ],
                [
                    'title' => 'So sánh Double Test, Triple Test và NIPT: Mẹ bầu nên chọn gói nào?',
                    'danh_muc' => 'Tin tức Y khoa',
                    'tags' => ['Sàng lọc trước sinh', 'Lịch khám thai'],
                    'excerpt' => 'So sánh ưu nhược điểm của các phương pháp sàng lọc trước sinh phổ biến hiện nay. Tại sao NIPT lại được nhiều mẹ bầu lựa chọn dù chi phí cao hơn?',
                    'content' => "Sàng lọc trước sinh là bước không thể thiếu để phát hiện sớm các dị tật bẩm sinh do bất thường nhiễm sắc thể (NST). Hiện nay có 3 phương pháp phổ biến, vậy đâu là lựa chọn tốt nhất cho mẹ?\n\n1. Double Test (Sàng lọc quý I)\n\nThời điểm: Tuần thai 11 - 13 tuần 6 ngày.\n\nCách thức: Kết hợp siêu âm đo độ mờ da gáy và xét nghiệm máu mẹ.\n\nĐộ chính xác: Khoảng 80 - 85%.\n\nPhát hiện: Hội chứng Down, Edwards, Patau.\n\n2. Triple Test (Sàng lọc quý II)\n\nThời điểm: Tuần thai 15 - 18.\n\nCách thức: Xét nghiệm 3 chỉ số sinh hóa trong máu mẹ.\n\nĐộ chính xác: Thấp hơn Double Test (khoảng 70%).\n\nPhát hiện: Nguy cơ dị tật ống thần kinh và các hội chứng NST.\n\n3. NIPT (Sàng lọc trước sinh không xâm lấn - Cao cấp)\n\nĐây là phương pháp tiên tiến nhất hiện nay, phân tích ADN tự do của thai nhi (cfDNA) có trong máu mẹ.\n\nThời điểm: Thực hiện rất sớm, từ tuần thai thứ 9.\n\nĐộ chính xác: > 99%. Gần như tuyệt đối.\n\nƯu điểm vượt trội: Sàng lọc được toàn bộ 23 cặp NST, phát hiện cả các đột biến vi mất đoạn mà siêu âm hay xét nghiệm thường không thấy.\n\nAn toàn: Chỉ lấy 7-10ml máu mẹ, hoàn toàn không xâm lấn, không gây hại cho thai nhi.\n\nKết luận\n\nNếu có điều kiện kinh tế, các chuyên gia khuyến cáo mẹ nên chọn NIPT ngay từ tuần thứ 10 để an tâm tuyệt đối suốt thai kỳ, giảm thiểu việc phải chọc ối không cần thiết.",
                    'meta_title' => 'So sánh Double Test, Triple Test và NIPT: Mẹ bầu nên chọn gói nào?',
                    'meta_description' => 'Phân tích ưu nhược điểm và độ chính xác của các phương pháp sàng lọc dị tật thai nhi. Tại sao bác sĩ khuyên dùng NIPT từ tuần thứ 9?',
                    'thumbnail' => null,
                ],
                [
                    'title' => '[HOT] Chào đón Giáng Sinh - Tặng gói quà sơ sinh 5 Triệu khi đăng ký Thai sản trọn gói',
                    'danh_muc' => 'Hoạt động Phòng khám',
                    'tags' => ['Ưu đãi', 'Sale'],
                    'excerpt' => 'Tri ân khách hàng dịp cuối năm, phòng khám dành tặng hàng ngàn voucher giảm giá và quà tặng sơ sinh cao cấp khi đăng ký gói theo dõi thai kỳ trong tháng 12.',
                    'content' => "Thấu hiểu nỗi lo chi phí của các gia đình trẻ, Phòng khám Sản-Phụ khoa xin gửi đến chương trình ưu đãi lớn nhất năm: \"Giáng sinh an lành - Đón rồng con khỏe mạnh\".\n\n🎁 Chi tiết ưu đãi:\n\nGIẢM TRỰC TIẾP 20% chi phí khi đăng ký Gói theo dõi thai kỳ từ tuần 12.\n\nTẶNG NGAY gói sàng lọc sơ sinh (lấy máu gót chân) cho bé sau sinh trị giá 2.000.000đ.\n\nMiễn phí 01 lần siêu âm 4D VIP (có ghi đĩa/gửi file video).\n\nTặng bộ quà tặng mẹ & bé cao cấp: Balo bỉm sữa, quần áo sơ sinh...\n\n⏰ Thời gian và Điều kiện áp dụng:\n\nChương trình diễn ra từ: 10/12/2025 đến hết 31/12/2025.\n\nÁp dụng cho khách hàng đặt cọc online hoặc đến trực tiếp phòng khám.",
                    'meta_title' => '[HOT] Ưu đãi thai sản trọn gói tháng 12: Giảm 20% + Tặng quà 5 Triệu',
                    'meta_description' => 'Chương trình tri ân lớn nhất năm. Giảm ngay 20% chi phí thai sản trọn gói, tặng gói sàng lọc sơ sinh và bộ quà tặng cao cấp cho mẹ và bé.',
                    'thumbnail' => null,
                ],
            ];

            foreach ($posts as $p) {
                $slug = Str::slug(mb_substr($p['title'],0,60,'UTF-8'));
                $post = BaiViet::updateOrCreate([
                    'slug' => $slug
                ],[
                    'user_id' => $adminUser->id ?? null,
                    'danh_muc_id' => $danhMucMap[$p['danh_muc']] ?? null,
                    'title' => $p['title'],
                    'slug' => $slug,
                    'excerpt' => $p['excerpt'],
                    'content' => $p['content'],
                    'status' => 'published',
                    'published_at' => Carbon::now(),
                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],
                    'thumbnail' => $p['thumbnail'] ?? null,
                ]);

                // attach tags (create if not exist)
                $tagIds = [];
                foreach ($p['tags'] as $tname) {
                    $t = $ensureTag($tname);
                    $tagIds[] = $t->id;
                }
                $post->tags()->sync($tagIds);
            }
        });

        $this->command->info('✅ BaiVietSeeder: Seeded 4 articles and linked categories/tags.');
    }
}
