<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountBlocksTable extends Migration
{
    public function up()
    {
        Schema::create('account_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('block_type', ['blocked', 'permanently_banned']);
            $table->text('reason');
            $table->unsignedBigInteger('blocked_by_admin_id');
            $table->timestamp('blocked_at');
            $table->timestamp('unblocked_at')->nullable();
            $table->unsignedBigInteger('unblocked_by_admin_id')->nullable();
            $table->text('unblock_note')->nullable();
            $table->boolean('admin_notified')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'blocked_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_blocks');
    }
}
