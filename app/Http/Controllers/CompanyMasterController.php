<?php

namespace App\Http\Controllers;

use App\Models\{CompanyMaster,State};
use Illuminate\Http\Request;

class CompanyMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyMaster $companyMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyMaster $companyMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyMaster $companyMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyMaster $companyMaster)
    {
        //
    }

    public function checkGST(Request $request)
    {
        $request->validate([
            'gst_no' => 'required|string|max:15',
        ]);
        $pan = substr($request->gst_no, 2, 10);
        $state_code = substr($request->gst_no, 0, 2);
        if($state_code < 10) {
            $state_code  =substr($request->gst_no, 1, 2);
        }
       
        $company = CompanyMaster::where('pancard', $pan)->first();
        $state = State::where('statecode', $state_code)->first();

        if ($company) {
            return response()->json([
                'exists' => true,
                'company_name' => $company->company_name, 
                'company_id' => $company->id, 
                'state_id' => $state->id, 
                'state_code' => $state->shortname, 
            ]);
        } elseif ($state) {
            return response()->json([
                'exists' => true,
                'state_id' => $state->id, 
                'state_code' => $state->shortname, 
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function autoSuggestCompanyName(Request $request)
    {
        $query = $request->input('query');

        // Basic validation (optional but recommended)
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]); // Return empty array if query is too short
        }

        // Fetch data from your database
        // Replace 'YourModel' and 'name' with your actual model and column name
        $suggestions = CompanyMaster::where('company_name', 'LIKE', '%' . $query . '%')
        ->select('id', 'company_name as name') 
        ->limit(10)
        ->get();

        return response()->json($suggestions);
    }
}
