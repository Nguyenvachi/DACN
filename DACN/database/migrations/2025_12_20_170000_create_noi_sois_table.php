<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('noi_sois')) {
            Schema::create('noi_sois', function (Blueprint $table) {
                $table->id();

                // Relationships
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Bệnh nhân
                $table->foreignId('benh_an_id')->constrained('benh_ans')->cascadeOnDelete(); // Bệnh án
                $table->foreignId('bac_si_chi_dinh_id')->nullable()->constrained('bac_sis')->nullOnDelete(); // Bác sĩ chỉ định
                $table->foreignId('bac_si_noi_soi_id')->nullable()->constrained('bac_sis')->nullOnDelete(); // Bác sĩ/KTV thực hiện (nếu có)
                $table->foreignId('phong_id')->nullable()->constrained('phongs')->nullOnDelete(); // Phòng nội soi (nếu có)

                // Thông tin chỉ định
                $table->string('loai');
                $table->text('mo_ta')->nullable();
                $table->decimal('gia', 12, 2)->default(0);

                // File kết quả (private)
                $table->string('file_path')->nullable();
                $table->string('disk')->nullable(); // mặc định sẽ fallback về benh_an_private

                // Kết quả & nhận xét
                $table->text('ket_qua')->nullable();
                $table->text('nhan_xet')->nullable();

                // pending|processing|completed
                $table->string('trang_thai', 50)->default('pending');

                // Audit timestamps
                $table->timestamp('ngay_chi_dinh')->useCurrent();
                $table->timestamp('ngay_thuc_hien')->nullable();
                $table->timestamp('ngay_hoan_thanh')->nullable();

                $table->timestamps();

                $table->index(['user_id', 'trang_thai']);
                $table->index(['benh_an_id', 'trang_thai']);
                $table->index('bac_si_chi_dinh_id');
                $table->index('bac_si_noi_soi_id');
                $table->index('phong_id');
                $table->index('ngay_chi_dinh');
            });
        }
    }

    public function down(): void
    {
        // Không rollback theo rule dự án: chỉ bổ sung
    }
};
