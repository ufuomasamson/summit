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

if (!function_exists('media_url')) {
    /**
     * Build a /media/{folder}/{file} URL served by MediaController.
     * Works even when Nginx blocks /storage/*.
     *
     * @param  string  $folder  branding|photos
     * @param  string  $file    basename
     * @return string
     */
    function media_url($folder, $file)
    {
        $folder = basename(str_replace('\\', '/', (string) $folder));
        $file = basename(str_replace('\\', '/', (string) $file));

        if ($file === '' || $file === '.' || $file === '..') {
            return '';
        }

        return url('/media/' . rawurlencode($folder) . '/' . rawurlencode($file));
    }
}

if (!function_exists('branding_url')) {
    /**
     * Public URL for logo/favicon.
     *
     * @param  string|null  $path
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

        $basename = basename($path);
        if ($basename === '' || $basename === '.' || $basename === '..') {
            return '';
        }

        // Logos may historically live under photos/ or branding/
        $folder = 'branding';
        if (strpos($path, 'photos/') === 0) {
            $folder = is_file(public_path('branding/' . $basename)) ? 'branding' : 'photos';
        }

        return media_url($folder, $basename);
    }
}

if (!function_exists('profile_photo_url')) {
    /**
     * Public URL for a user profile photo.
     *
     * @param  mixed  $userOrPath  User model/array or raw profile_photo_path
     * @param  string  $fallback
     * @return string
     */
    function profile_photo_url($userOrPath, $fallback = '')
    {
        $path = null;

        if (is_object($userOrPath)) {
            $path = $userOrPath->profile_photo_path ?? null;
        } elseif (is_array($userOrPath)) {
            $path = $userOrPath['profile_photo_path'] ?? null;
        } else {
            $path = $userOrPath;
        }

        if ($path === null || $path === '') {
            return $fallback;
        }

        $path = ltrim(str_replace('\\', '/', (string) $path), '/');

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $basename = basename($path);
        if ($basename === '' || $basename === '.' || $basename === '..') {
            return $fallback;
        }

        return media_url('photos', $basename);
    }
}
