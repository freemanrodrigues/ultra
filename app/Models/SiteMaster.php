<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMaster extends Model
{
    protected $fillable = ['site_code', 'site_name', 'site_display_name', 'email',  'contact_type',  'company_id',  'customer_id',  'address',  'address1',  'city',  'state',  'country',  'lat',  'long',  'customer_type', 'status' ];
    
    public static function getSite($customerid){
        return SiteMaster::where('customer_id',$customerid )->get();
    }

    public static function getAllSite(){
        return SiteMaster::where('status',1 )->get();
    }

    public static function getSiteMasterArray(){
        $sitemaster = SiteMaster::where('status', '1')
		->orderBy("site_name")
		->get(['id','site_name']);	
		//dd($user);
        $sitemasterArr = array();
		foreach($sitemaster as $k => $v)
			$sitemasterArr[$v->id] = $v->site_name;
       return $sitemasterArr;	
    }
}
