<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    /**
     * All valid transaction statuses
     */
    const STATUS_PENDING = 'Pending';
    const STATUS_PROCESSED = 'Processed';
    const STATUS_ON_HOLD = 'On-hold';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_FAILED = 'Failed';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_REVERSED = 'Reversed';
    const STATUS_REFUNDED = 'Refunded';
    const STATUS_SCHEDULED = 'Scheduled';
    const STATUS_EXPIRED = 'Expired';
    const STATUS_UNDER_REVIEW = 'Under Review';
    const STATUS_FROZEN = 'Frozen';

    /**
     * Get all valid statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSED,
            self::STATUS_ON_HOLD,
            self::STATUS_REJECTED,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED,
            self::STATUS_REVERSED,
            self::STATUS_REFUNDED,
            self::STATUS_SCHEDULED,
            self::STATUS_EXPIRED,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_FROZEN,
        ];
    }

    /**
     * Get validation rule string for statuses
     */
    public static function getStatusValidationRule(): string
    {
        return 'in:' . implode(',', self::getStatuses());
    }

    /**
     * Statuses that represent a "completed" or "successful" state
     */
    public static function getSuccessStatuses(): array
    {
        return [self::STATUS_PROCESSED];
    }

    /**
     * Statuses that represent a "pending/in-progress" state
     */
    public static function getPendingStatuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_ON_HOLD, self::STATUS_SCHEDULED, self::STATUS_UNDER_REVIEW];
    }

    /**
     * Statuses that represent a "failed/negative" state
     */
    public static function getFailedStatuses(): array
    {
        return [self::STATUS_REJECTED, self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_EXPIRED, self::STATUS_FROZEN];
    }

    /**
     * Statuses that represent a "reversed/refunded" state
     */
    public static function getReversedStatuses(): array
    {
        return [self::STATUS_REVERSED, self::STATUS_REFUNDED];
    }

    /**
     * Get the CSS badge class for this status (Bootstrap)
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $map = [
            self::STATUS_PENDING      => 'badge-warning',
            self::STATUS_PROCESSED    => 'badge-success',
            self::STATUS_ON_HOLD      => 'badge-warning',
            self::STATUS_REJECTED     => 'badge-danger',
            self::STATUS_FAILED       => 'badge-danger',
            self::STATUS_CANCELLED    => 'badge-secondary',
            self::STATUS_REVERSED     => 'badge-info',
            self::STATUS_REFUNDED     => 'badge-info',
            self::STATUS_SCHEDULED    => 'badge-primary',
            self::STATUS_EXPIRED      => 'badge-dark',
            self::STATUS_UNDER_REVIEW => 'badge-warning',
            self::STATUS_FROZEN       => 'badge-dark',
        ];
        return $map[$this->status] ?? 'badge-secondary';
    }

    /**
     * Check if the transaction can be refunded/reversed
     */
    public function canBeReversed(): bool
    {
        return $this->status === self::STATUS_PROCESSED;
    }

    /**
     * Check if balance should be refunded for this status change
     */
    public static function shouldRefundBalance(string $newStatus): bool
    {
        return in_array($newStatus, [
            self::STATUS_REJECTED,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED,
            self::STATUS_REVERSED,
            self::STATUS_REFUNDED,
        ]);
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'amount',
        'to_deduct',
        'payment_mode',
        'type',
        'status',
        'accountname',
        'accountnumber',
        'bankname',
        'Accounttype',
        'Description',
        'bankaddress',
        'country',
        'swiftcode',
        'iban',
        'routingnumber',
        'paydetails',
        'date',
        'txn_id',
        'bal',
        'city',
        'zipcode',
        // Sender information for credits
        'sender_name',
        'sender_bank',
        'sender_account_number',
        'sender_bank_address',
        'reference_number',
        'admin_note',
        'processed_at',
        'processed_by',
        // Crypto transaction fields
        'crypto_currency',
        'crypto_wallet_address',
        'crypto_txn_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2',
        'to_deduct' => 'decimal:2',
        'bal' => 'decimal:2',
        'processed_at' => 'datetime',
    ];
    
    public function duser(){
    	return $this->belongsTo(User::class , 'user');
    }
    
    /**
     * Get the admin who processed this transaction
     */
    public function processedByAdmin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'processed_by');
    }
    
    /**
     * Check if this is a credit transaction
     */
    public function isCredit()
    {
        return $this->type === 'Credit';
    }
    
    /**
     * Check if this is a debit transaction
     */
    public function isDebit()
    {
        return $this->type === 'Debit';
    }
    
    /**
     * Get sender information as an array
     */
    public function getSenderInfoAttribute()
    {
        if (!$this->isCredit()) {
            return null;
        }
        
        return [
            'name' => $this->sender_name,
            'bank' => $this->sender_bank,
            'account' => $this->sender_account_number,
            'address' => $this->sender_bank_address,
            'reference' => $this->reference_number,
        ];
    }
    
    /**
     * Get formatted display for transaction type
     */
    public function getTypeDisplayAttribute()
    {
        if ($this->isCredit()) {
            return '<span class="badge badge-success">Credit (Incoming)</span>';
        } elseif ($this->isDebit()) {
            return '<span class="badge badge-danger">Debit (Outgoing)</span>';
        }
        return '<span class="badge badge-secondary">Unknown</span>';
    }
}
