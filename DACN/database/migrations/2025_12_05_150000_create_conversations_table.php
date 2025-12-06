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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benh_nhan_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bac_si_id')->constrained('bac_sis')->onDelete('cascade');
            $table->foreignId('lich_hen_id')->nullable()->constrained('lich_hens')->onDelete('set null');
            $table->string('tieu_de')->nullable();
            $table->enum('trang_thai', ['Đang hoạt động', 'Đã đóng', 'Bị khóa'])->default('Đang hoạt động');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['benh_nhan_id', 'bac_si_id']);
            $table->index('trang_thai');
            $table->index('last_message_at');

            // Unique constraint: 1 cuộc hội thoại cho mỗi cặp bệnh nhân-bác sĩ
            $table->unique(['benh_nhan_id', 'bac_si_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
