<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentComponent extends Model
{
    protected $fillable = [ 'equipment_id', 'component_name', 'component_serial_number', 'component_type', 'assigned_from', 'assigned_to', 'createdby_id' ];
}
