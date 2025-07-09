<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMaster extends Model
{
    protected $fillable = ['customer_name', 'display_name', 'company_id', 'gst_no', 'address', 'city', 'state', 'country', 'pincode', 'billing_cycle', 'credit cycle', 'group', 'sales_person_id', 'status'];

    public static function getAllCustomer() {
		return CustomerMaster::all();
	}

    public static function getCustomerArray() {
		$customer = CustomerMaster::where('status', '1')
		->orderBy("customer_name")
		->get(['id','customer_name']);	
		
        $customerArr = array();
		foreach($customer as $k => $v)
			$customerArr[$v->id] = $v->customer_name;
       return $customerArr;	
	} 

}
