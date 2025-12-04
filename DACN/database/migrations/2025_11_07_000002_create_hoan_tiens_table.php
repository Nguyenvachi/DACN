<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hoan_tiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hoa_don_id')->constrained('hoa_dons')->cascadeOnDelete();
            $table->decimal('so_tien', 12, 2);
            $table->string('ly_do')->nullable();
            $table->string('trang_thai')->default('Đang xử lý');
            $table->string('provider')->nullable();
            $table->string('provider_ref')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoan_tiens');
    }
};
