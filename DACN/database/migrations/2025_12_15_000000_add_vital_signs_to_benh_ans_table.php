<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('benh_ans', function (Blueprint $table) {
            // Vital Signs - Chỉ số sinh tồn
            $table->string('huyet_ap', 20)->nullable()->comment('Huyết áp (VD: 120/80)')->after('tieu_de');
            $table->integer('nhip_tim')->nullable()->comment('Nhịp tim (lần/phút)')->after('huyet_ap');
            $table->decimal('nhiet_do', 4, 1)->nullable()->comment('Nhiệt độ (°C)')->after('nhip_tim');
            $table->integer('nhip_tho')->nullable()->comment('Nhịp thở (lần/phút)')->after('nhiet_do');
            $table->decimal('can_nang', 5, 2)->nullable()->comment('Cân nặng (kg)')->after('nhip_tho');
            $table->decimal('chieu_cao', 5, 2)->nullable()->comment('Chiều cao (cm)')->after('can_nang');
            $table->decimal('bmi', 4, 1)->nullable()->comment('BMI')->after('chieu_cao');
            $table->integer('spo2')->nullable()->comment('SpO2 (%)')->after('bmi');
        });
    }

    public function down(): void
    {
        Schema::table('benh_ans', function (Blueprint $table) {
            $table->dropColumn([
                'huyet_ap',
                'nhip_tim',
                'nhiet_do',
                'nhip_tho',
                'can_nang',
                'chieu_cao',
                'bmi',
                'spo2'
            ]);
        });
    }
};
