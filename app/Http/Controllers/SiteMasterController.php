<?php

namespace App\Http\Controllers;

use App\Models\{Country,CompanyMaster,CustomerMaster,SiteContact,SiteMaster,User};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;


class SiteMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
       //dd("Site Master Index");
        $query = SiteMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('site_code', 'like', "%{$search}%")
                  ->orWhere('site_name', 'like', "%{$search}%");
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

        return view('masters.site-masters.index', compact('siteMasters'));
    }

    /**
     * Show the form for creating a new resource.
     */ 
    public function create(): View
    {
        $countries = Country::all();
        $companies = CompanyMaster::all();
        $customers = CustomerMaster::all();
        return view('masters.site-masters.create', compact('countries','companies','customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());
        $validated = $request->validate([
            'site_code' => 'required|string|max:50|unique:site_masters,site_code',
            'site_name' => 'required|string|max:255',
            "site_display_name" => 'string',
            "contact_type" => 'string',
            "company_id" => 'integer',
            "customer_id" => 'integer',
            "address" => 'string',
            "address1" => 'string',
            "city" => 'string',
            "state" => 'string',
            "country" => 'string',
            "lat" => 'string',
            "long" => 'string',
            "customer_type" => 'string',
            'status' => 'required|in:1,0'
        ]);
/*
site_code" => null
  "site_name" => null
  "site_display_name" => null
  "contact_type" => "email"
  "company_id" => "1"
  "customer_id" => "1"
  "address" => null
  "address1" => null
  "city" => null
  "state" => null
  "country" => null
  "lat" => null
  "long" => null
  "customer_type" => "retail"
  "status" => "1"
*/


        try {
            SiteMaster::create($validated);
            
            return redirect()->route('site-masters.index')
                           ->with('success', 'Site Master created successfully!');
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
    public function show(SiteMaster $siteMaster): View
    {
        dd("view full details");
        return view('masters.site-masters.show', compact('siteMaster'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteMaster $siteMaster): View
    {
        
        return view('masters.site-masters.edit', compact('siteMaster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiteMaster $siteMaster): RedirectResponse
    {
       // dd($request->all());
        $validated = $request->validate([
            'site_code' => 'required|string|max:50|unique:site_masters,site_code,'.$siteMaster->id,
            'site_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
            $siteMaster->update($validated);
          //  dd("sucess");
            return redirect()->route('site-masters.index')
                           ->with('success', 'Site Master updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Site Master: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiteMaster $siteMaster): RedirectResponse
    {
        //dd($siteMaster);
        try {
            $result = $siteMaster->delete();
            dd($result);
            return redirect()->route('masters.site-masters.index')
                           ->with('success', 'Site Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error deleting Site Master: ' . $e->getMessage());
        }
    }
    /**
     * Toggle status of the site master
     */
    public function toggleStatus(SiteMaster $siteMaster): RedirectResponse
    {
        try {
            $siteMaster->update([
                'status' => $siteMaster->status === 'active' ? 'inactive' : 'active'
            ]);
            
            return redirect()->back()
                           ->with('success', 'Status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
    public function  assignUsers($id):View
    {
       // die("Id:".$id);
       $siteMaster = SiteMaster::where('id' , $id)->get();
       
       $users = User::where('company_id' , $siteMaster[0]->company_id)->get();
       //dd($users);
       return view('masters.site-masters.assign-users', compact('siteMaster','users'));
    }
    
    public function saveAssignUsers(Request $request): RedirectResponse
    {
        // print_r($request->all());
        // dd("Save Asing Users");
       
        $validated = $request->validate([
            'site_id' => 'required|integer',
            'users' => 'required|array',
        ]);

        try {


            $siteMaster = SiteMaster::where('id' , $request->site_id)->get();            
           
            foreach($request->users as $user) {
                $sc = new SiteContact();
                $sc->company_id = $siteMaster[0]->company_id; //'company_id', 'customer_id', 'site_masters_id', 'user_id'
                $sc->customer_id =  $siteMaster[0]->customer_id; 
                $sc->site_masters_id  =  $siteMaster[0]->id; 
                $sc->user_id = $user;
                $sc->save();
            }
          //  dd("sucess");
            return redirect()->route('site-masters.index')
                           ->with('success', 'Site Master updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Site Master: ' . $e->getMessage());
        }
    }
    

}
