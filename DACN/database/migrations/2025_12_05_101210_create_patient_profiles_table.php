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
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Thông tin y tế
            $table->string('nhom_mau')->nullable()->comment('A, B, AB, O, A+, A-, ...');
            $table->decimal('chieu_cao', 5, 2)->nullable()->comment('cm');
            $table->decimal('can_nang', 5, 2)->nullable()->comment('kg');
            $table->text('allergies')->nullable()->comment('Dị ứng (JSON array)');
            $table->text('tien_su_benh')->nullable()->comment('Tiền sử bệnh lý');
            $table->text('thuoc_dang_dung')->nullable()->comment('Thuốc đang sử dụng');
            $table->text('benh_man_tinh')->nullable()->comment('Bệnh mạn tính');

            // Thông tin liên hệ khẩn cấp
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();

            // Ảnh đại diện
            $table->string('avatar')->nullable();

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
        Schema::dropIfExists('patient_profiles');
    }
};
