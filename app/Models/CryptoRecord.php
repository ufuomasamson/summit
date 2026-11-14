<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source',
        'dest',
        'amount',
        'quantity',
        'rate',
        'fee',
        'type',
        'status',
    ];

    protected $casts = [
        'amount' => 'float',
        'quantity' => 'float',
        'rate' => 'float',
        'fee' => 'float',
    ];

    /**
     * Swap types
     */
    public const TYPE_MAIN_TO_CRYPTO = 'main_to_crypto';
    public const TYPE_CRYPTO_TO_MAIN = 'crypto_to_main';
    public const TYPE_CRYPTO_TO_CRYPTO = 'crypto_to_crypto';

    /**
     * Get the user that owns this record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
