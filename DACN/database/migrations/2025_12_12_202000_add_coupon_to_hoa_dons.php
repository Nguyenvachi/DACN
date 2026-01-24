<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            if (!Schema::hasColumn('hoa_dons', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete()->after('tong_tien');
            }
            if (!Schema::hasColumn('hoa_dons', 'giam_gia')) {
                $table->decimal('giam_gia', 12, 2)->default(0)->after('coupon_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            if (Schema::hasColumn('hoa_dons', 'giam_gia')) $table->dropColumn('giam_gia');
            if (Schema::hasColumn('hoa_dons', 'coupon_id')) {
                $table->dropForeign(['coupon_id']);
                $table->dropColumn('coupon_id');
            }
        });
    }
};
