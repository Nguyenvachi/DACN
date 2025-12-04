<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('locked_at')->nullable()->after('remember_token');
            $table->timestamp('locked_until')->nullable()->after('locked_at');
            $table->boolean('must_change_password')->default(false)->after('locked_until');
            $table->timestamp('last_login_at')->nullable()->after('must_change_password');
            $table->integer('login_attempts')->default(0)->after('last_login_at');
            $table->string('last_login_ip', 45)->nullable()->after('login_attempts');
            
            $table->index('locked_at');
            $table->index('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['locked_at']);
            $table->dropIndex(['last_login_at']);
            
            $table->dropColumn([
                'locked_at',
                'locked_until',
                'must_change_password',
                'last_login_at',
                'login_attempts',
                'last_login_ip',
            ]);
        });
    }
};
