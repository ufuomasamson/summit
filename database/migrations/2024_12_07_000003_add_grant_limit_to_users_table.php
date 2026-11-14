<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrantLimitToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'grant_limit')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('grant_limit', 15, 2)->default(0)->after('account_bal');
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
        if (Schema::hasColumn('users', 'grant_limit')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('grant_limit');
            });
        }
    }
}
