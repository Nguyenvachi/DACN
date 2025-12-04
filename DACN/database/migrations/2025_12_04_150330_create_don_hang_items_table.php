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
        Schema::create('don_hang_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('don_hang_id')->constrained('don_hangs')->onDelete('cascade');
            $table->foreignId('thuoc_id')->constrained('thuocs');
            $table->integer('so_luong')->default(1);
            $table->decimal('don_gia', 15, 2)->comment('Giá bán tại thời điểm đặt');
            $table->decimal('thanh_tien', 15, 2)->comment('so_luong * don_gia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('don_hang_items');
    }
};
