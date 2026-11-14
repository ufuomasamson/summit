<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'block_type',
        'reason',
        'blocked_by_admin_id',
        'blocked_at',
        'unblocked_at',
        'unblocked_by_admin_id',
        'unblock_note',
        'admin_notified',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
        'unblocked_at' => 'datetime',
        'admin_notified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blockedByAdmin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'blocked_by_admin_id');
    }

    public function unblockedByAdmin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'unblocked_by_admin_id');
    }
}
