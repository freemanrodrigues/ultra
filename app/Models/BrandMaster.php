<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
class BrandMaster extends Model
{
    protected $fillable = ['brand_code','brand_name','status'];
   // use SoftDeletes;

}
