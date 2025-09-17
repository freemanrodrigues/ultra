<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class POMaster extends Model
{
    use HasFactory;

    protected $table = 'po_masters';

    protected $fillable = [
        'po_number',
        'po_date',
        'customer_id','company_id',
        'site_id',
        'valid_from',
        'valid_to','currency','test_rate','test_limit',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'po_date' => 'date',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the customer for this PO
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerMaster::class, 'customer_id');
    }


    /**
     * Get the company for this PO
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyMaster::class, 'company_id');
    }

    /**
     * Get the site for this PO
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(SiteMaster::class, 'site_id');
    }

    /**
     * Get the samples for this PO
     */
    public function samples(): HasMany
    {
        return $this->hasMany(POSample::class, 'po_id');
    }

     public static function getPoForACustomer($company_id) {
       return  POMaster::where('company_id', $company_id)->get(['id','po_number'] );
     }   




}
