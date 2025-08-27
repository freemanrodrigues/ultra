<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleMaster extends Model
{
    public function customer()
    {
        return $this->belongsTo(CustomerMaster::class, 'customer_id');
    } 
    public function customer_site_masters()
    {
        return $this->belongsTo(CustomerSiteMaster::class, 'customer_site_id');
    } 
}
