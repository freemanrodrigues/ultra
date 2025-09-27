<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentAssignment extends Model
{
    protected $fillable = [ 'equipment_id', 'company_id', 'customer_id', 'customer_site_id', 'customer_site_equiment_name', 'assigned_from', 'assigned_to', 'status', 'createdby_id' ];

    public function equipment()
    {
        return $this->belongsTo(EquipmentMaster::class, 'equipment_id', 'id');
    }

    public static function getSiteEquipmentList($id) {
		  return EquipmentAssignment::with('equipment')->where('customer_site_id',$id )->get();
	}
}
