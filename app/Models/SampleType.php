<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleType extends Model
{
    protected $fillable = ['sample_type_code','sample_type_name','remark','mis_group', 'status'];
}
