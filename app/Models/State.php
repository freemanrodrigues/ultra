<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    
    public static function getStateArray() {
		$country = State::where('status', '1')
		->orderBy("statename")
		->get();	
		
        $stateArr = array();
		foreach($country as $k => $v)
			$stateArr[$v->id] = $v->statename;
       return $stateArr;	
	}   
}
