<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerUserTransferCodeSettingsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add per-user transfer code enable/disable fields
            // NULL means use global settings, 0 = disabled, 1 = enabled
            if (!Schema::hasColumn('users', 'code1_enabled')) {
                $table->boolean('code1_enabled')->nullable()->after('code3');
            }
            if (!Schema::hasColumn('users', 'code2_enabled')) {
                $table->boolean('code2_enabled')->nullable()->after('code1_enabled');
            }
            if (!Schema::hasColumn('users', 'code3_enabled')) {
                $table->boolean('code3_enabled')->nullable()->after('code2_enabled');
            }
            if (!Schema::hasColumn('users', 'otp_enabled')) {
                $table->boolean('otp_enabled')->nullable()->after('code3_enabled');
            }
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
            if (Schema::hasColumn('users', 'code1_enabled')) {
                $table->dropColumn('code1_enabled');
            }
            if (Schema::hasColumn('users', 'code2_enabled')) {
                $table->dropColumn('code2_enabled');
            }
            if (Schema::hasColumn('users', 'code3_enabled')) {
                $table->dropColumn('code3_enabled');
            }
            if (Schema::hasColumn('users', 'otp_enabled')) {
                $table->dropColumn('otp_enabled');
            }
        });
    }
}
