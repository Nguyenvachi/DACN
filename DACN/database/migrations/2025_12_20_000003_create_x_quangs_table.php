<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('x_quangs')) {
            Schema::create('x_quangs', function (Blueprint $table) {
                $table->id();

                $table->foreignId('benh_an_id')->constrained('benh_ans')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('bac_si_id')->nullable()->constrained('bac_sis')->nullOnDelete();

                $table->foreignId('loai_x_quang_id')->nullable()->constrained('loai_x_quangs')->nullOnDelete();

                // Fallback text (để không phá luồng nếu không dùng master)
                $table->string('loai');
                $table->text('mo_ta')->nullable();
                $table->decimal('gia', 12, 2)->default(0);

                $table->dateTime('ngay_chi_dinh')->nullable();

                $table->string('file_path')->nullable();
                $table->string('disk')->nullable();

                // pending|processing|completed
                $table->string('trang_thai')->default('pending');
                $table->text('nhan_xet')->nullable();
                $table->text('ket_qua')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Không rollback theo rule dự án: chỉ bổ sung code
    }
};
