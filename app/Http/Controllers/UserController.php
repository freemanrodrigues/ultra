<?php

namespace App\Http\Controllers;
use App\Models\{Country,CompanyMaster,CustomerMaster,SiteContact,User};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session;

class UserController
{
    public function loginHtml():View
    {
        return view('login');
    }

    public function verifyUser(Request $request): RedirectResponse
    {
       // dd($request->all());
       // return view('login');

    $validated = $request->validate([
        'email' => 'required|email',
        'password' => [
            'required',
            'min:8', 
        //    'regex:/[a-z]/',    
         //   'regex:/[A-Z]/',   
         //   'regex:/[0-9]/',    
         //   'regex:/[@$!%*#?&]/', 
        ],
    ]);
        $email = $request['email'];
        $password = $request['password'];

       if(Auth::attempt(['email'=> $email, 'password' => $password])) {
       //  die( "User".Auth::user()->user_type);
        if(Auth::user()->user_type == 0) {
            
         }
         $dash1 = 'dashboard'; 
         return redirect()->route($dash1);
        } else {
            return redirect()->back()
            ->withInput()
            ->with('error', 'Login Failed');
        } 
    }

    public function registerHtml():View
    {
        return view('register');
    }

    public function registerUser(Request $request)
    {
      //  dd($request->all());
       $validated = $request->validate([
        'email' => 'required|email|max:100|unique:users,email',
        'password' => 'required|string|min:8',
        'phone' => 'required|string|max:12|unique:users,phone',
        'firstname' => 'required|string',
        'lastname' => 'required|string',
    ]);

        try {
        $user = User::create($validated);
        return redirect()->route('register-success')
        ->with('success', 'User created successfully!');
        } catch (\Exception $e) {

        return redirect()->back()
        ->withInput()
        ->with('error', 'Error creating User: ' . $e->getMessage());
        }
    }

    public function registerSuccess():View
    {
        return view('register-success');
    }

    public function logout() { 
	    Session::flush();
        Auth::logout();
        return redirect()->route('login');
	}

    public function index(Request $request): View
    {
       // dd("User Index");
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
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

        $users = $query->paginate(10)->appends($request->query());
        $countries = Country::all();
        $companies = CompanyMaster::all();
        return view('masters.user.index', compact('users'))->with(['countries' => $countries, 'companies' => $companies]);
    }

        /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        $countries = Country::all();
        $customers = CustomerMaster::all();
        //dd($companies);
        return view('masters.user.create')->with(['countries' => $countries, 'customers' => $customers]);
    }

    public function store(Request $request)
    {
        //dd($request->all());
       $validated = $request->validate([
        'email' => 'required|email|max:100|unique:users,email',
        'phone' => 'required|string|max:12|unique:users,phone',
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'customer_id' => 'required|integer',
        "user_type" => "nullable",
        "user_role" => "nullable",
        'status' => 'required|in:1,0'
    ]);

    try {
        $user = User::create($validated);
        $userId = $user->id;
        $customer = CustomerMaster::getCountryId($request['customer_id']);
        
        User::where('id',$userId)->update(['company_id'=>$customer[0]->company_id, 'customer_id' =>$request->customer_id]);


        return redirect()->route('users.index')
                       ->with('success', 'User created successfully!');
    } catch (\Exception $e) {
        dd("<br>Error : ".$e->getMessage());
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error creating User: ' . $e->getMessage());
    }
    }


    public function testing():View
    {
        $countries = Country::all();
        $customers = CustomerMaster::all();
        //dd($companies);
        return view('testing2');
    }

    public function getCountryAddress(Request $request)
    {
        //return($request->all());
        $request->validate([
            'query' => 'required|string',
        ]);
        $query = $request->input('query');
        $customer = CompanyMaster::where('company_name',  'LIKE', '%' .$query. '%')->get(['company_name','id']);
        
        
         if ($customer) {
            return response()->json([
                'customer' => $customer, 
               
            ]);
        }
        return response()->json([]);
    }
}
