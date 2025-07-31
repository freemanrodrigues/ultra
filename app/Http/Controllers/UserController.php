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
}
