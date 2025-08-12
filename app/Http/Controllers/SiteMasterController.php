<?php

namespace App\Http\Controllers;

use App\Models\{Country,CompanyMaster,CustomerMaster,SiteContact,State,SiteMaster,User};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;

class SiteMasterController
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
        $states = State::all();
       
        return view('masters.site-masters.create', compact('countries','states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       // dd($request->all());
        $validated = $request->validate([
         //   'site_code' => 'required|string|max:50|unique:site_masters,site_code',
            'site_name' => 'required|string|max:255',
         //   "address" => 'string',
         //   "address1" => 'string',
            "city" => 'string',
            "state" => 'string',
            "country" => 'string',
            "lat" => 'string',
            "long" => 'string',
            'status' => 'required|in:1,0'
        ]);
 /*       "site_name" => "Rustomjee"
        "address" => null
        "city" => "Virar"
        "state" => "Maharashtra"
        "country" => "India"
        "CountryCode" => null
        "pincode" => null
        "lat" => null
        "long" => null */
        try {
            SiteMaster::create($validated);
            return redirect()->route('site-masters.index')
            ->with('success', [
                'text' => 'Site Master created successfully!',
                'link' => route('customer-site-masters.create'), // link to customer details
                'link_text' => 'Assign Site For the Customer '
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
    public function update(Request $request, SiteMaster $siteMaster)
    {
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
    public function destroy(SiteMaster $siteMaster)
    {
        try {
            $result = $siteMaster->delete();
           
            return redirect()->route('masters.site-masters.index')
                           ->with('success', 'Site Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error deleting Site Master: ' . $e->getMessage());
        }
    }
}
