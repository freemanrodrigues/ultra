<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Quotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quotation_number',
        'revision',
        'reference_number',
        'company_id',
        'customer_id',
        'site_id',
        'billing_address',
        'shipping_address',
        'quotation_date',
        'valid_from',
        'valid_until',
        'validity_days',
        'subtotal',
        'discount_percent',
        'discount_amount',
        'tax_percent',
        'tax_amount',
        'total_amount',
        'terms_and_conditions',
        'notes',
        'special_instructions',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'updated_by',
        'converted_to_po_id',
        'converted_at',
        'expiry_alert_sent',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'approved_at' => 'datetime',
        'converted_at' => 'datetime',
        'expiry_alert_sent' => 'boolean',
        'subtotal' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyMaster::class, 'company_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerMaster::class, 'customer_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(SiteMaster::class, 'site_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function itemTests(): HasMany
    {
        return $this->hasMany(QuotationItemTest::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(QuotationAttachment::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(QuotationApproval::class);
    }

    /**
     * Scopes
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 1);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 2);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 3);
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', Carbon::now());
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->whereBetween('valid_until', [
            Carbon::now(),
            Carbon::now()->addDays($days)
        ]);
    }

    /**
     * Accessors & Mutators
     */
    public function getStatusNameAttribute()
    {
        $statuses = config('constants.QUOTATION_STATUS');
        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until < Carbon::now();
    }

    public function getDaysUntilExpiryAttribute()
    {
        return Carbon::now()->diffInDays($this->valid_until, false);
    }

    public function getFormattedQuotationNumberAttribute()
    {
        if ($this->revision > 1) {
            return "{$this->quotation_number} (Rev. {$this->revision})";
        }
        return $this->quotation_number;
    }

    /**
     * Static Methods
     */
    public static function generateQuotationNumber()
    {
        $prefix = 'QT';
        $year = date('Y');
        $month = date('m');
        
        $lastQuotation = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastQuotation ? (int) substr($lastQuotation->quotation_number, -4) + 1 : 1;
        
        return sprintf('%s%s%s%04d', $prefix, $year, $month, $sequence);
    }

    /**
     * Create new revision
     */
    public function createRevision()
    {
        $newQuotation = $this->replicate();
        $newQuotation->revision = $this->revision + 1;
        $newQuotation->status = 1; // Draft
        $newQuotation->approved_by = null;
        $newQuotation->approved_at = null;
        $newQuotation->save();

        // Copy items
        foreach ($this->items as $item) {
            $newItem = $item->replicate();
            $newItem->quotation_id = $newQuotation->id;
            $newItem->save();
        }

        return $newQuotation;
    }

    /**
     * Calculate totals
     */
    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('line_total');
        
        // Apply discount
        if ($this->discount_percent > 0) {
            $this->discount_amount = ($this->subtotal * $this->discount_percent) / 100;
        }
        
        $amountAfterDiscount = $this->subtotal - $this->discount_amount;
        
        // Apply tax
        $this->tax_amount = ($amountAfterDiscount * $this->tax_percent) / 100;
        
        // Total
        $this->total_amount = $amountAfterDiscount + $this->tax_amount;
        
        $this->save();
    }
}

