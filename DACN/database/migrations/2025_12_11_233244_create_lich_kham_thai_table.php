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
        Schema::create('lich_kham_thai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theo_doi_thai_ky_id')->constrained('theo_doi_thai_ky')->onDelete('cascade');
            $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->onDelete('set null');

            // Thông tin lịch khám
            $table->date('ngay_kham')->comment('Ngày khám');
            $table->integer('tuan_thai')->comment('Tuần thai tại thời điểm khám');
            $table->integer('ngay_thai')->nullable()->comment('Ngày (0-6)');

            // Chỉ số sinh hiệu
            $table->decimal('can_nang', 5, 2)->nullable()->comment('Cân nặng mẹ (kg)');
            $table->decimal('huyet_ap_tam_thu', 5, 2)->nullable()->comment('Huyết áp tâm thu (mmHg)');
            $table->decimal('huyet_ap_tam_truong', 5, 2)->nullable()->comment('Huyết áp tâm trương (mmHg)');
            $table->decimal('nhiet_do', 4, 2)->nullable()->comment('Nhiệt độ (°C)');
            $table->integer('nhip_tim_me')->nullable()->comment('Nhịp tim mẹ (bpm)');

            // Khám thai
            $table->decimal('chieu_cao_tu_cung', 5, 2)->nullable()->comment('Chiều cao đáy tử cung (cm)');
            $table->decimal('vong_bung', 5, 2)->nullable()->comment('Vòng bụng (cm)');
            $table->integer('nhip_tim_thai')->nullable()->comment('Nhịp tim thai (bpm)');
            $table->string('vi_tri_thai', 100)->nullable()->comment('Vị trí thai nhi');

            // Triệu chứng & khám
            $table->text('trieu_chung')->nullable()->comment('Triệu chứng bất thường');
            $table->text('kham_lam_sang')->nullable()->comment('Kết quả khám lâm sàng');
            $table->text('chi_dinh')->nullable()->comment('Chỉ định (XN, SA, thuốc...)');

            // Đánh giá
            $table->text('danh_gia')->nullable()->comment('Đánh giá tổng quan');
            $table->text('tu_van')->nullable()->comment('Tư vấn cho mẹ bầu');
            $table->date('hen_kham_lai')->nullable()->comment('Hẹn khám lại');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('theo_doi_thai_ky_id');
            $table->index('ngay_kham');
            $table->index('tuan_thai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lich_kham_thai');
    }
};
