<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('ca_lam_viec_nhan_viens')) {
            Schema::create('ca_lam_viec_nhan_viens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('nhan_vien_id')->constrained('nhan_viens')->cascadeOnDelete();
                $table->date('ngay');
                $table->time('bat_dau');
                $table->time('ket_thuc');
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void {}
};