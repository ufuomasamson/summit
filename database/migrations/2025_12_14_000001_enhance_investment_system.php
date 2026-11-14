<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceInvestmentSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add additional fields to plans table for investment
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'roi_type')) {
                $table->string('roi_type')->default('percentage')->after('increment_amount'); // percentage or fixed
            }
            if (!Schema::hasColumn('plans', 'roi_value')) {
                $table->decimal('roi_value', 10, 2)->default(0)->after('roi_type'); // ROI value
            }
            if (!Schema::hasColumn('plans', 'duration_type')) {
                $table->string('duration_type')->default('days')->after('roi_value'); // days, weeks, months
            }
            if (!Schema::hasColumn('plans', 'duration')) {
                $table->integer('duration')->default(30)->after('duration_type'); // duration number
            }
            if (!Schema::hasColumn('plans', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('duration');
            }
            if (!Schema::hasColumn('plans', 'return_capital')) {
                $table->boolean('return_capital')->default(true)->after('is_active'); // return capital after duration
            }
        });

        // Add investment-specific fields to user_plans
        Schema::table('user_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('user_plans', 'roi_received')) {
                $table->decimal('roi_received', 15, 2)->default(0)->after('amount'); // Total ROI received
            }
            if (!Schema::hasColumn('user_plans', 'capital_returned')) {
                $table->boolean('capital_returned')->default(false)->after('roi_received');
            }
            if (!Schema::hasColumn('user_plans', 'investment_type')) {
                $table->string('investment_type')->default('investment')->after('capital_returned'); // investment or loan
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
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['roi_type', 'roi_value', 'duration_type', 'duration', 'is_active', 'return_capital']);
        });

        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn(['roi_received', 'capital_returned', 'investment_type']);
        });
    }
}
