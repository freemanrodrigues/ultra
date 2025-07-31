<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public static function getCountryArray() {
		$country = Country::where('status', '1')
		->orderBy("countryname")
		->get();	
		
        $countryArr = array();
		foreach($country as $k => $v)
			$countryArr[$v->id] = $v->countryname;
       return $countryArr;	
	}   
}
