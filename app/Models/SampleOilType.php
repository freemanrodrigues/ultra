<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleOilType extends Model
{
    protected $fillable = ['sample_oil_type_code','sample_oil_type_name','remark','mis_group', 'status'];

    public static function getSampleType(){
       return  SampleOilType::where('status',1)->get();
    }
}
