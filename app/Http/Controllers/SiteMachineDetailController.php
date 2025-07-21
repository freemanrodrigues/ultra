<?php

namespace App\Http\Controllers;

use App\Models\{MakeMst,SiteMaster,SiteMachineDetail};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class SiteMachineDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():view
    {
        
        $siteMachineDetails = SiteMachineDetail::all();    
        return view('masters.site-master-device.index',compact('siteMachineDetails'));
    }

    public function deviceBySiteMaster($id):view
    {
        
        $siteMachineDetails = SiteMachineDetail::where('site_master_id',$id)->get();    
        return view('masters.site-master-device.index',compact('siteMachineDetails'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sitemasters = SiteMaster::getAllSite();
        $sitedevices = MakeMst::all();
        return view('masters.site-master-device.create',compact('sitemasters','sitedevices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_master_id' => 'required|integer',
            'model_id' => 'required|integer',
            'machine_number'=>'required|string',
            'machine_code'=>'required|string',
        ]);

        try {
           // dd($validated);
           SiteMachineDetail::create($validated);
           // dd("Success");
            return redirect()->route('site-master-device.index')
                           ->with('success', 'Make created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Make: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SiteMachineDetail $siteMachineDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteMachineDetail $siteMachineDetail)
    {
        dd("Edit");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiteMachineDetail $siteMachineDetail)
    {
        dd("Update");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiteMachineDetail $siteMachineDetail)
    {
        dd("Delete");
    }
}
