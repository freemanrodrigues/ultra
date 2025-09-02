<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class POSample extends Model
{
    use HasFactory;

    protected $table = 'po_samples';

    protected $fillable = [
        'po_id',
        'sample_type_id',
        'description',
    ];

    /**
     * Get the PO that owns this sample
     */
    public function po(): BelongsTo
    {
        return $this->belongsTo(POMaster::class, 'po_id');
    }

    /**
     * Get the sample type for this sample
     */
    public function sampleType(): BelongsTo
    {
        return $this->belongsTo(SampleType::class, 'sample_type_id');
    }

    /**
     * Get the tests for this sample
     */
    public function tests(): HasMany
    {
        return $this->hasMany(POTest::class, 'po_sample_id');
    }
}

