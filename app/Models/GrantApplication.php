<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrantApplication extends Model
{
    use HasFactory;

    protected $table = 'grant_applications';

    protected $fillable = [
        'user_id',
        'application_type',
        'status',
        'program_funding',
        'equipment_funding',
        'research_funding',
        'community_outreach',
        'legal_name',
        'mailing_address',
        'ein',
        'incorporation_date',
        'mission_statement',
        'service_areas',
        'organization_history',
        'requested_amount',
        'approved_amount',
        'disbursal_date',
        'notes'
    ];

    protected $casts = [
        'program_funding' => 'boolean',
        'equipment_funding' => 'boolean',
        'research_funding' => 'boolean',
        'community_outreach' => 'boolean',
        'incorporation_date' => 'date',
        'requested_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'disbursal_date' => 'datetime',
    ];

    /**
     * Get the user that owns the grant application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add a note to the application.
     */
    public function addNote($note)
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $newNote = "[{$timestamp}] {$note}\n\n";
        $this->notes = $newNote . ($this->notes ?? '');
        $this->save();
    }

    /**
     * Get status badge class for display.
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'processing' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            'approved' => 'bg-green-500/10 text-green-400 border-green-500/20',
            'rejected' => 'bg-red-500/10 text-red-400 border-red-500/20',
            'disbursed' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
            default => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
        };
    }

    /**
     * Get status display name.
     */
    public function getStatusDisplayName()
    {
        return ucfirst($this->status);
    }
}
