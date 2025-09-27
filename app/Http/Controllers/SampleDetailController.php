<?php

namespace App\Http\Controllers;

use App\Models\{BottleType,CustomerMaster,EquipmentAssignment,POTest, SampleDetail,SampleMaster,SampleNature,SampleType,MakeModelMaster,SampleDetailTestAssignment};
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

        $make_models =MakeModelMaster::getMakeModel();
        $makes = MakeModelMaster::select('make')->distinct()->pluck('make');
        //dd($makes);
        $sample = SampleMaster::where('id',$id)->with('customer', 'customer_site_masters.siteMaster')->first();  
        // fetch the sample details, if any
            $sample_details = SampleDetail::where('sample_id',$id)->get()->ToArray();

         //  dd($sample_details);
      // echo "<br> Customer  Id: ". $sample[0]->customer_id;
     //s  echo "<br> Customer Site Id: ". $sample[0]->customer_site_id;
     //  echo "<br> Customer Site Id: ". $sample[0]->customer_id;
        //die();

        $equipments = EquipmentAssignment::getSiteEquipmentList($sample->customer_site_id);
        $sample_natures = SampleNature::getSampleNatureArray();
        $sample_types = SampleType::getSampleTypeArray();
        $bottle_types = BottleType::getBottleTypeArray();

        return view('add-sample-details',compact('sample','equipments','sample_types','sample_natures','bottle_types','make_models','makes',
    'sample_details'));
        // ,'devices','sample_types','sample_natures','bottle_types'
    }
    // Delete 
    

    public function saveSampleDetials(Request $request)
    {
     //   dd($request->all());
        
       
        if(!empty($request['device_id'])) {
            $samples =  SampleMaster::where('id', $request->sample_id)->get();

            foreach($request['device_id'] as $k => $device_id) {

           
            
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
            if(empty($request->sd_id[$k])) {
            $customer =  CustomerMaster::getCountryId($samples[0]->customer_id);
                $smd = new SampleDetail();
                $smd->sample_id = $request->sample_id;
                $smd->equipment_assignments_id = $device_id;
                $smd->company_id  = $customer->company_id; 
                $smd->customer_id  = $samples[0]->customer_id;
                $smd->customer_site_id  = $samples[0]->customer_site_id;
                $smd->type_of_sample  = $request->sample_type[$k];
                $smd->nature_of_sample  = $request->nature_of_Sample[$k]??NULL;
                $smd->running_hrs  = $request->running_hrs[$k]??NULL;
                $smd->sub_asy_no  = $request->sub_asy_no[$k]??NULL;
                $smd->sub_asy_hrs  = $request->sub_asy_hrs[$k]??NULL;
                $smd->sampling_date  = $request->sampling_date[$k]??NULL;
                $smd->brand_of_oil  = $request->brand_of_oil[$k]??NULL;
                $smd->grade  = $request->grade[$k]??NULL;
                $smd->lube_oil_running_hrs  = $request->lube_oil_running_hrs[$k]??NULL;
                $smd->top_up_volume  = $request->top_up_volume[$k]??NULL;
                $smd->sump_capacity  = $request->sump_capacity[$k]??NULL;
                $smd->sampling_from  = $request->sampling_from[$k]??NULL;
           //     $smd->report_expected_date  = $request->report_expected_date[$k];
                $smd->qty  = $request->qty[$k]??NULL;
                $smd->bottle_types_id  = $request->type_of_bottle[$k]??NULL;
           //     $smd->problem  = $request->problem[$k];
           //     $smd->comments  = $request->comments[$k];
            //    $smd->customer_note  = $request->customer_note[$k];
                $smd->severity  = $request->severity[$k]??NULL;
                $smd->oil_drained  = $request->oil_drained[$k]??NULL;
           //     $smd->image  = $image_path;
           //     $smd->fir  = $request->fir[$k]??NULL;
          //      $smd->invoice  = $request->invoice[$k];
                $smd->save();


                // Assign the test for the Sample
                $po_id = $samples[0]->work_order;

               $test_lists = POTest::getTestList($po_id,  $request->sample_type[$k] );
        
            foreach($test_lists as $i => $test) {
 
                SampleDetailTestAssignment::create(['sample_details_id' =>$smd->id,'test_id' => $test->id]);
            }
        } else {
            //edit
        }
        }
         return redirect()->route('sample.index')
                           ->with('success', 'SampleDetails added successfully!');
    }
    }
    

 }
