<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSenderFieldsToWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Ensure type column exists for Credit/Debit distinction
            if (!Schema::hasColumn('withdrawals', 'type')) {
                $table->string('type')->nullable()->after('payment_mode')->comment('Credit or Debit');
            }
            
            // Sender information fields for incoming credits
            $table->string('sender_name')->nullable()->after('type')->comment('Name of person/entity sending money');
            $table->string('sender_bank')->nullable()->after('sender_name')->comment('Bank from which money was sent');
            $table->string('sender_account_number')->nullable()->after('sender_bank')->comment('Sender account number');
            $table->text('sender_bank_address')->nullable()->after('sender_account_number')->comment('Sender bank address');
            $table->string('reference_number')->nullable()->after('sender_bank_address')->comment('External bank reference/confirmation number');
            
            // Additional tracking fields
            $table->text('admin_note')->nullable()->after('reference_number')->comment('Internal admin notes');
            $table->timestamp('processed_at')->nullable()->after('admin_note')->comment('When admin processed the credit');
            $table->unsignedBigInteger('processed_by')->nullable()->after('processed_at')->comment('Admin ID who processed');
            
            // Index for faster queries
            $table->index('reference_number');
            $table->index('sender_account_number');
            $table->index('type');
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
            $table->dropIndex(['reference_number']);
            $table->dropIndex(['sender_account_number']);
            $table->dropIndex(['type']);
            
            $table->dropColumn([
                'sender_name',
                'sender_bank',
                'sender_account_number',
                'sender_bank_address',
                'reference_number',
                'admin_note',
                'processed_at',
                'processed_by'
            ]);
        });
    }
}
