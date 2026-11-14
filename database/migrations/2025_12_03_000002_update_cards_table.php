<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, check and clean any existing cards with invalid data
        if (Schema::hasColumn('cards', 'user')) {
            DB::statement('DELETE FROM cards WHERE user IS NULL OR user = ""');
        }
        
        Schema::table('cards', function (Blueprint $table) {
            // Add new columns first (without foreign key)
            if (!Schema::hasColumn('cards', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('cards', 'card_type')) {
                $table->string('card_type', 50)->default('Visa');
            }
            if (!Schema::hasColumn('cards', 'card_level')) {
                $table->string('card_level', 50)->default('Standard');
            }
            if (!Schema::hasColumn('cards', 'balance')) {
                $table->decimal('balance', 15, 2)->default(0.00);
            }
            if (!Schema::hasColumn('cards', 'daily_limit')) {
                $table->decimal('daily_limit', 10, 2)->default(1000.00);
            }
            if (!Schema::hasColumn('cards', 'cardholder_name')) {
                $table->string('cardholder_name')->nullable();
            }
            if (!Schema::hasColumn('cards', 'billing_address')) {
                $table->text('billing_address')->nullable();
            }
            if (!Schema::hasColumn('cards', 'billing_city')) {
                $table->string('billing_city')->nullable();
            }
            if (!Schema::hasColumn('cards', 'billing_country')) {
                $table->string('billing_country')->nullable();
            }
            if (!Schema::hasColumn('cards', 'billing_zipcode')) {
                $table->string('billing_zipcode')->nullable();
            }
            if (!Schema::hasColumn('cards', 'card_pan')) {
                $table->text('card_pan')->nullable(); // Encrypted card number
            }
            if (!Schema::hasColumn('cards', 'card_token')) {
                $table->string('card_token')->unique()->nullable();
            }
            if (!Schema::hasColumn('cards', 'card_bin')) {
                $table->string('card_bin', 6)->nullable();
            }
            if (!Schema::hasColumn('cards', 'last_four')) {
                $table->string('last_four', 4)->nullable();
            }
            if (!Schema::hasColumn('cards', 'expiry_month')) {
                $table->string('expiry_month', 2)->nullable();
            }
            if (!Schema::hasColumn('cards', 'expiry_year')) {
                $table->string('expiry_year', 4)->nullable();
            }
            
            if (!Schema::hasColumn('cards', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            if (!Schema::hasColumn('cards', 'activated_at')) {
                $table->timestamp('activated_at')->nullable();
            }
            if (!Schema::hasColumn('cards', 'blocked_at')) {
                $table->timestamp('blocked_at')->nullable();
            }
        });
        
        // Map old user column to user_id if exists
        if (Schema::hasColumn('cards', 'user')) {
            // Try to map user emails to user IDs
            DB::statement('
                UPDATE cards c 
                INNER JOIN users u ON c.user = u.email OR c.user = u.id
                SET c.user_id = u.id
                WHERE c.user_id IS NULL
            ');
            
            // Delete any cards that couldn't be mapped
            DB::statement('DELETE FROM cards WHERE user_id IS NULL');
            
            // Now drop the old user column
            Schema::table('cards', function (Blueprint $table) {
                $table->dropColumn('user');
            });
        }
        
        // Make user_id not nullable and add foreign key
        Schema::table('cards', function (Blueprint $table) {
            DB::statement('ALTER TABLE cards MODIFY user_id BIGINT UNSIGNED NOT NULL');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Update existing status column if needed - use raw SQL to avoid Doctrine dependency
        if (Schema::hasColumn('cards', 'status')) {
            // Check if status is not already the right type
            $columnType = DB::select("SHOW COLUMNS FROM cards WHERE Field = 'status'");
            if (!empty($columnType) && strpos($columnType[0]->Type, 'varchar') === false) {
                // If it's not varchar, we need to recreate it
                DB::statement("ALTER TABLE cards MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
            }
        } else {
            Schema::table('cards', function (Blueprint $table) {
                $table->string('status', 50)->default('pending');
            });
        }
    }

    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            if (Schema::hasColumn('cards', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
            
            $columnsToCheck = [
                'user_id', 'card_type', 'card_level', 'balance', 'daily_limit',
                'cardholder_name', 'billing_address', 'billing_city', 'billing_country',
                'billing_zipcode', 'card_pan', 'card_token', 'card_bin', 'last_four',
                'expiry_month', 'expiry_year', 'rejection_reason', 'activated_at', 'blocked_at'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('cards', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
