<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCryptoWalletFieldsToWithdrawalsTable extends Migration
{
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'crypto_wallet_address')) {
                $table->string('crypto_wallet_address')->nullable()->after('crypto_currency');
            }
            if (!Schema::hasColumn('withdrawals', 'crypto_txn_hash')) {
                $table->string('crypto_txn_hash')->nullable()->after('crypto_wallet_address');
            }
        });
    }

    public function down()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            if (Schema::hasColumn('withdrawals', 'crypto_wallet_address')) {
                $table->dropColumn('crypto_wallet_address');
            }
            if (Schema::hasColumn('withdrawals', 'crypto_txn_hash')) {
                $table->dropColumn('crypto_txn_hash');
            }
        });
    }
}
