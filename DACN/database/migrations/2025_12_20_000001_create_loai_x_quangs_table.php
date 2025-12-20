<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('loai_x_quangs')) {
            Schema::create('loai_x_quangs', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('ma')->nullable()->unique();
                $table->text('mo_ta')->nullable();
                $table->unsignedInteger('thoi_gian_uoc_tinh')->default(15);
                $table->decimal('gia', 12, 2)->default(0);
                $table->foreignId('phong_id')->nullable()->constrained('phongs')->nullOnDelete();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Không rollback theo rule dự án: chỉ bổ sung code
    }
};
