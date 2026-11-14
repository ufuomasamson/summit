<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'card_type', 'card_level', 'card_number', 'cvv', 'expiry',
        'expiry_month', 'expiry_year', 'balance', 'daily_limit', 'cardholder_name',
        'billing_address', 'billing_city', 'billing_country', 'billing_zipcode',
        'card_pan', 'card_token', 'card_bin', 'last_four', 'status',
        'rejection_reason', 'activated_at', 'blocked_at', 'amount'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'daily_limit' => 'decimal:2',
        'amount' => 'decimal:2',
        'activated_at' => 'datetime',
        'blocked_at' => 'datetime',
    ];

    protected $hidden = [
        'card_pan',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(CardTransaction::class);
    }

    // Accessors
    public function getMaskedCardNumberAttribute()
    {
        if (!$this->card_number) return null;
        return '**** **** **** ' . substr($this->card_number, -4);
    }

    public function getFormattedCardNumberAttribute()
    {
        if (!$this->card_number) return null;
        return chunk_split($this->card_number, 4, ' ');
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsBlockedAttribute()
    {
        return $this->status === 'blocked';
    }

    public function getCardLevelColorAttribute()
    {
        return match($this->card_level) {
            'Standard' => 'gray',
            'Gold' => 'yellow',
            'Platinum' => 'blue',
            'Black' => 'black',
            default => 'gray'
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
