<?php

namespace App\Http\Controllers;

use App\Models\SampleOilType;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class SampleOilTypeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = SampleOilType::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('sample_oil_type_code', 'like', "%{$search}%")
                  ->orWhere('sample_oil_type_name', 'like', "%{$search}%");
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

        $sample_oil_types = $query->paginate(10)->appends($request->query());

        return view('masters.sample-oil-type.index', compact('sample_oil_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
         return view('masters.sample-oil-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sample_oil_type_code' => 'required|string|max:50|unique:sample_oil_types,sample_oil_type_code',
            'sample_oil_type_name' => 'required|string|max:255',
            'mis_group' => 'required|string',
            'remark' => 'required|string',
            'status' => 'required|in:1,0',
            
        ]);

        try {
          // dd($validated);
           SampleOilType::create($validated);
           // dd("Success");
            return redirect()->route('sample-oil-type.index')
                           ->with('success', 'Item created successfully!');
        } catch (\Exception $e) {
            //dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Item: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleOilType $sampleOilType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleOilType $sampleOilType)
    {
         return view('masters.sample-oil-type.edit',compact('sampleOilType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleOilType $sampleOilType)
    {
        $validated = $request->validate([
            'sample_oil_type_code' => 'required|string|max:50|unique:sample_oil_types,sample_oil_type_code,'.$sampleOilType->id,
            'sample_oil_type_name' => 'required|string|max:255',
            'mis_group' => 'required|string',
            'remark' => 'required|string',
            'status' => 'required',
        ]);

        try {
          // dd($validated);
          $sampleOilType->update($validated);
            //dd("Success");
            return redirect()->route('sample-oil-type.index')
                           ->with('success', 'Sample Type updated successfully!');
        } catch (\Exception $e) {
           dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Sample Type: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleOilType $sampleOilType)
    {
        try {
            SampleOilType::where('id', $sampleOilType->id)->update(['status' => 0]);
            return redirect()->route('sample-oil-type.index')
                            ->with('success', 'Sample Type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Sample Type: ' . $e->getMessage());
        }
    }
}
