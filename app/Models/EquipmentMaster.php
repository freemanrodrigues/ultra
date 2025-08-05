<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentMaster extends Model
{
    protected $fillable = [ 'equipment_name', 'make_model_id', 'serial_number', 'status' ];
}
