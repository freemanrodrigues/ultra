<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class POTestLine extends Model
{
    protected $table = 'po_test_lines';
    protected $fillable = ['po_master_id','sample_type_id','test_id','status'];

    public static function getTestList($po_id,$company_id, $sample_id ) {
        return  POTestLine::select('test_id')
        ->join('po_masters', 'po_test_lines.po_master_id', '=', 'po_masters.id')
        ->where('po_masters.id', $po_id)
        ->where('po_masters.party_id', $company_id)
        ->where('po_test_lines.sample_type_id', $sample_id)
        ->get();
    }
}
