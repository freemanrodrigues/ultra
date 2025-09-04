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
        'sample_count',
        'sample_rate',
        'sample_total',
    ];

    protected $casts = [
        'sample_rate' => 'decimal:2',
        'sample_total' => 'decimal:2',
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

    /**
     * Calculate the total amount for this sample
     */
    public function calculateTotal(): float
    {
        $testTotal = $this->tests->sum('total');
        $sampleTotal = $this->sample_count * $this->sample_rate;
        return $testTotal + $sampleTotal;
    }

    /**
     * Get the total number of samples for this sample type in the PO
     */
    public function getTotalSamplesForType(): int
    {
        return self::where('po_id', $this->po_id)
            ->where('sample_type_id', $this->sample_type_id)
            ->sum('sample_count');
    }

    /**
     * Get billing summary for this sample type
     */
    public function getBillingSummary(): array
    {
        $samples = self::where('po_id', $this->po_id)
            ->where('sample_type_id', $this->sample_type_id)
            ->with('tests')
            ->get();

        $totalSamples = $samples->sum('sample_count');
        $totalSampleAmount = $samples->sum('sample_total');
        $totalTestAmount = $samples->sum(function($sample) {
            return $sample->tests->sum('total');
        });

        return [
            'sample_type' => $this->sampleType->sample_type_name ?? 'Unknown',
            'total_samples' => $totalSamples,
            'sample_amount' => $totalSampleAmount,
            'test_amount' => $totalTestAmount,
            'total_amount' => $totalSampleAmount + $totalTestAmount,
        ];
    }
}

