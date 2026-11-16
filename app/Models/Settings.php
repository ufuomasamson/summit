<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $casts = [
        'return_capital' => 'boolean',
        'should_cancel_plan' => 'boolean',
        'modules' => 'array',
        'pwa_settings' => 'array',
    ];

    // PWA Accessor Methods - Read from JSON column
    public function getPwaShortNameAttribute()
    {
        return $this->pwa_settings['short_name'] ?? null;
    }

    public function getPwaDescriptionAttribute()
    {
        return $this->pwa_settings['description'] ?? null;
    }

    public function getPwaThemeColorAttribute()
    {
        return $this->pwa_settings['theme_color'] ?? '#2d8659';
    }

    public function getPwaBackgroundColorAttribute()
    {
        return $this->pwa_settings['background_color'] ?? '#0a1929';
    }

    public function getPwaDisplayAttribute()
    {
        return $this->pwa_settings['display'] ?? 'standalone';
    }

    public function getPwaOrientationAttribute()
    {
        return $this->pwa_settings['orientation'] ?? 'portrait';
    }

    public function getPwaStartUrlAttribute()
    {
        return $this->pwa_settings['start_url'] ?? '/dashboard';
    }

    public function getPwaEnabledAttribute()
    {
        return $this->pwa_settings['enabled'] ?? true;
    }

    /**
     * Web-accessible logo URL (public/branding preferred over /storage).
     */
    public function getLogoUrlAttribute()
    {
        return branding_url($this->attributes['logo'] ?? null);
    }

    /**
     * Web-accessible favicon URL.
     */
    public function getFaviconUrlAttribute()
    {
        return branding_url($this->attributes['favicon'] ?? null);
    }

    // public function getModulesAttribute($value)
    // {
    //     return ucfirst($value);
    // }
}