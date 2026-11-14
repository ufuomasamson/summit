<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'btc',
        'eth',
        'usdt',
        'ltc',
        'xrp',
        'link',
        'bat',
        'aave',
        'xlm',
        'bch',
    ];

    protected $casts = [
        'btc' => 'float',
        'eth' => 'float',
        'usdt' => 'float',
        'ltc' => 'float',
        'xrp' => 'float',
        'link' => 'float',
        'bat' => 'float',
        'aave' => 'float',
        'xlm' => 'float',
        'bch' => 'float',
    ];

    /**
     * The supported crypto coins for transfers/swaps
     */
    public const SUPPORTED_COINS = ['btc', 'eth', 'usdt'];

    /**
     * Coin display names
     */
    public const COIN_NAMES = [
        'btc' => 'Bitcoin',
        'eth' => 'Ethereum',
        'usdt' => 'USDT',
    ];

    /**
     * CoinGecko API IDs for supported coins
     */
    public const COINGECKO_IDS = [
        'btc' => 'bitcoin',
        'eth' => 'ethereum',
        'usdt' => 'tether',
    ];

    /**
     * Get the user that owns this crypto account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the balance of a specific coin.
     */
    public function getCoinBalance(string $coin): float
    {
        $coin = strtolower($coin);
        if (in_array($coin, self::SUPPORTED_COINS)) {
            return (float) ($this->$coin ?? 0);
        }
        return 0;
    }

    /**
     * Check if user has sufficient crypto balance.
     */
    public function hasSufficientBalance(string $coin, float $amount): bool
    {
        return $this->getCoinBalance($coin) >= $amount;
    }
}
