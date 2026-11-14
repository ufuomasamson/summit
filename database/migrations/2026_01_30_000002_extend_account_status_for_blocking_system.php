<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ExtendAccountStatusForBlockingSystem extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN account_status ENUM(
            'active',
            'dormant',
            'daily_limit_exceeded',
            'weekly_limit_exceeded',
            'monthly_limit_exceeded',
            'transaction_declined',
            'blocked',
            'permanently_banned'
        ) DEFAULT 'active'");

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'block_reason')) {
                $table->text('block_reason')->nullable()->after('account_status');
            }
            if (!Schema::hasColumn('users', 'blocked_at')) {
                $table->timestamp('blocked_at')->nullable()->after('block_reason');
            }
            if (!Schema::hasColumn('users', 'blocked_by_admin_id')) {
                $table->unsignedBigInteger('blocked_by_admin_id')->nullable()->after('blocked_at');
            }
            if (!Schema::hasColumn('users', 'unblock_at')) {
                $table->timestamp('unblock_at')->nullable()->after('blocked_by_admin_id');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'block_reason')) {
                $table->dropColumn('block_reason');
            }
            if (Schema::hasColumn('users', 'blocked_at')) {
                $table->dropColumn('blocked_at');
            }
            if (Schema::hasColumn('users', 'blocked_by_admin_id')) {
                $table->dropColumn('blocked_by_admin_id');
            }
            if (Schema::hasColumn('users', 'unblock_at')) {
                $table->dropColumn('unblock_at');
            }
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN account_status ENUM(
            'active', 'dormant', 'daily_limit_exceeded', 'weekly_limit_exceeded', 
            'monthly_limit_exceeded', 'transaction_declined'
        ) DEFAULT 'active'");
    }
}
