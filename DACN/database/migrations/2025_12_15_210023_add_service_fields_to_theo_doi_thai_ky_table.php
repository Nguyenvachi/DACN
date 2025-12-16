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
        Schema::table('theo_doi_thai_ky', function (Blueprint $table) {
            // Liên kết với bệnh án
            $table->foreignId('benh_an_id')->nullable()->after('bac_si_id')->constrained('benh_ans')->onDelete('cascade');

            // Thông tin dịch vụ
            $table->decimal('gia_tien', 10, 2)->default(0)->after('ghi_chu')->comment('Giá gói theo dõi thai kỳ');
            $table->enum('trang_thai_thanh_toan', ['Chưa thanh toán', 'Đã thanh toán', 'Đã hoàn tiền'])->default('Chưa thanh toán')->after('gia_tien');
            $table->date('ngay_bat_dau')->nullable()->after('trang_thai_thanh_toan')->comment('Ngày bắt đầu gói dịch vụ');
            $table->text('goi_dich_vu')->nullable()->after('ngay_bat_dau')->comment('Mô tả gói dịch vụ theo dõi');

            // Index
            $table->index('benh_an_id');
            $table->index('trang_thai_thanh_toan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theo_doi_thai_ky', function (Blueprint $table) {
            $table->dropForeign(['benh_an_id']);
            $table->dropIndex(['benh_an_id']);
            $table->dropIndex(['trang_thai_thanh_toan']);
            $table->dropColumn([
                'benh_an_id',
                'gia_tien',
                'trang_thai_thanh_toan',
                'ngay_bat_dau',
                'goi_dich_vu'
            ]);
        });
    }
};
