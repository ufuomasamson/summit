<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('card_id'); // Match cards.id which is int(11) NOT unsigned
            $table->unsignedBigInteger('user_id');
            $table->string('type', 50); // purchase, refund, fee, topup, deduction, withdrawal
            $table->decimal('amount', 15, 2);
            $table->string('merchant_name')->nullable();
            $table->string('merchant_location')->nullable();
            $table->text('description')->nullable();
            $table->string('reference', 100)->unique();
            $table->string('status', 50)->default('pending'); // pending, completed, failed, reversed
            $table->decimal('balance_before', 15, 2)->nullable();
            $table->decimal('balance_after', 15, 2)->nullable();
            $table->timestamps();

            // Add foreign keys
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('card_transactions');
    }
};
