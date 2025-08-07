<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMaster extends Model
{
    protected $fillable = ['company_id', 'customer_id', 'firstname', 'lastname', 'phone', 'email', 'user_id', 'status'];

    public function company()
    {
        return $this->belongsTo(CompanyMaster::class, 'company_id');
    } 
}
