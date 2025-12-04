<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phieu_xuats', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phieu')->unique();
            $table->date('ngay_xuat');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('doi_tuong')->nullable();
            $table->decimal('tong_tien', 14, 2)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });

        Schema::create('phieu_xuat_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phieu_xuat_id')->constrained('phieu_xuats')->onDelete('cascade');
            $table->foreignId('thuoc_id')->constrained('thuocs');
            $table->integer('so_luong');
            $table->decimal('don_gia', 12, 2);
            $table->decimal('thanh_tien', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phieu_xuat_items');
        Schema::dropIfExists('phieu_xuats');
    }
};
