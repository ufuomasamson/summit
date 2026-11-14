<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_plans extends Model
{
    use HasFactory;

    protected $fillable = [
        'user',
        'plan',
        'plan_name',
        'amount',
        'active',
        'inv_duration',
        'duration',
        'income_range',
        'loan_purpose',
        'rejection_reason',
        'expire_date',
        'activated_at',
        'last_growth',
        'roi_received',
        'capital_returned',
        'investment_type',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'last_growth' => 'datetime',
        'expire_date' => 'datetime',
        'capital_returned' => 'boolean',
        'roi_received' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function dplan(){
        return $this->belongsTo(Plans::class, 'plan', 'id');
    }

    public function duser(){
    	return $this->belongsTo(User::class, 'user', 'id');
    }

    public function user(){
    	return $this->belongsTo(User::class, 'user', 'id');
    }

    public function plan_details(){
        return $this->belongsTo(Plans::class, 'plan', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', 'yes');
    }

    public function scopeInvestments($query)
    {
        return $query->where('investment_type', 'investment');
    }

    public function scopeLoans($query)
    {
        return $query->where('investment_type', 'loan');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $status = $this->active;
        $badges = [
            'yes' => 'success',
            'Pending' => 'warning',
            'expired' => 'secondary',
            'cancelled' => 'danger',
        ];
        
        return $badges[$status] ?? 'info';
    }

    public function getTotalReturnAttribute()
    {
        return $this->roi_received;
    }

    public function getIsExpiredAttribute()
    {
        return $this->expire_date && now()->greaterThan($this->expire_date);
    }

}
