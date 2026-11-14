<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPwaFieldsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Use a single JSON column to store all PWA settings
            // This avoids the MySQL 65,535 byte row size limit
            $table->json('pwa_settings')->nullable()->after('description');
        });
        
        // Set default PWA settings for existing records
        if (Schema::hasTable('settings')) {
            DB::table('settings')->update([
                'pwa_settings' => json_encode([
                    'short_name' => null,
                    'description' => null,
                    'theme_color' => '#2d8659',
                    'background_color' => '#0a1929',
                    'display' => 'standalone',
                    'orientation' => 'portrait',
                    'start_url' => '/dashboard',
                    'enabled' => true,
                ])
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('pwa_settings');
        });
    }
}
