<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeMaster extends Model
{
    protected $fillable = ['grade_code','grade_name','remark','status'];
}
