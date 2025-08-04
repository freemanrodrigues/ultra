<?php

namespace App\Http\Controllers;


use App\Models\{Country,CompanyMaster, ContactMaster, CustomerMaster,CustomerSiteMaster,SiteContact,SiteMaster,User};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;

class CustomerSiteMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        //dd("List Customer Sites");
        $query = CustomerSiteMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('site_customer_code', 'like', "%{$search}%")
                  ->orWhere('site_customer_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $siteMasters = $query->paginate(10)->appends($request->query());
        $customers = CustomerMaster::getCustomerArray();;
        return view('masters.customer-site-masters.index', compact('siteMasters','customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
       // dd("Create Customer Sites");
        $countries = Country::all();
        $companies = CompanyMaster::all();
        $site_masters = SiteMaster::all();
        $customers = CustomerMaster::all();
        return view('masters.customer-site-masters.create', compact('countries','companies','customers','site_masters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       // dd("Save Customer Sites");
        //dd($request->all());
        $validated = $request->validate([
      //      'site_code' => 'required|string|max:50|unique:site_masters,site_code',
      
            'site_customer_code' => 'required',
            'site_master_id' => 'required',
            "site_customer_name" => 'string',
        //    "contact_type" => 'string',
            "company_id" => 'integer',
            "customer_id" => 'integer',
            "address" => 'string',
            "city" => 'string',
            "state" => 'string',
            "country" => 'string',
            "pincode" => 'string',
            "lat" => 'string',
            "long" => 'string',
            "customer_type" => 'string',
            'status' => 'required|in:1,0'
        ]);

   /*    company_id	customer_id	site_master_id	site_customer_code	site_customer_name	address	city	pincode	state	country	lat	long	status					 */
   /*
   "customer_site_code" => "Rus"
   "customer_site_name_id" => "1"
   "site_display_name" => "Virar"
   "customer_id" => "270"
   "address" => "Virar, Maharashtra, India"
   "city" => "Virar"
   "state" => "MH"
   "country" => "India"
   "CountryCode" => "IN"
   "pincode" => "401303"
   "lat" => "19.4563596"
   "long" => "72.79246119999999"
   "status" => "1" */
        try {
           // $data = $request->validate();
			
            $customer = CustomerMaster::getCountryId($request['customer_id']);
            $validated['company_id'] = $customer[0]->company_id;
            CustomerSiteMaster::create($validated);
            return redirect()->route('customer-site-masters.index')
                           ->with('success', 'Customer Site Master created successfully!');
        } catch (\Exception $e) {
            dd("<br>Error : ".$e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Site Master: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerSiteMaster $customerSiteMaster)
    {
        dd("Show Customer Sites");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerSiteMaster $customerSiteMaster)
    {
        dd("Edit Customer Sites");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerSiteMaster $customerSiteMaster)
    {
        dd("Update Customer Sites");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerSiteMaster $customerSiteMaster)
    {
        dd("Destroy Customer Sites");
    }

    public function  assignUsers($id):View
    {
       // die("Id:".$id);
     //  $siteMaster = SiteMaster::where('id' , $id)->get();
       
       $users = ContactMaster::where('company_id' , $id)->get();
       //dd($users);
       return view('masters.customer-site-masters.assign-contact', compact('siteMaster','users'));
    }
}
