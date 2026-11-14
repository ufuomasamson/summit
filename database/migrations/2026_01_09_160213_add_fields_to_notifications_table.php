<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('type')->after('user_id')->nullable(); // transfer, deposit, password_change, etc.
            $table->string('title')->after('type')->nullable();
            $table->json('data')->after('message')->nullable(); // additional data like amount, currency, etc.
            $table->timestamp('read_at')->after('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['type', 'title', 'data', 'read_at']);
        });
    }
}
