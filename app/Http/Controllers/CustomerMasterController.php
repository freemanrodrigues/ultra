<?php

namespace App\Http\Controllers;

use App\Models\{Country,CompanyMaster,CustomerMaster,SiteMaster};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class CustomerMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $customers = CustomerMaster::all();
        return view('masters.customer.index')->with(['customers' => $customers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
       // dd("Create");
     //   $customer = CustomerMaster::all();
         $companies = CompanyMaster::getCompanyArray();
         $countries = Country::getCountryArray();
         //dd($countries);
        return view('masters.customer.create',compact('countries','companies'));
    }
/*
    public function createCustomer():View
    {
       // dd("Create");
     //   $customer = CustomerMaster::all();
     $states = array();
     $salesPersons = array();
     $companies = CompanyMaster::getCompanyArray();
     
        return view('masters.customer.create-customer')->with(['companies' => $companies, 'states' => $states, 'salesPersons' => $salesPersons]);
    }
*/
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       // dd($request->all());
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            "company_id" => 'required|string',
            'gst_no' => 'required|string|max:15',
            "address" => 'required|string|max:255',
            "city" => 'required|string|max:100',
            "state" => 'required|string|max:100',
            "country" => 'required|string|max:100',
            "pincode" => 'required|string|max:24',
            "email" => 'required|email',
            "phone" => 'required',
          //  "billing_cycle" => 'date',
          //  "credit_cycle" => 'required|date',
            //    "sales_person_id" => null
            'status' => 'required|in:1,0'
        ]);
// account code - integer, account category, account name

        try {
            //  dd($validated);
             if(empty($request->companyid_val)) {
                $arr = array(0 =>$request->company_id,1 =>substr($request->gst_no, 2, 12));
                $cid = CompanyMaster::createCompany($arr);
             } else {
                $cid = $request->company_id;
             }
            $cm = new CustomerMaster();
            $cm->customer_name = $request->customer_name;
            $cm->display_name = $request->display_name;
            $cm->company_id = $cid;
            $cm->gst_no = $request->gst_no;
            $cm->address = $request->address;
            $cm->city = $request->city;
            $cm->state = $request->state;
            $cm->country= $request->country;
            $cm->pincode	= $request->pincode;
            $cm->email	= $request->email;
            $cm->mobile	= $request->mobile;
            $cm->billing_cycle = $request->billing_cycle;
            $cm->credit_cycle = $request->credit_cycle;
            $cm->status = 1;
            $cm->save();

              return redirect()->route('customer.index')
                             ->with('success', 'Customer created successfully!');
                             
          } catch (\Exception $e) {
              //dd("Fail". $e->getMessage());
              return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error creating customer: ' . $e->getMessage());
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerMaster $customerMaster)
    {
        die("Display Details");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerMaster $customer): View
    {
       
        $companies = CompanyMaster::getCompanyArray();
 
        return view('masters.customer.edit', compact('customer','companies'));
       
       

    }
 //
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerMaster $customerMaster)
    {
        dd($request->all());
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            "company_id" => 'required|string',
            'gst_no' => 'required|string|max:15',
            "address" => 'required|string|max:255',
            "city" => 'required|string|max:100',
            "state" => 'required|string|max:100',
            "country" => 'required|string|max:100',
            "pincode" => 'required|string|max:24',
          //  "billing_cycle" => 'date',
          //  "credit_cycle" => 'required|date',
            //    "sales_person_id" => null
            'status' => 'required|in:1,0'
        ]);
// account code - integer, account category, account name

        try {
            //  dd($validated);
             if(empty($request->companyid_val)) {
                $arr = array(0 =>$request->company_id,1 =>substr($request->gst_no, 2, 12));
                $cid = CompanyMaster::createCompany($arr);
             } else {
                $cid = $request->company_id;
             }
            $id = $request->id;
            $cm = CustomerMaster::find($id);
            $cm->customer_name = $request->customer_name;
            $cm->display_name = $request->display_name;
            $cm->company_id = $cid;
            $cm->gst_no = $request->gst_no;
            $cm->address = $request->address;
            $cm->city = $request->city;
            $cm->state = $request->state;
            $cm->country= $request->country;
            $cm->pincode	= $request->pincode;
            $cm->billing_cycle = $request->billing_cycle;
            $cm->credit_cycle = $request->credit_cycle;
            $cm->status = 1;
            $cm->save();

              return redirect()->route('customer.index')
                             ->with('success', 'Customer updated successfully!');
          } catch (\Exception $e) {
              dd("Fail". $e->getMessage());
              return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error updating customer: ' . $e->getMessage());
          }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerMaster $customerMaster)
    {
        //
    }

    public function getCustomerAddress(Request $request)
    {
        $request->validate([
            'customerid' => 'required|integer',
        ]);

        
        $customer = CustomerMaster::where('id', $request->customerid)->first();
        $sitemaster = SiteMaster::getSite($request->customerid);
        
        if ($customer) {
            return response()->json([
                'customer' => $customer, 
                'sitemaster' => $sitemaster, 
            ]);
        }
        return response()->json([]);
    }
}
