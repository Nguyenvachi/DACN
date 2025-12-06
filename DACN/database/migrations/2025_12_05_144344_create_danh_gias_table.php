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
        Schema::create('danh_gias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Bệnh nhân đánh giá
            $table->foreignId('bac_si_id')->constrained('bac_sis')->onDelete('cascade'); // Bác sĩ được đánh giá
            $table->foreignId('lich_hen_id')->nullable()->constrained('lich_hens')->onDelete('set null'); // Lịch hẹn liên quan
            $table->unsignedTinyInteger('rating'); // Rating từ 1-5 sao
            $table->text('noi_dung')->nullable(); // Nội dung đánh giá
            $table->enum('trang_thai', ['pending', 'approved', 'rejected'])->default('approved'); // Trạng thái kiểm duyệt
            $table->timestamps();

            // Unique constraint: Mỗi user chỉ đánh giá 1 lần cho mỗi lịch hẹn
            $table->unique(['user_id', 'lich_hen_id']);

            // Index để tìm kiếm nhanh
            $table->index(['bac_si_id', 'trang_thai']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('danh_gias');
    }
};
