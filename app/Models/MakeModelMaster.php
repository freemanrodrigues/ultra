<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakeModelMaster extends Model
{
    protected $fillable = [ 'model','description','status','make' ];


    public static function getMakeModel()
    {
        return MakeModelMaster::all();
        
    }
}
