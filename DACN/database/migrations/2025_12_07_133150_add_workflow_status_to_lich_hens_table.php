<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lich_hens', function (Blueprint $table) {
            // Thêm cột checked_in_at để lưu thời gian bệnh nhân check-in
            $table->timestamp('checked_in_at')->nullable()->after('trang_thai');

            // Thêm cột completed_at để lưu thời gian hoàn thành khám
            $table->timestamp('completed_at')->nullable()->after('checked_in_at');
        });

        // Cập nhật dữ liệu cũ: chuyển các trạng thái tiếng Việt sang tiếng Anh chuẩn
        DB::table('lich_hens')->where('trang_thai', 'Chờ xác nhận')->update(['trang_thai' => 'pending']);
        DB::table('lich_hens')->where('trang_thai', 'Đã xác nhận')->update(['trang_thai' => 'confirmed']);
        DB::table('lich_hens')->where('trang_thai', 'Đã hủy')->update(['trang_thai' => 'cancelled']);
        DB::table('lich_hens')->where('trang_thai', 'Hoàn thành')->update(['trang_thai' => 'completed']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback: chuyển lại sang tiếng Việt
        DB::table('lich_hens')->where('trang_thai', 'pending')->update(['trang_thai' => 'Chờ xác nhận']);
        DB::table('lich_hens')->where('trang_thai', 'confirmed')->update(['trang_thai' => 'Đã xác nhận']);
        DB::table('lich_hens')->where('trang_thai', 'cancelled')->update(['trang_thai' => 'Đã hủy']);
        DB::table('lich_hens')->where('trang_thai', 'completed')->update(['trang_thai' => 'Hoàn thành']);
        DB::table('lich_hens')->where('trang_thai', 'checked_in')->update(['trang_thai' => 'Đã xác nhận']);
        DB::table('lich_hens')->where('trang_thai', 'in_progress')->update(['trang_thai' => 'Đã xác nhận']);

        Schema::table('lich_hens', function (Blueprint $table) {
            $table->dropColumn(['checked_in_at', 'completed_at']);
        });
    }
};
