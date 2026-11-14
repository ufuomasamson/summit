<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardSettings extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'standard_fee',
        'gold_fee',
        'platinum_fee',
        'black_fee',
        'monthly_fee',
        'topup_fee_percentage',
        'min_daily_limit',
        'max_daily_limit',
        'cards_enabled',
        'admin_notes'
    ];

    protected $casts = [
        'standard_fee' => 'decimal:2',
        'gold_fee' => 'decimal:2',
        'platinum_fee' => 'decimal:2',
        'black_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'topup_fee_percentage' => 'decimal:2',
        'min_daily_limit' => 'decimal:2',
        'max_daily_limit' => 'decimal:2',
        'cards_enabled' => 'boolean',
    ];

    public static function getSettings()
    {
        return self::first() ?? self::create([]);
    }

    public function getIssuanceFee($level)
    {
        return match($level) {
            'Standard' => $this->standard_fee,
            'Gold' => $this->gold_fee,
            'Platinum' => $this->platinum_fee,
            'Black' => $this->black_fee,
            default => $this->standard_fee
        };
    }

    public function calculateTopupFee($amount)
    {
        return ($amount * $this->topup_fee_percentage) / 100;
    }
}
