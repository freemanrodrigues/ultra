<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleDetailTestAssignment extends Model
{
     protected $fillable = ['sample_details_id','test_id'];
	
     public static function getAssignedTestForSample($sample_details_id) {
       // return   SampleDetailTestAssignment::where( 'sample_details_id'=> $sample_details_id)->get();

     return  SampleDetailTestAssignment::query()->join('test_masters', 'test_masters.id', '=', 'sample_detail_test_assignments.test_id')
    ->where('sample_detail_test_assignments.sample_details_id', $sample_details_id)
    ->select('test_masters.test_name', 'test_masters.default_unit', 'test_masters.standard_test_rate', 'test_masters.tat_hours_default')
    ->get();
    }
}

