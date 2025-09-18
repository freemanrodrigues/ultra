<?php

namespace App\Http\Controllers;

use App\Models\{CourierMaster,CompanyMaster,CustomerMaster,SampleMaster,SiteMaster,User};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;
use Pdf; // Use the Pdf Facade
use Carbon\Carbon; // For date handling

class SampleMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $samples = SampleMaster::with([
            'customer.company',
            'customer_site_masters.siteMaster',
            'customer_site_masters.contactAssignments.contactMaster'
        ])->get();
        $courier_mst = CourierMaster::getCourierArray();
        $company_mst = CompanyMaster::getCompanyArray();
        $customer_mst = CustomerMaster::getCustomerArray();
        $sitemaster = SiteMaster::getSiteMasterArray();
        $users = User::getUserArray();
       
        return view('sample-list',compact('samples','courier_mst','company_mst','customer_mst','users','sitemaster'));
    }

    public function create()
    {
        $courier_mst = CourierMaster::getCourierArray();
     //$courier_mst = array(1=>'Blue Dart',2=>'India Post',3=>'Hand Delivery');
        
        $customer_mst = CustomerMaster::getCustomerArray();
        
        return view('sample-form',compact('courier_mst','customer_mst'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lot_no' => 'required|string|max:15',
            'courier_id' => 'required|integer',
            'no_of_samples' => 'required|integer|max:255',
            'sample_date' => 'required|date',
            "pod_no" => 'required|string|max:24',
            "customer_site_id" => 'nullable|integer',    
          "customer_id" => 'nullable|integer',
      //  "company_id" => 'nullable|integer',
      //  "cus_mobile" => 'required|numeric',
      //  "cus_email" => 'required|email',
     //   "cus_site_contact_person_id" => 'nullable|integer',
    //    "cus_site_contact_mobile" => 'nullable|numeric',
    //    "cus_site_contact_email" => 'nullable|email',
        "expected_report_date" => 'nullable|date',
        "work_order_date" => 'nullable|date',
        "work_order" => 'nullable|string|max:24',
        "freight_charges" => ['nullable', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
        "additional_info" => 'nullable|string|max:255',
        "site_sample_dispacted_date" => 'nullable|date',
        "collection_center_sample_received_date" => 'date',
        "collection_center_sample_collected_date" => 'nullable|date',
        "lab_sample_received_date" => 'nullable|date',
        ]);
       
       
        try {
            //  dd($validated);
         //   $customer = CustomerMaster::getCountryId($request['customer_id']);
          //  $company_id = $customer->company_id;
            
            $sam = new SampleMaster();
           // $sam->lot_no = $request->lot_no;
            $sam->courier_id = $request->courier_id;
            $sam->no_of_samples = $request->no_of_samples;
            $sam->sample_date = $request->sample_date;
            $sam->pod_no = $request->pod_no;
            $sam->customer_site_id = $request->site_master_id;
            $sam->customer_id = $request->customer_id;
          //  $sam->company_id = $company_id;
         //   $sam->cus_mobile= $request->cus_mobile;
         //   $sam->cus_email	= $request->cus_email;
        //    $sam->cus_site_contact_person_id = $request->cus_site_contact_person_id;
         //   $sam->cus_site_contact_mobile = $request->cus_site_contact_mobile;
        //    $sam->cus_site_contact_email = $request->cus_site_contact_email;
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
    public function show(SampleMaster $sampleMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleMaster $sampleMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleMaster $sampleMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleMaster $sampleMaster)
    {
        //
    }

    public function getCustomerAddress(Request $request): JsonResponse
    {
        $customer = CustomerMaster::find($request->customer_id);
    
        if ($customer) {
            return response()->json($customer);
        }
    
        return response()->json(['error' => 'Customer not found'], 404);
    }
}
