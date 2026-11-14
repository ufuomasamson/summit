<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnableCryptoTransferToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('settings', 'enable_crypto_transfer')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('enable_crypto_transfer', 10)->default('yes')->after('enable_kyc_registration');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('enable_crypto_transfer');
        });
    }
}
