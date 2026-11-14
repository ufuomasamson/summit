<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('card_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('standard_fee', 10, 2)->default(5.00);
            $table->decimal('gold_fee', 10, 2)->default(15.00);
            $table->decimal('platinum_fee', 10, 2)->default(30.00);
            $table->decimal('black_fee', 10, 2)->default(50.00);
            $table->decimal('monthly_fee', 10, 2)->default(2.00);
            $table->decimal('topup_fee_percentage', 5, 2)->default(1.5);
            $table->decimal('min_daily_limit', 10, 2)->default(100.00);
            $table->decimal('max_daily_limit', 10, 2)->default(10000.00);
            $table->boolean('cards_enabled')->default(true);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('card_settings')->insert([
            'standard_fee' => 5.00,
            'gold_fee' => 15.00,
            'platinum_fee' => 30.00,
            'black_fee' => 50.00,
            'monthly_fee' => 2.00,
            'topup_fee_percentage' => 1.5,
            'min_daily_limit' => 100.00,
            'max_daily_limit' => 10000.00,
            'cards_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('card_settings');
    }
};
