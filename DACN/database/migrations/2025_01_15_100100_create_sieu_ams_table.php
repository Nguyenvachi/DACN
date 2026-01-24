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
        if (!Schema::hasTable('sieu_ams')) {
            Schema::create('sieu_ams', function (Blueprint $table) {
                $table->id();

                // Foreign keys - Relationships
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Bệnh nhân
                $table->foreignId('benh_an_id')->constrained('benh_ans')->cascadeOnDelete(); // Bệnh án
                $table->foreignId('bac_si_chi_dinh_id')->constrained('bac_sis')->cascadeOnDelete(); // Bác sĩ chỉ định
                $table->foreignId('loai_sieu_am_id')->nullable()->constrained('loai_sieu_ams')->nullOnDelete(); // Loại siêu âm
                $table->foreignId('bac_si_sieu_am_id')->nullable()->constrained('bac_sis')->nullOnDelete(); // Bác sĩ thực hiện
                $table->foreignId('phong_id')->nullable()->constrained('phongs')->nullOnDelete(); // Phòng siêu âm

                // Thông tin siêu âm
                $table->string('loai'); // Tên loại siêu âm (text để không phụ thuộc vào FK)
                $table->text('mo_ta')->nullable(); // Mô tả yêu cầu từ bác sĩ
                $table->decimal('gia', 12, 2)->default(0); // Giá thực tế

                // File đính kèm
                $table->string('file_path')->nullable(); // Đường dẫn file kết quả
                $table->string('disk')->default('sieu_am_private'); // Disk storage

                // Kết quả & nhận xét
                $table->text('ket_qua')->nullable(); // Kết quả từ kỹ thuật viên
                $table->text('nhan_xet')->nullable(); // Nhận xét từ bác sĩ

                // Trạng thái workflow
                $table->string('trang_thai', 50)->default('pending'); // pending, processing, completed

                // Audit timestamps
                $table->timestamp('ngay_chi_dinh')->useCurrent(); // Ngày bác sĩ chỉ định
                $table->timestamp('ngay_thuc_hien')->nullable(); // Ngày bắt đầu thực hiện
                $table->timestamp('ngay_hoan_thanh')->nullable(); // Ngày hoàn thành

                $table->timestamps();

                // Indexes for query performance
                $table->index(['user_id', 'trang_thai']);
                $table->index(['benh_an_id', 'trang_thai']);
                $table->index('bac_si_chi_dinh_id');
                $table->index('bac_si_sieu_am_id');
                $table->index('loai_sieu_am_id');
                $table->index('trang_thai');
                $table->index('ngay_chi_dinh');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sieu_ams');
    }
};
