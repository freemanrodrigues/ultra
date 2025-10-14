<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItemTest extends Model
{
    protected $fillable = [
        'quotation_id',
        'quotation_item_id',
        'test_id',
        'standard_rate',
    ];

    protected $casts = [
        'standard_rate' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function quotationItem(): BelongsTo
    {
        return $this->belongsTo(QuotationItem::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(TestMaster::class, 'test_id');
    }
}

