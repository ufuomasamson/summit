<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'min_price',
        'max_price',
        'minr',
        'maxr',
        'gift',
        'expected_return',
        'type',
        'increment_interval',
        'increment_type',
        'increment_amount',
        'expiration',
        'roi_type',
        'roi_value',
        'duration_type',
        'duration',
        'is_active',
        'return_capital',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'return_capital' => 'boolean',
        'roi_value' => 'decimal:2',
    ];

    // Relationships
    public function userPlans()
    {
        return $this->hasMany(User_plans::class, 'plan', 'id');
    }

    public function activeInvestments()
    {
        return $this->hasMany(User_plans::class, 'plan', 'id')
                    ->where('active', 'yes')
                    ->where('investment_type', 'investment');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInvestment($query)
    {
        return $query->where('type', 'Main');
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        return $this->duration . ' ' . ucfirst($this->duration_type);
    }

    public function getFormattedRoiAttribute()
    {
        if ($this->roi_type === 'percentage') {
            return $this->roi_value . '%';
        }
        return '$' . number_format($this->roi_value, 2);
    }
}
