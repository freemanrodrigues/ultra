<?php

namespace App\Http\Controllers;

use App\Models\{CompanyMaster,POMaster,SampleType,TestMaster};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class POMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = POMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
             $query->where('po_number', 'like', "%{$search}%");
        }	

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $po_datas = $query->paginate(10)->appends($request->query());

        return view('masters.po.index', compact('po_datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $sampleTypes = SampleType::getSampleType();
       $tests = TestMaster::where('status',1)->get();
       $companies= CompanyMaster::where('status',1)->get();
        return view('masters.po.create',compact('sampleTypes','tests','companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_masters,po_number',
            'po_date' => 'nullable|date',
            'party_id' => 'required|integer',
            'status' => 'required|integer',
            'site_id' => 'nullable|integer',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'currency' => 'required|string',
            'sample_type_id' => 'required|integer',
            'test_rate' => 'required|integer',
            'test_limit' => 'required|integer',	
        ]);
			
        try {
          // dd($validated);
           POMaster::create($validated);
           // dd("Success");
            return redirect()->route('po.index')
                           ->with('success', 'PO created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating PO: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(POMaster $pOMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(POMaster $po)
    {

         $sample_types = SampleType::getSampleType();
        return view('masters.po.edit',compact('sample_types','po'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, POMaster $po)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_masters,po_number,'.$po->id,
            'po_date' => 'nullable|date',
            'party_id' => 'required|integer',
            'status' => 'required|integer',
            'site_id' => 'nullable|integer',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'currency' => 'required|string',
            'sample_type_id' => 'required|integer',
            'test_rate' => 'required|integer',
            'test_limit' => 'required|integer',	
        ]);
			
        try {
           $po->update($validated);
            return redirect()->route('po.index')
                           ->with('success', 'PO updated successfully!');
        } catch (\Exception $e) {
            dd("Fail ". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating PO: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(POMaster $pOMaster)
    {
        //
    }
}
