<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMaster extends Model
{
	protected $fillable = ['company_name','pancard','status'];

	public static function getAllCompany() {
		return CompanyMaster::all();
	}

    public static function getCompanyArray() {
		$company = CompanyMaster::where('status', '1')
		->orderBy("company_name")
		->get();	
		
        $companyArr = array();
		foreach($company as $k => $v)
			$companyArr[$v->id] = $v->company_name;
       return $companyArr;	
	} 

	public static function createCompany($array) {
		
		$company =	CompanyMaster::create(['company_name' =>$array[0],'pancard' =>substr($array[1], 2, 12),'status' =>1]);
		return $company->id;
	}

}
