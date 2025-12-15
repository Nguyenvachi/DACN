<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BacSi;
use App\Models\ChuyenKhoa;
use App\Models\Phong;

class BacSiSeeder extends Seeder
{
    /**
     * Consolidated seeder for doctors.
     * - When $forceReplace is true it will remove existing BacSi and associated users
     * - Otherwise it will create or update entries
     */
    public function run(): void
    {
        $forceReplace = (bool) env('SEED_FORCE_REPLACE', true); // set to false if you don't want to remove existing doctors

        DB::transaction(function () use ($forceReplace) {
            if ($forceReplace) {
                $this->command->info('🔄 BacSiSeeder: removing existing BacSi and associated doctor users');
                BacSi::chunk(100, function ($docs) {
                    foreach ($docs as $doc) {
                        try { $doc->chuyenKhoas()->detach(); } catch (\Exception $e) {}
                        try { $doc->phongs()->detach(); } catch (\Exception $e) {}
                        $user = $doc->user;
                        $doc->delete();
                        if ($user && $user->isDoctor()) {
                            try { $user->delete(); } catch (\Exception $e) {}
                        }
                    }
                });
            }

            // Main curated doctors list (previous BacSiReplaceSeeder)
            $doctors = [
                ['ho_ten' => 'TS.BS Nguyễn Thị Lan Anh', 'email' => 'lananh@vietcare.com', 'phone' => '0909111001', 'chuyen_khoa' => 'Sản Khoa', 'kinh_nghiem' => 25, 'dia_chi' => 'Quận 3, TP.HCM', 'mo_ta' => 'Nguyên Trưởng khoa Sản bệnh viện Từ Dũ. Chuyên gia hàng đầu về quản lý thai kỳ nguy cơ cao (tiền sản giật, đái tháo đường thai kỳ) và đỡ sinh khó.'],
                ['ho_ten' => 'ThS.BS Phạm Văn Hùng', 'email' => 'hunghoang@vietcare.com', 'phone' => '0909111002', 'chuyen_khoa' => 'Sản Khoa', 'kinh_nghiem' => 12, 'dia_chi' => 'Quận 7, TP.HCM', 'mo_ta' => 'Thạc sĩ Y khoa chuyên ngành Sản phụ khoa, từng tu nghiệp tại Pháp. Nổi tiếng "mát tay" trong đỡ sinh thường, may thẩm mỹ tầng sinh môn và phẫu thuật lấy thai.'],
                ['ho_ten' => 'BSCKII Trần Thu Hà', 'email' => 'hatran@vietcare.com', 'phone' => '0909111003', 'chuyen_khoa' => 'Phụ Khoa', 'kinh_nghiem' => 18, 'dia_chi' => 'Quận 10, TP.HCM', 'mo_ta' => 'Chuyên gia phẫu thuật nội soi phụ khoa (bóc u xơ tử cung, u nang buồng trứng). Điều trị chuyên sâu các bệnh lý sàn chậu, sa tử cung và són tiểu ở phụ nữ.'],
                ['ho_ten' => 'BS.CKI Nguyễn Thanh Vân', 'email' => 'vannguyen@vietcare.com', 'phone' => '0909111004', 'chuyen_khoa' => 'Phụ Khoa', 'kinh_nghiem' => 10, 'dia_chi' => 'Quận Tân Bình, TP.HCM', 'mo_ta' => 'Chuyên sâu về soi cổ tử cung, điều trị lộ tuyến và các bệnh viêm nhiễm phụ khoa tái phát. Tư vấn sức khỏe tiền mãn kinh.'],
                ['ho_ten' => 'TS.BS Hoàng Minh Tuấn', 'email' => 'tuanhoang@vietcare.com', 'phone' => '0909111005', 'chuyen_khoa' => 'Hiếm muộn & Vô sinh', 'kinh_nghiem' => 20, 'dia_chi' => 'TP. Thủ Đức, TP.HCM', 'mo_ta' => 'Nguyên Phó Giám đốc Trung tâm Hỗ trợ sinh sản Quốc gia. "Bàn tay vàng" điều trị vô sinh nam và thực hiện kỹ thuật IVF/ICSI với tỷ lệ thành công cao.'],
                ['ho_ten' => 'ThS.BS Võ Thị Ngọc', 'email' => 'ngocvo@vietcare.com', 'phone' => '0909111006', 'chuyen_khoa' => 'Hiếm muộn & Vô sinh', 'kinh_nghiem' => 15, 'dia_chi' => 'Quận 5, TP.HCM', 'mo_ta' => 'Chuyên gia về nội tiết sinh sản. Rất giỏi trong việc kích trứng, canh niêm mạc và điều trị hội chứng buồng trứng đa nang (PCOS) cho các cặp đôi mong con.'],
                ['ho_ten' => 'BS.CKI Phạm Thanh Thúy', 'email' => 'thuypham@vietcare.com', 'phone' => '0909111007', 'chuyen_khoa' => 'Siêu âm & Chẩn đoán hình ảnh', 'kinh_nghiem' => 10, 'dia_chi' => 'Quận 1, TP.HCM', 'mo_ta' => 'Có chứng chỉ FMF Quốc tế (London). Chuyên siêu âm 4D/5D tầm soát dị tật thai nhi sớm và siêu âm Doppler tim thai, mạch máu.'],
                ['ho_ten' => 'ThS.BS Nguyễn Hữu Phước', 'email' => 'phuocnguyen@vietcare.com', 'phone' => '0909111008', 'chuyen_khoa' => 'Sàng lọc trước sinh', 'kinh_nghiem' => 9, 'dia_chi' => 'Quận Bình Thạnh, TP.HCM', 'mo_ta' => 'Chuyên gia Di truyền học. Tư vấn chuyên sâu về các kết quả sàng lọc NIPT, Double Test, Triple Test và chọc ối chẩn đoán bất thường nhiễm sắc thể.'],
                ['ho_ten' => 'BS.CKI Đỗ Mỹ Linh', 'email' => 'linhdo@vietcare.com', 'phone' => '0909111009', 'chuyen_khoa' => 'Kế hoạch hóa gia đình', 'kinh_nghiem' => 12, 'dia_chi' => 'Quận Phú Nhuận, TP.HCM', 'mo_ta' => 'Chuyên thực hiện các thủ thuật tránh thai hiện đại: Cấy que Implanon, đặt vòng nội tiết Mirena. Thao tác nhẹ nhàng, không đau, tư vấn tận tình.'],
                ['ho_ten' => 'ThS.BS Lê Thị Mai', 'email' => 'maile@vietcare.com', 'phone' => '0909111010', 'chuyen_khoa' => 'Sản Khoa', 'kinh_nghiem' => 8, 'dia_chi' => 'Quận 4, TP.HCM', 'mo_ta' => 'Bác sĩ trẻ, nhiệt huyết, cập nhật liên tục các phương pháp thai giáo và sinh nở hiện đại (da kề da, kẹp rốn chậm). Được nhiều mẹ bầu trẻ tin tưởng.'],
            ];

            // Also include additional doctors (previous BacSiAddSeeder)
            $additional = [
                ['ho_ten' => 'BS.CKI Trần Văn Minh', 'email' => 'minh.xetnghiem@vietcare.com', 'phone' => '0909111011', 'chuyen_khoa' => 'Xét nghiệm', 'kinh_nghiem' => 15, 'dia_chi' => 'Quận 8, TP.HCM', 'mo_ta' => 'Trưởng khoa Xét nghiệm. Chuyên gia về Huyết học và Vi sinh. Đảm bảo quy trình xét nghiệm đạt chuẩn ISO 15189, kết quả chính xác và nhanh chóng.'],
                ['ho_ten' => 'ThS.BS Nguyễn Ngọc Lan', 'email' => 'lan.thammy@vietcare.com', 'phone' => '0909111012', 'chuyen_khoa' => 'Sàn chậu & Thẩm mỹ nữ', 'kinh_nghiem' => 10, 'dia_chi' => 'Quận 2, TP.HCM', 'mo_ta' => 'Chuyên gia phục hồi sàn chậu sau sinh và thẩm mỹ vùng kín. Rất mát tay trong các thủ thuật làm hồng, se khít và điều trị són tiểu không phẫu thuật.'],
            ];

            $allDocs = array_merge($doctors, $additional);

            // Create or update
            $room = Phong::where('ten', 'Phòng Thăm Khám Chung')->first() ?? Phong::first();
            foreach ($allDocs as $d) {
                $ck = ChuyenKhoa::firstOrCreate(['ten' => $d['chuyen_khoa']], ['slug' => \Illuminate\Support\Str::slug($d['chuyen_khoa']), 'mo_ta' => '']);
                $user = User::updateOrCreate(['email' => $d['email']], ['name' => $d['ho_ten'], 'password' => Hash::make('password'), 'so_dien_thoai' => $d['phone'], 'role' => 'doctor', 'email_verified_at' => Carbon::now()]);
                $user->assignRole('doctor');
                $bacSi = BacSi::updateOrCreate(['email' => $d['email']], ['user_id' => $user->id, 'ho_ten' => $d['ho_ten'], 'email' => $d['email'], 'so_dien_thoai' => $d['phone'], 'chuyen_khoa' => $d['chuyen_khoa'], 'kinh_nghiem' => $d['kinh_nghiem'], 'dia_chi' => $d['dia_chi'], 'mo_ta' => $d['mo_ta'], 'trang_thai' => 'Đang hoạt động']);
                $bacSi->chuyenKhoas()->syncWithoutDetaching([$ck->id]);
                if ($room) $room->bacSis()->syncWithoutDetaching([$bacSi->id]);
            }

            $this->command->info('✅ BacSiSeeder: Ensured curated doctor list exists.');
        });
    }
}
