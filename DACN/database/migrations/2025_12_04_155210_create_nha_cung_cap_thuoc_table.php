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
        Schema::create('nha_cung_cap_thuoc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nha_cung_cap_id')->constrained('nha_cung_caps')->onDelete('cascade');
            $table->foreignId('thuoc_id')->constrained('thuocs')->onDelete('cascade');
            $table->decimal('gia_nhap_mac_dinh', 15, 2)->nullable()->comment('Giá nhập mặc định từ NCC này');
            $table->timestamps();

            // Unique: 1 NCC chỉ cung cấp 1 thuốc 1 lần (không trùng)
            $table->unique(['nha_cung_cap_id', 'thuoc_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nha_cung_cap_thuoc');
    }
};
