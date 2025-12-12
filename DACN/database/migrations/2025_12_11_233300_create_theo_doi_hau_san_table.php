<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theo_doi_hau_san', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theo_doi_thai_ky_id')->constrained('theo_doi_thai_ky')->onDelete('cascade');
            $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->onDelete('set null');

            // Thông tin sinh
            $table->date('ngay_sinh')->comment('Ngày sinh thực tế');
            $table->enum('phuong_phap_sinh', ['Sinh thường', 'Sinh mổ', 'Sinh có can thiệp', 'Sinh forceps', 'Sinh vacuum'])->comment('Phương pháp sinh');
            $table->text('dieu_kien_sinh')->nullable()->comment('Điều kiện khi sinh (biến chứng, xử trí...)');

            // Thông tin trẻ
            $table->decimal('can_nang_tre', 5, 2)->nullable()->comment('Cân nặng trẻ (kg)');
            $table->decimal('chieu_dai_tre', 5, 2)->nullable()->comment('Chiều dài trẻ (cm)');
            $table->decimal('vong_dau_tre', 5, 2)->nullable()->comment('Vòng đầu trẻ (cm)');
            $table->integer('diem_apgar_1')->nullable()->comment('Điểm Apgar phút thứ 1');
            $table->integer('diem_apgar_5')->nullable()->comment('Điểm Apgar phút thứ 5');
            $table->enum('gioi_tinh_tre', ['Nam', 'Nữ', 'Chưa xác định'])->nullable();

            // Theo dõi hậu sản (postpartum visits)
            $table->date('ngay_kham')->comment('Ngày khám hậu sản');
            $table->integer('ngay_sau_sinh')->comment('Số ngày sau sinh');

            // Khám mẹ
            $table->decimal('can_nang_me', 5, 2)->nullable()->comment('Cân nặng mẹ (kg)');
            $table->decimal('huyet_ap_tam_thu', 5, 2)->nullable()->comment('Huyết áp tâm thu (mmHg)');
            $table->decimal('huyet_ap_tam_truong', 5, 2)->nullable()->comment('Huyết áp tâm trương (mmHg)');
            $table->decimal('nhiet_do', 4, 2)->nullable()->comment('Nhiệt độ (°C)');

            // Tình trạng mẹ
            $table->text('tinh_trang_tu_cung')->nullable()->comment('Tình trạng tử cung (co hồi, độ cao đáy tử cung...)');
            $table->text('tinh_trang_vu')->nullable()->comment('Tình trạng vú (sữa, nứt núm vú, viêm vú...)');
            $table->enum('lochia', ['Bình thường', 'Nhiều', 'Ít', 'Bất thường'])->nullable()->comment('Dịch ác lộ');
            $table->text('tinh_trang_thuong_tich')->nullable()->comment('Tình trạng vết mổ/rạch tầng sinh môn');

            // Tình trạng chung
            $table->text('trieu_chung')->nullable()->comment('Triệu chứng (đau, chảy máu, sốt...)');
            $table->text('tam_trang')->nullable()->comment('Tâm trạng (trầm cảm sau sinh...)');
            $table->enum('cho_con_bu', ['Hoàn toàn', 'Một phần', 'Không'])->default('Hoàn toàn');

            // Đánh giá & tư vấn
            $table->text('danh_gia')->nullable()->comment('Đánh giá tổng quan');
            $table->text('tu_van')->nullable()->comment('Tư vấn chăm sóc, dinh dưỡng, kế hoạch hóa gia đình');
            $table->text('chi_dinh')->nullable()->comment('Chỉ định thuốc/điều trị');
            $table->date('hen_kham_lai')->nullable()->comment('Hẹn khám lại');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('theo_doi_thai_ky_id');
            $table->index('ngay_kham');
            $table->index('ngay_sau_sinh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theo_doi_hau_san');
    }
};
