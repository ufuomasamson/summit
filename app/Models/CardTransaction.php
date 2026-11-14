<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CardTransaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'card_id',
        'user_id',
        'type',
        'amount',
        'merchant_name',
        'merchant_location',
        'description',
        'reference',
        'status',
        'balance_before',
        'balance_after'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($transaction) {
            if (!$transaction->reference) {
                $transaction->reference = 'TXN-' . strtoupper(Str::random(12));
            }
        });
    }

    // Relationships
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'purchase' => 'red',
            'refund' => 'blue',
            'topup' => 'green',
            'withdrawal' => 'yellow',
            'fee' => 'orange',
            'deduction' => 'red',
            default => 'gray'
        };
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'purchase' => 'shopping-cart',
            'refund' => 'arrow-down-left',
            'topup' => 'arrow-up-right',
            'withdrawal' => 'arrow-down-right',
            'fee' => 'credit-card',
            'deduction' => 'minus-circle',
            default => 'activity'
        };
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForCard($query, $cardId)
    {
        return $query->where('card_id', $cardId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
