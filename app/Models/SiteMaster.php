<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMaster extends Model
{
    protected $fillable = ['site_code', 'site_name', 'site_display_name', 'email',  'contact_type',  'company_id',  'customer_id',  'address',  'address1',  'city',  'state',  'country',  'lat',  'long',  'customer_type', 'status' ];
  
}
