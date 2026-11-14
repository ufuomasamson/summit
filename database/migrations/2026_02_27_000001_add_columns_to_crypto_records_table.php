<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCryptoRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crypto_records', function (Blueprint $table) {
            if (!Schema::hasColumn('crypto_records', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('crypto_records', 'rate')) {
                $table->float('rate', 16, 8)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('crypto_records', 'fee')) {
                $table->float('fee', 12, 2)->default(0)->after('rate');
            }
            if (!Schema::hasColumn('crypto_records', 'type')) {
                $table->string('type')->nullable()->after('fee');
            }
            if (!Schema::hasColumn('crypto_records', 'status')) {
                $table->string('status')->default('completed')->after('type');
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
        Schema::table('crypto_records', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'rate', 'fee', 'type', 'status']);
        });
    }
}
