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
        Schema::table('xet_nghiems', function (Blueprint $table) {
            // Thêm các cột cho sản phụ khoa
            $table->string('loai_xet_nghiem')->nullable()->after('bac_si_id'); // 'NIPT', 'Triple test', 'Máu thai nhi', 'Hormone'...
            $table->date('ngay_chi_dinh')->nullable()->after('loai_xet_nghiem');
            $table->string('trang_thai_xn')->nullable()->after('ngay_chi_dinh'); // 'Chờ lấy mẫu', 'Đã lấy mẫu', 'Đang xét nghiệm', 'Có kết quả', 'Đã hủy'
            $table->text('ket_qua')->nullable()->after('mo_ta'); // Kết quả xét nghiệm
            $table->date('ngay_tra_ket_qua')->nullable()->after('ket_qua');
            $table->decimal('gia_tien', 10, 2)->nullable()->after('ngay_tra_ket_qua');
            $table->enum('trang_thai_thanh_toan', ['Chưa thanh toán', 'Đã thanh toán'])->default('Chưa thanh toán')->after('gia_tien');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xet_nghiems', function (Blueprint $table) {
            $table->dropColumn([
                'loai_xet_nghiem',
                'gia_tien',
                'trang_thai_thanh_toan',
                'ngay_chi_dinh',
                'ngay_tra_ket_qua',
                'trang_thai_xn'
            ]);
        });
    }
};
