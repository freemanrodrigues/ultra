<?php

namespace App\Http\Controllers;

use App\Models\CompanyMaster;
use Illuminate\Http\Request;

class CompanyMasterController extends Controller
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
        $pan = substr($request->gst_no, 2, 12);
        $company = CompanyMaster::where('pancard', $pan)->first();

        if ($company) {
            return response()->json([
                'exists' => true,
                'company_name' => $company->company_name, 
                'company_id' => $company->id, 
            ]);
        }

        return response()->json(['exists' => false]);
    }
}
