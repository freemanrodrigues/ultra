<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationApproval extends Model
{
    protected $fillable = [
        'quotation_id',
        'submitted_by',
        'approved_by',
        'submitted_at',
        'approved_at',
        'status',
        'remarks',
        'rejection_reason',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get status name
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            1 => 'Pending',
            2 => 'Approved',
            3 => 'Rejected',
        ];
        
        return $statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 1);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 2);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 3);
    }
}

