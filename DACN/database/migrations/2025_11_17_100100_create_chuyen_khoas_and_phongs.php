<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('chuyen_khoas')) {
            Schema::create('chuyen_khoas', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('slug')->nullable();
                $table->text('mo_ta')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('phongs')) {
            Schema::create('phongs', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('loai')->nullable(); // phong_kham, phong_xet_nghiem, ...
                $table->text('mo_ta')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('bac_si_chuyen_khoa')) {
            Schema::create('bac_si_chuyen_khoa', function (Blueprint $table) {
                $table->unsignedBigInteger('bac_si_id');
                $table->unsignedBigInteger('chuyen_khoa_id');
                $table->primary(['bac_si_id','chuyen_khoa_id']);
                $table->foreign('bac_si_id')->references('id')->on('bac_sis')->onDelete('cascade');
                $table->foreign('chuyen_khoa_id')->references('id')->on('chuyen_khoas')->onDelete('cascade');
            });
        }
        if (!Schema::hasTable('bac_si_phong')) {
            Schema::create('bac_si_phong', function (Blueprint $table) {
                $table->unsignedBigInteger('bac_si_id');
                $table->unsignedBigInteger('phong_id');
                $table->primary(['bac_si_id','phong_id']);
                $table->foreign('bac_si_id')->references('id')->on('bac_sis')->onDelete('cascade');
                $table->foreign('phong_id')->references('id')->on('phongs')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bac_si_phong');
        Schema::dropIfExists('bac_si_chuyen_khoa');
        Schema::dropIfExists('phongs');
        Schema::dropIfExists('chuyen_khoas');
    }
};
