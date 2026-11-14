<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 
        'type', 
        'title', 
        'message', 
        'data', 
        'read_at'
    ];
    
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Check if notification is read
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => Carbon::now()])->save();
        }
    }
    
    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
    
    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
    
    /**
     * Get notification icon based on type
     */
    public function getIconAttribute()
    {
        $icons = [
            'transfer' => 'send',
            'deposit' => 'arrow-down-to-line',
            'withdrawal' => 'arrow-up-from-line',
            'password_change' => 'shield-check',
            'login' => 'log-in',
            'card' => 'credit-card',
            'loan' => 'piggy-bank',
            'investment' => 'trending-up',
            'grant' => 'hand-coins',
            'profile_update' => 'user-cog',
            'default' => 'bell',
        ];
        
        return $icons[$this->type] ?? $icons['default'];
    }
    
    /**
     * Get notification color based on type
     */
    public function getColorAttribute()
    {
        $colors = [
            'transfer' => 'blue',
            'deposit' => 'forest',
            'withdrawal' => 'orange',
            'password_change' => 'champagne',
            'login' => 'green',
            'card' => 'forest',
            'loan' => 'champagne',
            'investment' => 'green',
            'grant' => 'champagne',
            'profile_update' => 'blue',
            'default' => 'gray',
        ];
        
        return $colors[$this->type] ?? $colors['default'];
    }
}
