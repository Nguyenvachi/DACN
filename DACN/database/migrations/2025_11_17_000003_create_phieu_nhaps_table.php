<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phieu_nhaps', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phieu')->unique();
            $table->date('ngay_nhap');
            $table->foreignId('nha_cung_cap_id')->nullable()->constrained('nha_cung_caps');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->decimal('tong_tien', 14, 2)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });

        Schema::create('phieu_nhap_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phieu_nhap_id')->constrained('phieu_nhaps')->onDelete('cascade');
            $table->foreignId('thuoc_id')->constrained('thuocs');
            $table->string('ma_lo')->nullable();
            $table->date('han_su_dung')->nullable();
            $table->integer('so_luong');
            $table->decimal('don_gia', 12, 2);
            $table->decimal('thanh_tien', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phieu_nhap_items');
        Schema::dropIfExists('phieu_nhaps');
    }
};
