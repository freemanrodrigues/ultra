<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleOilType extends Model
{
    protected $fillable = ['sample_oil_type_code','sample_oil_type_name','remark','mis_group', 'status'];

    public static function getSampleType(){
       return  SampleOilType::where('status',1)->get();
    }

    public static function getSampleOilTypeArray() {
      $sample_oil_type = SampleOilType::where('status', '1')->orderBy("sample_oil_type_name")->get(['id','sample_oil_type_name']);	
      //dd($sample_nature);
          $sample_oil_typeArr = array();
      foreach($sample_oil_type as $k => $v) {
        $sample_oil_typeArr[$v->id] = $v->sample_oil_type_name;
      }  
      return $sample_oil_typeArr;	
	}   
}
