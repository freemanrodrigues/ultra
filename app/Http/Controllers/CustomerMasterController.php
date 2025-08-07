<?php

namespace App\Http\Controllers;

use App\Models\{Country,CompanyMaster,CustomerMaster,State};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
class CustomerMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        
        $query = CustomerMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('gst_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }	

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        } else {
            $query->where('status', 1);
        }
       
        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(10)->appends($request->query());
        
        return view('masters.customer.index',compact('customers'));
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
        "state" => 'required|integer|max:100',
        "country" => 'required|integer|max:100',
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
                $company = CompanyMaster::where('pancard', substr($request->gst_no, 2, 10))->get();
               // dd($company);
              //  dd($company->id);
                if(count($company)== 0) {
                    $arr = array(0 =>$request->customer_name,1 =>substr($request->gst_no, 2, 10));
                    $cid = CompanyMaster::createCompany($arr);
                } else {
                    $cid = $company[0]->id;
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
            $cm->is_billing = $request->billing_address??null;;
            $cm->billing_cycle = $request->billing_cycle??null;;
            $cm->credit_cycle = $request->credit_cycle??null;;
            $cm->status = $request->status??null;
            $cm->save();

            return redirect()->route('customer.index')
                             ->with('success', 'Customer created successfully!');
                             
          } catch (\Exception $e) {
             // dd("Fail". $e->getMessage());
            // 1062
            if($e->getCode() == 1062) {
                   $err_msg = 'Duplicate Record'; 
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
        return view('masters.customer.show', compact('customer','companies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerMaster $customer)
    {
        $companies = CompanyMaster::getCompanyArray();
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
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255|unique:customer_masters,customer_name,' . $customer->id,
            "company_id" => 'required|string',
            'gst_no' => 'nullable|string|max:15',
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
            $company = CompanyMaster::where('pancard', substr($request->gst_no, 2, 10))->get();
            // dd($company);
           //  dd($company->id);
             if(count($company)== 0) {
                 $arr = array(0 =>$request->customer_name,1 =>substr($request->gst_no, 2, 10));
                 $cid = CompanyMaster::createCompany($arr);
             } else {
                 $cid = $company[0]->id;
             }
           //  dd($validated);
             $customer->update($validated);
           /*  
             $id = $request->id;
             $cm = CustomerMaster::find($id);
             $cm->customer_name = $request->customer_name;
             $cm->division = $request->division??null;
             $cm->company_id = $cid;
             $cm->gst_no = $request->gst_no??null;
             $cm->address = $request->address??null;
            $cm->address1 = $request->address2??null;
             $cm->city = $request->city??null;
             $cm->state = $request->state??null;
             $cm->country= $request->country??null;
             $cm->pincode	= $request->pincode??null;
             $cm->is_billing = $request->billing_address??null;
             $cm->billing_cycle = $request->billing_cycle??null;
             $cm->credit_cycle = $request->credit_cycle??null;
             $cm->status =$request->status??null;
             $cm->save();
             */

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
        $suggestions = CompanyMaster::where('company_name', 'LIKE', '%' . $query . '%')
                                ->select('id', 'company_name') // Select only necessary columns
                                ->limit(10) // Limit the number of suggestions
                                ->get();

        return response()->json($suggestions);
    }
}
