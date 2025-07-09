<?php

namespace App\Http\Controllers;

use App\Models\{Country,CompanyMaster,User};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session;

class UserController extends Controller
{
    
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
        $companies = CompanyMaster::all();
        //dd($companies);
        return view('masters.user.create')->with(['countries' => $countries, 'companies' => $companies]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      //  dd($request->all());
       $validated = $request->validate([
        'email' => 'required|email|max:100|unique:users,email',
        'phone' => 'required|string|max:12|unique:users,phone',
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'address1' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'pincode' => 'required|string',
        'status' => 'required|in:1,0'
    ]);
   /* 
    "firstname" => "dfgdf"
    "lastname" => "dfgd"
    "address1" => "dgdfg"
    "address2" => "fgdf"
    "city" => "dfgdf"
    "state" => "dfg"
    "pincode" => "45645"
    "country_id" => null
    "company_id" => null
    "user_type" => "staff"
    "user_role" => "1"
    "status" => "1"
    */
    try {
        User::create($validated);
        
        return redirect()->route('users.index')
                       ->with('success', 'User created successfully!');
    } catch (\Exception $e) {
        dd("<br>Error : ".$e->getMessage());
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error creating User: ' . $e->getMessage());
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        die("Show");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        
     
     
        $countries = Country::all();
        $companies = CompanyMaster::all();
        //dd($companies);
        return view('masters.user.edit', compact('countries', 'companies'))->with(['users' =>$user ]); //->with(['' => $countries, 'companies' => $companies]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
      
       $validated = $request->validate([
       
        'email' => 'required|email|max:100|unique:users,email,'.$request->id,
        'phone' => 'required|string|max:12|unique:users,phone,'.$request->id,
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'address1' => 'required|string',
        'address2' => 'string',
        'city' => 'required|string',
        'state' => 'required|string',
        'pincode' => 'required|string',
        'country_id' => 'required|integer',
        'company_id' => 'required|integer',
        'user_type' => 'required|integer',
        'user_role' => 'required|integer',
       'status' => 'required|in:1,0'
    ]);
        
    try {
       // User::create($validated);
        
       
        $id =$request->id;
        $user = User::find($id);
        // $user->title = $request->input("title");
         $user->email = $request->email;
         $user->phone =  $request->phone;
         $user->firstname =  $request->firstname;
         $user->lastname =  $request->lastname;
         $user->address1 =  $request->address1;
         $user->address2 =  $request->address2;
         $user->city =  $request->city;
         $user->state =  $request->state;
         $user->pincode =  $request->pincode;
         $user->country_id =  $request->country_id;
         $user->company_id =  $request->company_id;
         $user->user_type =  $request->user_type;
         $user->user_role =  $request->user_role;
         $user->status =  $request->status;
         $user->save();
            return redirect()->route('users.index')
                    ->with('success', 'User Updated successfully!');
        } catch (\Exception $e) {
        dd("<br>Error : ".$e->getMessage());
        return redirect()->back()
                ->withInput()
                ->with('error', 'Error Updating User: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $segments = explode('/', $_SERVER['REQUEST_URI']);
        $id = end($segments);
        try {
            User::where('id', $id)->update(['status' => 0]);
            return redirect()->route('unit-masters.index')
                            ->with('success', 'Unit Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Unit Master: ' . $e->getMessage());
        }
    }


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
        'password' => 'required|string|min:8',
    ]);
        $email = $request['email'];
        $password = $request['password'];

       if(Auth::attempt(['email'=> $email, 'password' => $password])) {
         return redirect()->route('sample.index');
        } else {
            return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating Item: ');
        } 
    }
    
    public function registerHtml():View
    {
        $countries = Country::all();
        $companies = CompanyMaster::all();
        //dd($companies);
        return view('register')->with(['countries' => $countries, 'companies' => $companies]);

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
        'address1' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'pincode' => 'required|string',
        'company_id' => 'required',
        //'status' => 'required|in:1,0'
    ]);

   /* 
    "firstname" => "dfgdf"
    "lastname" => "dfgd"
    "address1" => "dgdfg"
    "address2" => "fgdf"
    "city" => "dfgdf"
    "state" => "dfg"
    "pincode" => "45645"
    "country_id" => null
    "company_id" => null
    "user_type" => "staff"
    "user_role" => "1"
    "status" => "1"
    */
    try {
        User::create($validated);
        
        return redirect()->route('register-success')
                       ->with('success', 'User created successfully!');
    } catch (\Exception $e) {
        dd("<br>Error : ".$e->getMessage());
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
}
