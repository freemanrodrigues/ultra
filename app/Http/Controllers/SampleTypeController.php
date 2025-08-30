<?php

namespace App\Http\Controllers;

use App\Models\SampleType;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class SampleTypeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = SampleType::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
             $query->where('sample_type_name', 'like', "%{$search}%");
        }	

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $sample_types = $query->paginate(10)->appends($request->query());

        return view('masters.sample-type.index', compact('sample_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
        return view('masters.sample-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sample_type_name' => 'required|string|max:255|unique:sample_types,sample_type_name',
            'description' => 'required|string',
            'status' => 'required|in:1,0',
            
        ]);

        try {
          // dd($validated);
           SampleType::create($validated);
           // dd("Success");
            return redirect()->route('sample-type.index')
                           ->with('success', 'Item created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Item: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleType $sampleType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleType $sampleType)
    {
        return view('masters.sample-type.edit',compact('sampleType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleType $sampleType)
    {
        $validated = $request->validate([
            'sample_type_name' => 'required|string|max:255|unique:sample_types,sample_type_name,'.$sampleType->id,
            'description' => 'required|string',
            'status' => 'required',
        ]);

        try {
          // dd($validated);
          $sampleType->update($validated);
            //dd("Success");
            return redirect()->route('sample-type.index')
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
    public function destroy(SampleType $sampleType)
    {
        try {
            SampleType::where('id', $sampleType->id)->update(['status' => 0]);
            return redirect()->route('sample-type.index')
                            ->with('success', 'Sample Type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Sample Type: ' . $e->getMessage());
        }
    }
}
