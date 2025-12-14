<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate dữ liệu từ cột loai sang loai_phong_id
        $mapping = [
            'phong_kham' => 'Phòng Khám Thai',
            'phong_xet_nghiem' => 'Phòng Xét Nghiệm',
            'phong_sieu_am' => 'Phòng Siêu Âm',
            'phong_kham_tong_quat' => 'Phòng Khám Tổng Quát',
        ];

        foreach ($mapping as $oldType => $newTypeName) {
            $loaiPhong = DB::table('loai_phongs')->where('ten', $newTypeName)->first();
            if ($loaiPhong) {
                DB::table('phongs')
                    ->where('loai', $oldType)
                    ->update(['loai_phong_id' => $loaiPhong->id]);
            }
        }

        // Xóa cột loai cũ
        Schema::table('phongs', function (Blueprint $table) {
            $table->dropColumn('loai');
        });
    }

    public function down(): void
    {
        // Thêm lại cột loai
        Schema::table('phongs', function (Blueprint $table) {
            $table->string('loai')->nullable()->after('ten');
        });

        // Restore dữ liệu
        $mapping = [
            'Phòng Khám Thai' => 'phong_kham',
            'Phòng Xét Nghiệm' => 'phong_xet_nghiem',
            'Phòng Siêu Âm' => 'phong_sieu_am',
            'Phòng Khám Tổng Quát' => 'phong_kham_tong_quat',
        ];

        foreach ($mapping as $typeName => $oldType) {
            $loaiPhong = DB::table('loai_phongs')->where('ten', $typeName)->first();
            if ($loaiPhong) {
                DB::table('phongs')
                    ->where('loai_phong_id', $loaiPhong->id)
                    ->update(['loai' => $oldType]);
            }
        }
    }
};
