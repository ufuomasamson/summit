<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixCryptoAccountsColumnPrecision extends Migration
{
    /**
     * Fix crypto_accounts columns from float(8,2) to decimal(20,8).
     * float(8,2) only allows 2 decimal places and max ~999,999.
     * Crypto needs 8 decimal places (e.g., 0.00000001 BTC).
     */
    public function up()
    {
        $coins = ['btc', 'eth', 'ltc', 'xrp', 'link', 'bnb', 'aave', 'usdt', 'xlm', 'bch', 'ada'];
        
        // Disable strict mode temporarily to allow float→decimal conversion
        DB::statement("SET SESSION sql_mode = ''");
        
        foreach ($coins as $coin) {
            DB::statement("ALTER TABLE `crypto_accounts` MODIFY `{$coin}` DECIMAL(20,8) NOT NULL DEFAULT 0");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $coins = ['btc', 'eth', 'ltc', 'xrp', 'link', 'bnb', 'aave', 'usdt', 'xlm', 'bch', 'ada'];
        
        foreach ($coins as $coin) {
            DB::statement("ALTER TABLE `crypto_accounts` MODIFY `{$coin}` FLOAT(8,2) NOT NULL DEFAULT 0");
        }
    }
}
