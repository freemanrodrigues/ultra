<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BottleType extends Model
{
    protected $fillable = ['bottle_code','bottle_name','remark','status'];

    public static function getBottleType(){
        return  BottleType::where('status',1)->get();
      }
    public static function getBottleTypeArray() {
      $bottles = BottleType::where('status', '1')->orderBy("bottle_name")->get(['id','bottle_name']);	
      //dd($sample_nature);
          $bottleArr = array();
      foreach($bottles as $k => $v) {
        $bottleArr[$v->id] = $v->bottle_name;
      }  
      return $bottleArr;	
	}     
}
