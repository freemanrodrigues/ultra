<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BottleType extends Model
{
    protected $fillable = ['bottle_code','bottle_name','remark','status'];

    public static function getBottleType(){
        return  BottleType::where('status',1)->get();
      }
}
