<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSmsSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('provider')->default('twilio');
            
            // Twilio
            $table->string('twilio_sid')->nullable();
            $table->string('twilio_token')->nullable();
            $table->string('twilio_from')->nullable();
            
            // Nexmo
            $table->string('nexmo_key')->nullable();
            $table->string('nexmo_secret')->nullable();
            $table->string('nexmo_from')->nullable();
            
            // Africa's Talking
            $table->string('africastalking_username')->nullable();
            $table->string('africastalking_api_key')->nullable();
            $table->string('africastalking_from')->nullable();
            
            // Events
            $table->boolean('event_deposit')->default(true);
            $table->boolean('event_withdrawal')->default(true);
            $table->boolean('event_transfer')->default(true);
            $table->boolean('event_kyc_approved')->default(true);
            $table->boolean('event_kyc_rejected')->default(false);
            $table->boolean('event_card_approved')->default(true);
            $table->boolean('event_grant_approved')->default(true);
            $table->boolean('event_loan_approved')->default(true);
            
            $table->timestamps();
        });

        // Insert default record
        DB::table('sms_settings')->insert([
            'enabled' => false,
            'provider' => 'twilio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('sms_settings');
    }
}