<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POTest extends Model
{
    use HasFactory;

    protected $table = 'po_tests';

    protected $fillable = ['po_id',
        'po_sample_id',
        'test_id',
        'price',
        'quantity',
        'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the PO sample that owns this test
     */
    public function poSample(): BelongsTo
    {
        return $this->belongsTo(POSample::class, 'po_sample_id');
    }

    /**
     * Get the test details
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(TestMaster::class, 'test_id');
    }
}

