<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMachineDetail extends Model
{
    protected $fillable = ['site_master_id', 'model_id', 'machine_number', 'machine_code'];

    public static function getSiteDeviceList($id) {
		return SiteMachineDetail::where('site_master_id',$id )->get();
	}

}
