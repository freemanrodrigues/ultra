<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMaster extends Model
{
	protected $fillable  = ['customer_name' ,'site' ,'division' ,'company_id' ,'b2c_customer' ,'gst_no' ,'gst_state_code' ,'address' ,'address1' ,'city' ,'state' ,'country' ,'pincode' ,'is_billing' ,'landline' ,'billing_cycle' ,'credit_cycle' ,'group' ,'status' ,'account_category'];

	public function customerSiteMasters()
	{
	return $this->hasMany(CustomerSiteMaster::class);
	}
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
	
	public static function getCountryId($id) {
		return CustomerMaster::where('id',$id)->get(['company_id']);
	}

	public static function getCustomerIdByCompanyId($id) {
		return CustomerMaster::where('company_id',$id)->value('id');
	}
}
