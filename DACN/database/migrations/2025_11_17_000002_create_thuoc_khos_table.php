<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('thuoc_khos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thuoc_id')->constrained('thuocs');
            $table->string('ma_lo')->nullable();
            $table->date('han_su_dung')->nullable();
            $table->integer('so_luong')->default(0);
            $table->decimal('gia_nhap', 12, 2)->default(0);
            $table->decimal('gia_xuat', 12, 2)->default(0);
            $table->foreignId('nha_cung_cap_id')->nullable()->constrained('nha_cung_caps');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thuoc_khos');
    }
};
