<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('loai_sieu_ams')) {
            Schema::create('loai_sieu_ams', function (Blueprint $table) {
                $table->id();
                $table->string('ten')->unique(); // Tên loại siêu âm (duy nhất)
                $table->text('mo_ta')->nullable(); // Mô tả chi tiết
                $table->decimal('gia_mac_dinh', 12, 2)->default(0); // Giá mặc định
                $table->integer('thoi_gian_uoc_tinh')->default(30); // Thời gian ước tính (phút)
                $table->foreignId('phong_id')->nullable()->constrained('phongs')->nullOnDelete(); // Phòng thực hiện
                $table->boolean('is_active')->default(true); // Trạng thái hoạt động
                $table->timestamps();

                $table->index('is_active');
                $table->index('ten');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_sieu_ams');
    }
};
