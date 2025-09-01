<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleType extends Model
{
     protected $fillable = ['sample_type_name','description', 'status'];

    public static function getSampleType(){
       return  SampleType::where('status',1)->get();
    }


    public static function getSampleTypeArray() {
      $sample_type = SampleType::where('status', '1')->orderBy("sample_type_name")->get(['id','sample_type_name']);	
      //dd($sample_nature);
          $sample_typeArr = array();
      foreach($sample_type as $k => $v) {
        $sample_typeArr[$v->id] = $v->sample_type_name;
      }  
      return $sample_typeArr;	
	}   
}
