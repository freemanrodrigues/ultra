<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class POMaster extends Model
{
    protected $table = 'po_masters';
    protected $fillable = ['po_number','po_date','party_id','site_id','valid_from','valid_to','currency','sample_type_id','test_rate','test_limit','status'];
}
