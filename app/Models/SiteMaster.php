<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMaster extends Model
{
    protected $fillable = [ 'site_name', 'address', 'city',  'state',  'country',  'lat',  'long',  'status' ];
    

    public function customerSiteMasters()
    {
        return $this->hasMany(CustomerSiteMaster::class, 'site_master_id');
    }

    public function state_table()
    {
        return $this->belongsTo(State::class, 'state');
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
