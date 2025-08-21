<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierMaster extends Model
{
    protected $fillable = ['courier_code', 'courier_name',  'status'];

    public static function getAllCourier() {
		return CourierMaster::all();
	}

    public static function getCourierArray() {
		$courier = CourierMaster::where('status', '1')
		->orderBy("courier_name")
		->get();	
		$courierArr = array();
		foreach($courier as $k => $v)
			$courierArr[$v->id] = $v->courier_name;
       return $courierArr;	
	}
}
