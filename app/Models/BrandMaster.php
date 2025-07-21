<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
class BrandMaster extends Model
{
    protected $fillable = ['brand_code','brand_name','status'];
   // use SoftDeletes;
    public static function getAllBrand() {
        return BrandMaster::all();
    }

    public static function getBrandArray() {
        $company = BrandMaster::where('status', '1')
        ->orderBy("brand_name")
        ->get();	
        
        $brandArr = array();
        foreach($company as $k => $v)
            $brandArr[$v->id] = $v->brand_name;
        return $brandArr;	
    }  
}
