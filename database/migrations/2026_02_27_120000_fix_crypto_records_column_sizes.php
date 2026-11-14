<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixCryptoRecordsColumnSizes extends Migration
{
    /**
     * Fix column types to handle large USD values when swapping crypto.
     * MySQL FLOAT max is ~16 million, but BTC→USD can produce hundreds of millions.
     */
    public function up()
    {
        // Use raw SQL to alter column types since Blueprint->change() requires doctrine/dbal
        DB::statement('ALTER TABLE `crypto_records` MODIFY `amount` DECIMAL(20,8) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `crypto_records` MODIFY `quantity` DECIMAL(20,8) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `crypto_records` MODIFY `rate` DECIMAL(20,8) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `crypto_records` MODIFY `fee` DECIMAL(20,8) NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('ALTER TABLE `crypto_records` MODIFY `amount` FLOAT NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `crypto_records` MODIFY `quantity` FLOAT NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `crypto_records` MODIFY `rate` FLOAT NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `crypto_records` MODIFY `fee` FLOAT NULL DEFAULT NULL');
    }
}
