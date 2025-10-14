<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'sample_type_id',
        'description',
        'sample_count',
        'sample_rate',
        'sample_total',
        'test_total',
        'discount_percent',
        'discount_amount',
        'line_total',
        'tat_days',
    ];

    protected $casts = [
        'sample_count' => 'integer',
        'sample_rate' => 'decimal:2',
        'sample_total' => 'decimal:2',
        'test_total' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function sampleType(): BelongsTo
    {
        return $this->belongsTo(SampleType::class, 'sample_type_id');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(QuotationItemTest::class);
    }

    /**
     * Calculate line total
     */
    public function calculateLineTotal()
    {
        // Calculate sample total
        $this->sample_total = $this->sample_count * $this->sample_rate;
        
        // Base amount is sample total + test total
        $baseAmount = $this->sample_total + $this->test_total;
        
        // Apply discount
        if ($this->discount_percent > 0) {
            $this->discount_amount = ($baseAmount * $this->discount_percent) / 100;
        } else {
            $this->discount_amount = 0;
        }
        
        $this->line_total = $baseAmount - $this->discount_amount;
        
        return $this->line_total;
    }

    /**
     * Boot method to auto-calculate line total
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->calculateLineTotal();
        });
    }
}


