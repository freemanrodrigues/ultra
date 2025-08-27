<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleNature extends Model
{
    protected $fillable = ['sample_nature_code','sample_nature_name','remark','status'];

    public static function getSampleNature(){
      return  SampleNature::where('status',1)->get();
    }

    public static function getSampleNatureArray() {
      $sample_nature = SampleNature::where('status', '1')->orderBy("sample_nature_name")->get(['id','sample_nature_name']);	
      //dd($sample_nature);
          $sample_natureArr = array();
      foreach($sample_nature as $k => $v) {
        $sample_natureArr[$v->id] = $v->sample_nature_name;
      }  
      return $sample_natureArr;	
	}   
}
