<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrantApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grant_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('application_type', ['individual', 'company']);
            $table->enum('status', ['processing', 'approved', 'rejected', 'disbursed'])->default('processing');
            
            // Individual funding types
            $table->boolean('program_funding')->default(0);
            $table->boolean('equipment_funding')->default(0);
            $table->boolean('research_funding')->default(0);
            $table->boolean('community_outreach')->default(0);
            
            // Company information
            $table->string('legal_name')->nullable();
            $table->text('mailing_address')->nullable();
            $table->string('ein', 30)->nullable();
            $table->date('incorporation_date')->nullable();
            $table->text('mission_statement')->nullable();
            $table->text('service_areas')->nullable();
            $table->text('organization_history')->nullable();
            
            // Financial information
            $table->decimal('requested_amount', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->datetime('disbursal_date')->nullable();
            
            // Additional information
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grant_applications');
    }
}
