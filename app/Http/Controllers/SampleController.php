<?php

namespace App\Http\Controllers;

use App\Models\{CompanyMaster,CourierMaster,CustomerMaster,Sample};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $samples = Sample::all();
       // dd($samples);
        return view('sample-list')->with(['samples' => $samples]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        $courier_mst = CourierMaster::getCourierArray();
        $company_mst = CompanyMaster::getCompanyArray();
        $customer_mst = CustomerMaster::getCustomerArray();
        
        return view('sample-form',compact('courier_mst','company_mst','customer_mst'));
    }
    public function sampleCopy():View
    {
        //die('copy');
        return view('sample2');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
      //  dd($request->all());
        $validated = $request->validate([
            'lot_no' => 'required|string|max:15',
            'courier_id' => 'required|integer|max:99',
            'no_of_samples' => 'required|integer|max:255',
            'sample_date' => 'required|date',
            "pod_no" => 'required|string|max:24',
          "customer" => 'string',
        "company_id" => 'string',
        "cus_mobile" => 'required|numeric',
        "cus_email" => 'required|email',
        "cus_site_contact_person_id" => 'required|string',
        "cus_site_contact_mobile" => 'required|numeric',
        "cus_site_contact_email" => 'required|email',
        "expected_report_date" => 'required|date',
        "work_order_date" => 'required|date',
        "work_order" => 'required|string|max:24',
        "freight_charges" => ['required', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
        "additional_info" => 'required|string|max:255',
        "site_sample_dispacted_date" => 'required|date',
        "collection_center_sample_received_date" => 'date',
        "collection_center_sample_collected_date" => 'required|date',
        "lab_sample_received_date" => 'required|date',
        ]);
       
       
        try {
            //  dd($validated);
             
            $sam = new Sample();
            $sam->lot_no = $request->lot_no;
            $sam->courier_id = $request->courier_id;
            $sam->no_of_samples = $request->no_of_samples;
            $sam->sample_date = $request->sample_date;
            $sam->pod_no = $request->pod_no;
            $sam->customer_id = 1;//$request->customer_id;
            $sam->company_id = $request->company_id;
            $sam->cus_mobile= $request->cus_mobile;
            $sam->cus_email	= $request->cus_email;
            $sam->cus_site_contact_person_id = $request->cus_site_contact_person_id;
            $sam->cus_site_contact_mobile = $request->cus_site_contact_mobile;
            $sam->cus_site_contact_email = $request->cus_site_contact_email;
            $sam->expected_report_date = $request->expected_report_date;
            $sam->work_order_date = $request->work_order_date;
            $sam->work_order = $request->work_order;
            $sam->freight_charges = $request->freight_charges;
            $sam->additional_info = $request->additional_info;
            $sam->site_sample_dispacted_date = $request->site_sample_dispacted_date;
            $sam->collection_center_sample_received_date = $request->collection_center_sample_received_date;
            $sam->collection_center_sample_collected_date = $request->collection_center_sample_received_date;
            $sam->lab_sample_received_date = $request->lab_sample_received_date;
            $sam->save();

              return redirect()->route('sample.index')
                             ->with('success', 'SampleNature created successfully!');
          } catch (\Exception $e) {
              dd("Fail". $e->getMessage());
              return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error creating SampleNature: ' . $e->getMessage());
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sample $sample)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sample $sample)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sample $sample)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sample $sample)
    {
        //
    }
}
