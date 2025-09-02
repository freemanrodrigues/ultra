<?php

namespace App\Http\Controllers;

use App\Models\{Country,CompanyMaster,CustomerMaster,CustomerSiteMaster,SiteContact,State,SiteMaster,User};
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\{DB,Log};

class SiteMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
       //dd("Site Master Index");
     //  DB::enableQueryLog();
        $query = SiteMaster::query()->with(['state_table', 'customerSiteMasters.customer']);
       
        // Search functionality
        if($request->filled('site_master_id')) { 
            $query->where("id", $request->get('site_master_id'));
        }elseif ($request->filled('search')) {
            $search = $request->get('search');
         /*   $query->where(function($q) use ($search) {
                $q->where('site_code', 'like', "%{$search}%")
                  ->orWhere('site_name', 'like', "%{$search}%");
            }); */
            $query->where("site_name", 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $siteMasters = $query->paginate(100)->appends($request->query());
        
        // Check if user wants list view
        if ($request->get('view') === 'list') {
            return view('masters.site-masters.list', compact('siteMasters'));
        }
        
        // Get all sites with coordinates for map
        $mapSites = SiteMaster::where('status', 1)
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->where('lat', '!=', '')
            ->where('long', '!=', '')
            ->with(['customerSiteMasters.customer', 'state_table'])
            ->get();
        
        // Convert coordinates to numeric values
        $mapSites = $mapSites->map(function($site) {
            $site->lat = floatval($site->lat);
            $site->long = floatval($site->long);
            return $site;
        });
        
        // Calculate statistics
        $sitesWithCustomers = $mapSites->filter(function($site) {
            return $site->customerSiteMasters && $site->customerSiteMasters->count() > 0;
        })->count();
        
        $totalCustomerSites = $mapSites->sum(function($site) {
            return $site->customerSiteMasters ? $site->customerSiteMasters->count() : 0;
        });
        
        $sitesMissingCoordinates = SiteMaster::where('status', 1)
            ->where(function($query) {
                $query->whereNull('lat')
                      ->orWhere('lat', '')
                      ->orWhereNull('long')
                      ->orWhere('long', '');
            })
            ->count();
        
       // $logs = DB::getQueryLog();
      //  dd($logs); 
        return view('masters.site-masters.index', compact('siteMasters', 'mapSites', 'sitesWithCustomers', 'totalCustomerSites', 'sitesMissingCoordinates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $countries = Country::getCountryArray();
        $states = State::getStateArray();
       
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
            "address" => 'string',
         //   "address1" => 'string',
            "city" => 'string',
            "state" => 'string',
            "country" => 'string',
            "lat" => 'nullable|string',
            "long" => 'nullable|string',
            'status' => 'required|in:1,0'
        ]);

        try {
            SiteMaster::create($validated);
            return redirect()->route('site-masters.index')
            ->with('success', [
                'text' => 'Site Master created successfully!',
                'link' => route('customer-site-masters.create'), // link to customer details
                'link_text' => 'Assign Site For the Customer '
            ]);

        } catch (\Exception $e) {
            //dd("<br>Error : ".$e->getMessage());
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
        // Load customers linked to this site
        $customers = CustomerSiteMaster::where('site_master_id', $siteMaster->id)
            ->with(['customer', 'siteMaster'])
            ->get();
            
        return view('masters.site-masters.show', compact('siteMaster', 'customers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteMaster $siteMaster): View
    {
        $countries = Country::getCountryArray();
        $states = State::getStateArray();
        return view('masters.site-masters.edit', compact('siteMaster','countries','states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiteMaster $siteMaster)
    {
       // dd($request->all());
        $validated = $request->validate([
           // 'site_code' => 'required|string|max:50|unique:site_masters,site_code,'.$siteMaster->id,
            'site_name' => 'required|string|max:255',
            "address" => 'nullable|string',
            "city" => 'nullable|string',
            "state" => 'nullable|string',
            "country" => 'nullable|string',
            "lat" => 'nullable|string',
            "long" => 'nullable|string',
            'status' => 'required|in:1,0'
        ]);
        
        try {
            $siteMaster->update($validated);
          //  dd("sucess");
           
            return redirect()->route('site-masters.index')
            ->with('success', [
                'text' => 'Site Master updated successfully!',
                'link' => route('customer-site-masters.create'), // link to customer details
                'link_text' => 'Assign Site For the Customer '
            ]);               
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

    public function autoSuggestSiteName(Request $request)
    {
        $query = $request->input('query');

        // Basic validation (optional but recommended)
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]); // Return empty array if query is too short
        }

        // Fetch data from your database
        // Replace 'YourModel' and 'name' with your actual model and column name
        $suggestions = SiteMaster::where('site_name', 'LIKE', '%' . $query . '%')
                                ->select('id', 'site_name as name') // Select only necessary columns
                                ->limit(10) // Limit the number of suggestions
                                ->get();

        return response()->json($suggestions);
    }
    
    public function ajaxListSitemaster(Request $request)
    {
        $query = $request->input('query');

        // Basic validation (optional but recommended)
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]); // Return empty array if query is too short
        }

        // Fetch data from your database
        // Replace 'YourModel' and 'name' with your actual model and column name
        $suggestions = SiteMaster::where('site_name', 'LIKE', '%' . $query . '%')->get();

        return response()->json($suggestions);
    }
}
