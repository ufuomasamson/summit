<?php

/**
 * Helper functions for BankApp
 * 
 * These functions provide convenient access to common operations
 * throughout the application.
 */

if (!function_exists('get_settings')) {
    /**
     * Get the application settings
     * 
     * Retrieves the settings from the database. This provides a centralized
     * way to access site configuration across the application.
     * 
     * @return \App\Models\Settings|null
     */
    function get_settings()
    {
        return \App\Models\Settings::first();
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format amount with currency symbol
     * 
     * @param float $amount
     * @param \App\Models\Settings|null $settings
     * @return string
     */
    function format_currency($amount, $settings = null)
    {
        $settings = $settings ?? get_settings();
        return $settings->currency . number_format($amount, 2);
    }
}
