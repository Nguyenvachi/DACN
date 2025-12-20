<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Master: Loại xét nghiệm (dịch vụ xét nghiệm)
        if (!Schema::hasTable('loai_xet_nghiems')) {
            Schema::create('loai_xet_nghiems', function (Blueprint $table) {
                $table->id();
                $table->string('ten')->index();
                $table->string('ma')->nullable()->unique();
                $table->text('mo_ta')->nullable();
                $table->unsignedInteger('thoi_gian_uoc_tinh')->default(30);
                $table->decimal('gia', 12, 2)->default(0);
                $table->foreignId('phong_id')->nullable()->constrained('phongs')->nullOnDelete();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        // Pivot: Chuyên khoa <-> Loại xét nghiệm
        if (!Schema::hasTable('chuyen_khoa_loai_xet_nghiem')) {
            Schema::create('chuyen_khoa_loai_xet_nghiem', function (Blueprint $table) {
                $table->foreignId('chuyen_khoa_id')->constrained('chuyen_khoas')->cascadeOnDelete();
                $table->foreignId('loai_xet_nghiem_id')->constrained('loai_xet_nghiems')->cascadeOnDelete();
                $table->primary(['chuyen_khoa_id', 'loai_xet_nghiem_id'], 'ck_lxn_pk');
            });
        }

        // Add FK vào xet_nghiems (tương thích ngược, nullable)
        if (Schema::hasTable('xet_nghiems')) {
            Schema::table('xet_nghiems', function (Blueprint $table) {
                if (!Schema::hasColumn('xet_nghiems', 'loai_xet_nghiem_id')) {
                    $table->foreignId('loai_xet_nghiem_id')
                        ->nullable()
                        ->after('bac_si_id')
                        ->constrained('loai_xet_nghiems')
                        ->nullOnDelete();
                }

                if (!Schema::hasColumn('xet_nghiems', 'gia')) {
                    $table->decimal('gia', 12, 2)->default(0)->after('mo_ta');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('xet_nghiems')) {
            Schema::table('xet_nghiems', function (Blueprint $table) {
                if (Schema::hasColumn('xet_nghiems', 'loai_xet_nghiem_id')) {
                    try {
                        $table->dropForeign(['loai_xet_nghiem_id']);
                    } catch (Throwable $e) {
                        // ignore
                    }
                    $table->dropColumn('loai_xet_nghiem_id');
                }

                if (Schema::hasColumn('xet_nghiems', 'gia')) {
                    $table->dropColumn('gia');
                }
            });
        }

        Schema::dropIfExists('chuyen_khoa_loai_xet_nghiem');
        Schema::dropIfExists('loai_xet_nghiems');
    }
};
