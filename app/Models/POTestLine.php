<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class POTestLine extends Model
{
    protected $table = 'po_test_lines';
    protected $fillable = ['po_id','sample_type_id','test','status'];
}
