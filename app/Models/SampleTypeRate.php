<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleTypeRate extends Model
{
    use HasFactory;

    protected $table = 'sample_type_rates';

    protected $fillable = [
        'sample_type_id',
        'test_id',
        'rate',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the sample type that owns this rate
     */
    public function sampleType(): BelongsTo
    {
        return $this->belongsTo(SampleType::class, 'sample_type_id');
    }

    /**
     * Get the test that owns this rate
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(TestMaster::class, 'test_id');
    }

    /**
     * Scope to get active rates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get rates for a specific sample type
     */
    public static function getRatesForSampleType($sampleTypeId)
    {
        return self::with('test')
            ->where('sample_type_id', $sampleTypeId)
            ->active()
            ->get();
    }

    /**
     * Get rate for a specific sample type and test combination
     */
    public static function getRate($sampleTypeId, $testId)
    {
        return self::where('sample_type_id', $sampleTypeId)
            ->where('test_id', $testId)
            ->active()
            ->first();
    }
}