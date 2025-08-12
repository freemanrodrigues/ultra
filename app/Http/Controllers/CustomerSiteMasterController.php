<?php

namespace App\Http\Controllers;


use App\Models\{Country,CompanyMaster, ContactMaster, CustomerMaster,CustomerSiteMaster,State,SiteContact,SiteMaster,User};
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
        if (isset($_GET['customer_id']) && !empty(isset($_GET['customer_id']))) {
           $query->where('customer_id', $_GET['customer_id']);
        }
        
        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customerSiteMasters = $query->paginate(10)->appends($request->query());
        $customers = CustomerMaster::getCustomerArray();;
        return view('masters.customer-site-masters.index', compact('customerSiteMasters','customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
       // dd("Create Customer Sites");

        $companies = CompanyMaster::orderBy('company_name')->get();
        $site_masters = SiteMaster::orderBy('site_name')->get(['id','site_name','city']);
        $customers = CustomerMaster::orderBy('customer_name')->get(['id','customer_name']);
        $countries = Country::getCountryArray();
        $states = State::getStateArray();
        $select_customer = null;
        if ($customerId = request('customer_id')) {
            $select_customer = CustomerMaster::find($customerId);
        }
        return view('masters.customer-site-masters.create', compact('countries','companies','customers','site_masters','states','select_customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //dd("Save Customer Sites");
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
            "lat" => 'nullable|string',
            "long" => 'nullable|string',
            "customer_type" => 'string',
                'status' => 'required|in:1,0'
            ]);


        try {
           // $data = $request->validate();
			
            $customer = CustomerMaster::getCountryId($request['customer_id']);
            $validated['company_id'] = $customer[0]->company_id;
            CustomerSiteMaster::create($validated);
            
            return redirect()->route('customer-site-masters.index')
            ->with('success', [
                'text' => 'Customer Site Master created successfully!',
                'link' => route('contacts-masters.create',['company_id'=>$customer[0]->company_id]), // link to customer details
                'link_text' => ' Next Step : Add Contact'
            ]);               
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
       
       
       
        $companies = CompanyMaster::all();
        $site_masters = SiteMaster::all();
        $customers = CustomerMaster::all();
        $countries = Country::getCountryArray();
        $states = State::getStateArray();
        return view('masters.customer-site-masters.edit', compact('countries','companies','customers','site_masters','customerSiteMaster','states'));

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

    public function  assignContact($id):View
    {
       // die("Id:".$id);
       $siteMaster = SiteMaster::where('id' , $id)->get();
       
       $users = ContactMaster::where('company_id' , $id)->get();
       //dd($users);
       return view('masters.customer-site-masters.assign-contact', compact('siteMaster','users'));
    }

    
}
