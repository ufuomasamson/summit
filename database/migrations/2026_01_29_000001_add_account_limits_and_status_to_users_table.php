<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountLimitsAndStatusToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Account status
            $table->enum('account_status', [
                'active',
                'dormant',
                'daily_limit_exceeded',
                'weekly_limit_exceeded',
                'monthly_limit_exceeded',
                'transaction_declined'
            ])->default('active')->after('status');
            
            // Transfer limits
            $table->decimal('daily_limit', 15, 2)->nullable()->after('account_status');
            $table->decimal('weekly_limit', 15, 2)->nullable()->after('daily_limit');
            $table->decimal('monthly_limit', 15, 2)->nullable()->after('weekly_limit');
            
            // Enable/disable toggles for limits
            $table->boolean('daily_limit_enabled')->default(false)->after('monthly_limit');
            $table->boolean('weekly_limit_enabled')->default(false)->after('daily_limit_enabled');
            $table->boolean('monthly_limit_enabled')->default(false)->after('weekly_limit_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_status',
                'daily_limit',
                'weekly_limit',
                'monthly_limit',
                'daily_limit_enabled',
                'weekly_limit_enabled',
                'monthly_limit_enabled'
            ]);
        });
    }
}
