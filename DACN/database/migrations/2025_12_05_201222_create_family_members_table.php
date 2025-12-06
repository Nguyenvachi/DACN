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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ho_ten');
            $table->enum('quan_he', ['vo', 'chong', 'con', 'cha', 'me', 'anh', 'chi', 'em', 'ong', 'ba', 'chau', 'khac']);
            $table->date('ngay_sinh');
            $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác']);
            $table->string('so_dien_thoai');
            $table->string('email')->nullable();
            $table->text('dia_chi')->nullable();
            $table->string('nhom_mau')->nullable();
            $table->decimal('chieu_cao', 5, 2)->nullable()->comment('cm');
            $table->decimal('can_nang', 5, 2)->nullable()->comment('kg');
            $table->text('tien_su_benh')->nullable();
            $table->string('bhyt_ma_so')->nullable();
            $table->date('bhyt_ngay_het_han')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_members');
    }
};
