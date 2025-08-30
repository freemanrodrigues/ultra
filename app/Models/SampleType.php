<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleType extends Model
{
     protected $fillable = ['sample_type_name','description', 'status'];

    public static function getSampleType(){
       return  SampleType::where('status',1)->get();
    }
}
