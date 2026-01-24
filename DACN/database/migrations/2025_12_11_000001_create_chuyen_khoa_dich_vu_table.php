<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chuyen_khoa_dich_vu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chuyen_khoa_id');
            $table->unsignedBigInteger('dich_vu_id');
            $table->timestamps();

            $table->foreign('chuyen_khoa_id')->references('id')->on('chuyen_khoas')->onDelete('cascade');
            $table->foreign('dich_vu_id')->references('id')->on('dich_vus')->onDelete('cascade');
            $table->unique(['chuyen_khoa_id','dich_vu_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chuyen_khoa_dich_vu');
    }
};
