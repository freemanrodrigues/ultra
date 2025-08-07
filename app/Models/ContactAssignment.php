<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactAssignment extends Model
{
    protected $fillable = ['contact_id' ,'company_id' ,'customer_id' ,'customer_site_id' ,'equipment_id' ,'department' ,'designation' ,'role' ,'level' ,'send_bill' ,'send_report' ,'whatsapp' ,'is_primary'];

}
