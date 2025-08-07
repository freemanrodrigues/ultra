<?php

namespace App\Http\Controllers;

use App\Models\{Country,ContactMaster,CompanyMaster,CustomerMaster,SiteContact,User};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;

class ContactMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = ContactMaster::query();
        //$contacts = ContactMaster::with('company')->get();
        $query->with('company');
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%");
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
        return view('masters.contacts-masters.index', compact('users'))->with(['countries' => $countries, 'companies' => $companies]);
 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        $companies = CompanyMaster::all();
        //dd($companies);
        return view('masters.contacts-masters.create')->with(['countries' => $countries, 'companies' => $companies]);

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
        'company_id' => 'required|integer',
        'status' => 'required|in:1,0'
    ]);

    try {
         ContactMaster::create($validated);
        return redirect()->route('contacts-masters.index')
                       ->with('success', 'User created successfully!');
    } catch (\Exception $e) {
        //dd("<br>Error : ".$e->getMessage());
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error creating User: ' . $e->getMessage());
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMaster $contactMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactMaster $contacts_master)
    {
        $countries = Country::all();
        $companies = CompanyMaster::all();
       // dd($contacts_master->id);
        return view('masters.contacts-masters.edit', compact('countries', 'companies','contacts_master')); //->with(['' => $countries, 'companies' => $companies]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactMaster $contacts_master)
    {
       // dd($request->all());
        $validated = $request->validate([
       
            'email' => 'required|email|max:100|unique:contact_masters,email,'.$request->id,
            'phone' => 'required|string|max:12|unique:contact_masters,phone,'.$request->id,
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'company_id' => 'required|integer',
           
           'status' => 'required|in:1,0'
        ]);
            
        try {
           // User::create($validated);
            
           $contacts_master->update($validated);
           
                return redirect()->route('contacts-masters.index')
                        ->with('success', 'Contact Master Updated successfully!');
            } catch (\Exception $e) {
            //dd("<br>Error : ".$e->getMessage());
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error Updating Contact Master: ' . $e->getMessage());
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMaster $contacts_master)
    {
        try {
            ContactMaster::where('id', $contacts_master->id)->update(['status' => 0]);
            return redirect()->route('contacts-masters.index')
                            ->with('success', 'Contact Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Contact Master: ' . $e->getMessage());
        }
    }

    
    public function getContacts(Request $request)
    {
     /*   $request->validate([
            'company_id' => 'required|integer',
        ]);
       */
        $data = ContactMaster::where('company_id', $request->company_id)->get();

        if ($data) {
            return response()->json($data);
        }
        return response()->json();
    }
}
