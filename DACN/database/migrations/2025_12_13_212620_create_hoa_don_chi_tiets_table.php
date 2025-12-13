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
        if (!Schema::hasTable('hoa_don_chi_tiets')) {
            Schema::create('hoa_don_chi_tiets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hoa_don_id')->constrained('hoa_dons')->cascadeOnDelete();

                // Loại dịch vụ: thuoc, noi_soi, x_quang, xet_nghiem, dich_vu_kham, thu_thuat
                $table->enum('loai_dich_vu', ['thuoc', 'noi_soi', 'x_quang', 'xet_nghiem', 'dich_vu_kham', 'thu_thuat']);

                // ID tham chiếu đến bảng tương ứng (polymorphic)
                $table->unsignedBigInteger('dich_vu_id')->nullable()->comment('ID của thuốc, nội soi, x-quang, v.v.');

                // Thông tin dịch vụ (lưu trữ snapshot tại thời điểm tạo hóa đơn)
                $table->string('ten_dich_vu');
                $table->text('mo_ta')->nullable();
                $table->integer('so_luong')->default(1);
                $table->decimal('don_gia', 12, 2)->default(0);
                $table->decimal('thanh_tien', 12, 2)->default(0); // = so_luong * don_gia

                $table->timestamps();

                $table->index(['hoa_don_id', 'loai_dich_vu']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hoa_don_chi_tiets');
    }
};
