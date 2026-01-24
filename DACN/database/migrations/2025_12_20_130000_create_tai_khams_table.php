<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tai_khams')) {
            return;
        }

        Schema::create('tai_khams', function (Blueprint $table) {
            $table->id();

            $table->foreignId('benh_an_id')->constrained('benh_ans')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->nullOnDelete();
            $table->foreignId('lich_hen_id')->nullable()->constrained('lich_hens')->nullOnDelete();

            $table->date('ngay_tai_kham')->nullable();
            $table->time('thoi_gian_tai_kham')->nullable();
            $table->unsignedTinyInteger('so_ngay_du_kien')->nullable();

            $table->text('ly_do')->nullable();
            $table->text('ghi_chu')->nullable();

            // Consistent with existing app values (see LichHen::STATUS_*_VN)
            $table->string('trang_thai')->default('Chờ xác nhận');
            $table->string('created_by_role')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['benh_an_id', 'trang_thai']);
            $table->index(['user_id', 'trang_thai']);
            $table->index(['bac_si_id', 'trang_thai']);
        });
    }

    public function down(): void
    {
        // Intentionally keep non-destructive down() to respect "no fresh DB" workflow.
        // Schema::dropIfExists('tai_khams');
    }
};
