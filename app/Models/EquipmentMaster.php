<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentMaster extends Model
{
    protected $fillable = [ 'equipment_name', 'make_model_id', 'serial_number', 'status' ];

    public function make_model()
    {
        return $this->belongsTo(MakeModelMaster::class, 'make_model_id');
    } 
}
