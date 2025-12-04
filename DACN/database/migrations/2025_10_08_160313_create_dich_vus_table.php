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
    public function up(): void
    {
        Schema::create('dich_vus', function (Blueprint $table) {
            $table->id();
            $table->string('ten_dich_vu'); // Ví dụ: "Siêu âm 4D", "Khám thai định kỳ"
            $table->text('mo_ta')->nullable(); // Mô tả chi tiết về dịch vụ
            $table->decimal('gia', 10, 2); // Giá dịch vụ, ví dụ: 500000.00
            $table->integer('thoi_gian_uoc_tinh'); // Thời gian ước tính (phút), ví dụ: 30
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
        Schema::dropIfExists('dich_vus');
    }
};
