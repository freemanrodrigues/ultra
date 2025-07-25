<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleNature extends Model
{
    protected $fillable = ['sample_nature_code','sample_nature_name','remark','status'];

    public static function getSampleNature(){
      return  SampleNature::where('status',1)->get();
    }
}
