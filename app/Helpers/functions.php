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

if (!function_exists('branding_url')) {
    /**
     * Public URL for logo/favicon (and other branding files).
     *
     * New uploads live in public/branding/ so they work when Nginx blocks
     * direct HTTP access to /storage (common on hardened aaPanel hosts).
     * Legacy DB values like photos/xxx.png are still resolved.
     *
     * @param  string|null  $path  Relative path from settings.logo / settings.favicon
     * @return string
     */
    function branding_url($path)
    {
        if ($path === null || $path === '') {
            return '';
        }

        $path = ltrim(str_replace('\\', '/', (string) $path), '/');

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        // Preferred: files under public/branding
        if (strpos($path, 'branding/') === 0 && is_file(public_path($path))) {
            return asset($path);
        }

        $basename = basename($path);
        if ($basename !== '' && is_file(public_path('branding/' . $basename))) {
            return asset('branding/' . $basename);
        }

        // Standard Laravel public/storage symlink copy
        if (is_file(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        // Promote legacy storage/app/public files into public/branding so they
        // remain visible when Nginx blocks direct /storage/* access.
        $legacy = storage_path('app/public/' . $path);
        if ($basename !== '' && is_file($legacy)) {
            $destDir = public_path('branding');
            if (!is_dir($destDir)) {
                @mkdir($destDir, 0775, true);
            }
            $dest = $destDir . DIRECTORY_SEPARATOR . $basename;
            if (!is_file($dest)) {
                @copy($legacy, $dest);
                @chmod($dest, 0664);
            }
            if (is_file($dest)) {
                return asset('branding/' . $basename);
            }
        }

        // Last resort legacy URL
        return asset('storage/app/public/' . $path);
    }
}
