<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Check and add missing columns if they don't exist
            if (!Schema::hasColumn('withdrawals', 'routingnumber')) {
                $table->string('routingnumber', 50)->nullable()->after('iban');
            }
            if (!Schema::hasColumn('withdrawals', 'paydetails')) {
                $table->text('paydetails')->nullable()->after('routingnumber');
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
        Schema::table('withdrawals', function (Blueprint $table) {
            if (Schema::hasColumn('withdrawals', 'routingnumber')) {
                $table->dropColumn('routingnumber');
            }
            if (Schema::hasColumn('withdrawals', 'paydetails')) {
                $table->dropColumn('paydetails');
            }
        });
    }
}
