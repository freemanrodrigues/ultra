<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSiteMaster extends Model
{
    protected $fillable = [ 'company_id', 'customer_id', 'site_master_id', 'site_customer_code', 'site_customer_name', 'address', 'city', 'pincode', 'state', 'country', 'lat', 'long', 'status'];

    public function siteMaster()
    {
        return $this->belongsTo(SiteMaster::class, 'site_master_id');
    }

    public function customer()
    {
        return $this->belongsTo(CustomerMaster::class, 'customer_id');
    }
}
