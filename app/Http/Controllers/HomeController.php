<?php

namespace App\Http\Controllers;
use App\Models\{BottleType,CustomerMaster,EquipmentAssignment,POTest, SampleDetail,SampleMaster,SampleNature,SampleType,MakeModelMaster,SampleDetailTestAssignment};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController
{

    public function dashboard(Request $request)
    {
        $dt = date('Y-m-d H:i:s', strtotime('last 15 days'));
        $dt1 = date('Y-m-d H:i:s', strtotime('last 2 days'));
        $sampledetials_count = SampleDetail::where('created_at', '>=',$dt1)->count();
        $sample_lot = SampleMaster::whereDate('created_at','>=', $dt)->count();
        $total_samples = SampleMaster::whereDate('created_at','>=', $dt)->sum('no_of_samples');
        $data = ['sample_lot' => $sample_lot, 'sampledetials_count' => $sampledetials_count, 'total_samples' => $total_samples];
       // dd($data);
        if (Auth::check()) { 
            return view('dashboard', compact('data'));
        } else{ return view('login');
        }
    } 
}
