<?php

namespace App\Http\Controllers;

use App\Models\{Country,CompanyMaster,ContactMaster,CustomerMaster,CustomerSiteMaster,State};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\{DB,Log};
use Illuminate\Validation\Rule;

class CustomerMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        
        $query = CustomerMaster::query();

        $query->leftJoin(
            'customer_site_masters', 
            'customer_masters.id', 
            '=', 
            'customer_site_masters.customer_id'
        )
        ->select('customer_masters.*', 'customer_site_masters.*', 'customer_masters.state', 'customer_masters.id');
        
        if ($request->filled('company_id')) {
            $query->where('customer_masters.id', $request->get('company_id'));
        }elseif ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('customer_masters.gst_no', 'like', "%{$search}%")
                  ->orWhere('customer_masters.customer_name', 'like', "%{$search}%");
            });
        }	

        // Filter by status
        if ($request->filled('status')) {
            $query->where('customer_masters.status', $request->get('status'));
        } else {
            $query->where('customer_masters.status', 1);
        }
       
        // Sort by
        $sortBy = $request->get('sort_by', 'customer_masters.customer_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(10)->appends($request->query());
        //dd($customers);
        $states = State::getStateArray();
        return view('masters.customer.index',compact('customers', 'states'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
      //   dd("Create");
     //   $customer = CustomerMaster::all();
     $companies = CompanyMaster::getCompanyArray();
     $countries = Country::getCountryArray();
     $states = State::getStateArray();
    return view('masters.customer.create',compact('countries','companies','states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request->all());

       $request->validate([
        'b2c_customer' => ['nullable', 'boolean'],
        'customer_name' => 'required|string|max:255|unique:customer_masters,customer_name',
        'gst_no' => [
            'nullable',                              // Allows null/empty values
            'required_unless:b2c_customer,1',        // Required if b2c_customer is not 1
            'prohibited_if:b2c_customer,1',          // Must be empty if b2c_customer is 1
            'regex:/^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}Z[A-Z\d]{1}$/',
        ],
        "address" => 'required|string|max:255',
        "city" => 'required|string|max:100',
        "state" => 'required|integer',
        "country" => 'required|integer|min:1|max:999',
        "pincode" => 'required|string|max:6',
        //   "email" => 'required|email',
        //   "phone" => 'required',
        //  "billing_cycle" => 'date',
        //  "credit_cycle" => 'required|date',
        //    "sales_person_id" => null
        'status' => 'required|in:1,0'
    ]); 

        try {
            //  dd($validated);
            
          
                try {

                    if (empty($request->gst_no)) {
                        // GST not provided → No PAN
                        $cid = CompanyMaster::createCompany([$request->customer_name, null]);
                    } else {
                        // Extract PAN from GST
                        $pan = substr($request->gst_no, 2, 10);
                    
                        $company = CompanyMaster::where('pancard', $pan)->first();
                    
                        $cid = $company
                            ? $company->id
                            : CompanyMaster::createCompany([$request->customer_name, $pan]);
                    }
                    
                } catch (\Exception $e) {
                    //dd($e->getMessage());
                    Log::error('An error occurred', ['exception' => $e->getMessage()]); // Log an error
                    return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error creating company ');
                }
           
            
            $cm = new CustomerMaster();
            $cm->customer_name = $request->customer_name;
            
            $cm->b2c_customer = $request->b2c_customer??null;
            $cm->division = $request->division??null;
            $cm->company_id = $cid;
            $cm->gst_no = $request->gst_no??null;;
            $cm->address = $request->address;
            $cm->address1 = $request->address2??null;
            $cm->city = $request->city??null;
            $cm->state = $request->state??null;
            $cm->country= $request->country??null;
            $cm->pincode	= $request->pincode??null;
            $cm->gst_state_code = $request->state_code??null;
        //    $cm->email	= $request->email;
         //   $cm->mobile	= $request->mobile;
            $cm->is_billing = $request->is_billing??null;;
            $cm->billing_cycle = $request->billing_cycle??null;;
            $cm->credit_cycle = $request->credit_cycle??null;;
            $cm->group = $request->group??null;;
            $cm->status = $request->status??null;
            $cm->save();

                return redirect()->route('customer.show', $cm->id)
                ->with('success', [
                    'text' => 'Customer Created Successfully!',
                    'link' => route('customer-site-masters.create',['customer_id'=>$cm->id]), // link to customer details
                    'link_text' => ' Next Step : Assign Customer Site'
                ]);
                             
          } catch (\Exception $e) {
             // dd("Fail". $e->getMessage());
            if($e->getCode() == 23000) {
                   $err_msg = 'This GST number is already registered. Please use a different one.'; 
            } else {
                $err_msg = $e->getMessage();
            }
           
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating customer: ' . $err_msg);
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerMaster $customer)
    {
        $companies = CompanyMaster::getCompanyArray();
        $contactmasters =ContactMaster::where('company_id',$customer->company_id )->get();
        $customerSiteMasters = CustomerSiteMaster::where('customer_id',$customer->id )->get();
        $countries = Country::getCountryArray();
        $states = State::getStateArray();
       // dd($contactmasters);
        return view('masters.customer.show', compact('customer','companies','contactmasters','countries','states','customerSiteMasters'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerMaster $customer)
    {
        $companies = CompanyMaster::where('id',$customer->company_id)->first('pancard');
        //$companies->pancard;
        $countries = Country::getCountryArray();
        $states = State::getStateArray();
        return view('masters.customer.edit',compact('countries','companies','states','customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerMaster $customer)
    {
         //dd($request->all());
       
        $request->validate([
            'b2c_customer' => ['nullable', 'boolean'],
            'customer_name' => 'required|string|max:255|unique:customer_masters,customer_name,' . $customer->id,
            'gst_no' => [
                'nullable',                              // Allows null/empty values
                'required_unless:b2c_customer,1',        // Required if b2c_customer is not 1
                'prohibited_if:b2c_customer,1',          // Must be empty if b2c_customer is 1
                'regex:/^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}Z[A-Z\d]{1}$/',
            ],
            "address" => 'required|string|max:255',
            "city" => 'required|string|max:100',
            "state" => 'required|integer',
            "country" => 'required|integer|min:1|max:999',
            "pincode" => 'required|string|max:6',
            //   "email" => 'required|email',
            //   "phone" => 'required',
            //  "billing_cycle" => 'date',
            //  "credit_cycle" => 'required|date',
            //    "sales_person_id" => null
            'status' => 'required|in:1,0'
        ]); 
// account code - integer, account category, account name

        try {
            //  dd($validated);
            /*
            $company = CompanyMaster::where('pancard', substr($request->gst_no, 2, 10))->get();
            // dd($company);
           //  dd($company->id);
             if(count($company)== 0) {
                 $arr = array(0 =>$request->customer_name,1 =>substr($request->gst_no, 2, 10));
                 $cid = CompanyMaster::createCompany($arr);
             } else {
                 $cid = $company[0]->id;
             }*/
           //  dd($validated);
            // $customer->update($validated);
             
             
             $cm = CustomerMaster::find($customer->id);
             $cm->customer_name = $request->customer_name;
            $cm->b2c_customer = $request->b2c_customer??null;
             $cm->division = $request->division??null;
            // $cm->company_id = $cid;
             $cm->gst_no = $request->gst_no??null;
             $cm->address = $request->address??null;
            $cm->address1 = $request->address2??null;
             $cm->city = $request->city??null;
             $cm->state = $request->state??null;
             $cm->country= $request->country??null;
             $cm->pincode	= $request->pincode??null;
             $cm->gst_state_code = $request->state_code??null;
             $cm->is_billing = $request->billing_address??null;
             $cm->billing_cycle = $request->billing_cycle??null;
             $cm->credit_cycle = $request->credit_cycle??null;
             $cm->group = $request->group??null;
             $cm->status =$request->status??null;
             $cm->save();
             
             
        
              return redirect()->route('customer.show',$customer->id)
                             ->with('success', [
                                'text' => 'Customer updated successfully!',
                                'link' => route('customer-site-masters.create',['customer_id'=>$customer->id]), // link to customer details
                                'link_text' => ' Next Step : Assign Customer Site'
                            ]);
          } catch (\Exception $e) {
                if($e->getCode() == 23000) {
                    $err_msg = 'This GST number is already registered. Please use a different one.'; 
                } else {
                    $err_msg = $e->getMessage();
                }
              return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error updating customer: ' . $err_msg);
          }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerMaster $customer)
    {
        try {
            CustomerMaster::where('id', $customer->id)->update(['status' => 0]);
            return redirect()->route('customer.index')
                            ->with('success', 'CustomerMaster deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting CustomerMaster: ' . $e->getMessage());
        }
    }

    public function autoSuggestCustomer(Request $request)
    {
        $query = $request->input('query');

        // Basic validation (optional but recommended)
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]); // Return empty array if query is too short
        }

        // Fetch data from your database
        // Replace 'YourModel' and 'name' with your actual model and column name
        $suggestions = CustomerMaster::where('customer_name', 'LIKE', '%' . $query . '%')
                                ->select('id', 'customer_name as name') // Select only necessary columns
                                ->limit(10) // Limit the number of suggestions
                                ->get();

        return response()->json($suggestions);
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

    public function autoSuggestCustomer1(Request $request)
    {
        $query = $request->input('query');

        // Basic validation (optional but recommended)
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]); // Return empty array if query is too short
        }

        // Fetch data from your database
        // Replace 'YourModel' and 'name' with your actual model and column name
        
        $sql = "SELECT  customer_masters.id as cus_mas_id,site_name,customer_name, division, customer_masters.group, customer_masters.status, statename
        FROM customer_masters 
        LEFT JOIN customer_site_masters   ON customer_masters.id = customer_site_masters.customer_id LEFT JOIN site_masters ON customer_site_masters.site_master_id = site_masters.id   LEFT JOIN states on states.id = customer_site_masters.state
        WHERE    customer_masters.customer_name LIKE '%".$query."%'  or site_masters.site_name LIKE '%".$query."%'";

        $customers  = DB::select($sql);
            

        return response()->json($customers);
    }
}
