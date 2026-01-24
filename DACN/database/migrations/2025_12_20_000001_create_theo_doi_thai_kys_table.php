<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('theo_doi_thai_kys')) {
            Schema::create('theo_doi_thai_kys', function (Blueprint $table) {
                $table->id();

                // Gắn với hồ sơ gốc
                $table->foreignId('benh_an_id')->constrained('benh_ans')->cascadeOnDelete();

                // Ai là bệnh nhân (để query nhanh, vẫn kiểm tra ownership qua benh_an)
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

                // Bác sĩ phụ trách (optional, có thể suy ra từ benh_an)
                $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->nullOnDelete();

                // Dữ liệu theo dõi
                $table->date('ngay_theo_doi')->nullable();
                $table->unsignedTinyInteger('tuan_thai')->nullable();
                $table->decimal('can_nang_kg', 5, 2)->nullable();
                $table->unsignedSmallInteger('huyet_ap_tam_thu')->nullable();
                $table->unsignedSmallInteger('huyet_ap_tam_truong')->nullable();
                $table->unsignedSmallInteger('nhip_tim_thai')->nullable();
                $table->decimal('duong_huyet', 5, 2)->nullable();
                $table->decimal('huyet_sac_to', 5, 2)->nullable();

                $table->text('trieu_chung')->nullable();
                $table->text('ghi_chu')->nullable();
                $table->text('nhan_xet')->nullable();

                // File đính kèm (ảnh/phiếu đo)
                $table->string('file_path')->nullable();
                $table->string('disk')->nullable();

                // Trạng thái workflow
                $table->string('trang_thai')->default('submitted');

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('theo_doi_thai_kys');
    }
};
