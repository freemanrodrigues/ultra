<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelMaster extends Model
{
    protected $fillable = ['make_id','model','status'];


    public function make()
    {
        return $this->belongsTo(MakeMaster::class, 'make_id');
    } 
}
