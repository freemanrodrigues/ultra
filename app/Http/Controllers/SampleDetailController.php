<?php

namespace App\Http\Controllers;

use App\Models\{EquipmentAssignment,SampleDetail,SampleMaster};
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleDetailController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleDetail $sampleDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleDetail $sampleDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleDetail $sampleDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleDetail $sampleDetail)
    {
        //
    }

    public function addSampleDetials($id):View
    {
        $sample = SampleMaster::where('id',$id)->get();
        $equipments = EquipmentAssignment::getSiteEquipmentList($sample[0]->site_master_id);
       // $sample_natures = SampleNature::getSampleNature();
      //  $sample_types = SampleType::getSampleType();
      //  $bottle_types = BottleType::getBottleType();
        
        return view('add-sample-details',compact('sample','devices','sample_types','sample_natures','bottle_types'));
    }
    
    public function saveSampleDetials(Request $request)
    {
       // dd($request->all());
        
       
        if(!empty($request['device_id'])) {
            foreach($request['device_id'] as $k => $device_id){

            $samples =  Sample::where('id', $request->sample_id)->get();
            
            if(!empty($request->image[$k])) {
               
                $image = $request->image[$k];
                $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $uploadPath = 'images/uploads'; // This will be storage/app/public/images/uploads
                $image_path = $image->storeAs($uploadPath, $fileName, 'public');
        
                // 6. Get the public URL for display
               // $imageUrl = Storage::url($path); 
            } else {
                $image_path = '';
            }
            if(!empty($request->invoice[$k])) {
                
            }
               $smd = new SampleDetail();
                $smd->sample_id = $request->sample_id;
                $smd->device_id = $device_id;
                $smd->company_id  = $samples[0]->company_id;
                $smd->customer_id  = $samples[0]->customer_id;
                $smd->sitemaster_id  = $samples[0]->site_master_id;
                $smd->type_of_sample  = $request->sample_type[$k];
                $smd->nature_of_sample  = $request->nature_of_Sample[$k];
                $smd->running_hrs  = $request->running_hrs[$k];
                $smd->sub_asy_no  = $request->sub_asy_no[$k];
                $smd->sub_asy_hrs  = $request->sub_asy_hrs[$k];
                $smd->sampling_date  = $request->sampling_date[$k];
                $smd->brand_of_oil  = $request->brand_of_oil[$k];
                $smd->grade  = $request->grade[$k];
                $smd->lube_oil_running_hrs  = $request->lube_oil_running_hrs[$k];
                $smd->top_up_volume  = $request->top_up_volume[$k];
                $smd->sump_capacity  = $request->sump_capacity[$k];
                $smd->sampling_from  = $request->sampling_from[$k];
                $smd->report_expected_date  = $request->report_expected_date[$k];
                $smd->qty  = $request->qty[$k];
                $smd->bottle_types_id  = $request->type_of_bottle[$k];
                $smd->problem  = $request->problem[$k];
                $smd->comments  = $request->comments[$k];
                $smd->customer_note  = $request->customer_note[$k];
                $smd->severity  = $request->severity[$k];
                $smd->oil_drained  = $request->oil_drained[$k];
                $smd->image  = $image_path;
                $smd->fir  = $request->fir[$k]??NULL;
                $smd->invoice  = $request->invoice[$k];
                $smd->save();
            }
            return redirect()->route('sample.index')
                           ->with('success', 'SampleDetails added successfully!');
        }

        
    }
}
