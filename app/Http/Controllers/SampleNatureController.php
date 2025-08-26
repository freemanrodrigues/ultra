<?php

namespace App\Http\Controllers;

use App\Models\SampleNature;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class SampleNatureController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = SampleNature::query();
    //    sample_nature_code	sample_nature_name	remark	status	
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('sample_nature_code', 'like', "%{$search}%")
                  ->orWhere('sample_nature_name', 'like', "%{$search}%");
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

        $sample_natures = $query->paginate(10)->appends($request->query());

        return view('masters.sample-nature.index', compact('sample_natures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        return view('masters.sample-nature.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       // dd($request->all());		
        $validated = $request->validate([
            'sample_nature_code' => 'required|string|max:50|unique:sample_natures,sample_nature_code',
            'sample_nature_name' => 'required|string|max:255',
            'remark' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);
        //dd($request->all());		   
        try {
          //  dd($validated);
           SampleNature::create($validated);
           //dd("Success");
            return redirect()->route('sample-nature.index')
                           ->with('success', 'SampleNature created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating SampleNature: ' . $e->getMessage());
        }
        //dd("XXX");
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleNature $sampleNature)
    {
       dd("XX");
    }

    /**
     * Show the form for editing the specified resource.
     */
   
    public function edit(SampleNature $sampleNature):view
    {
        dd("XX");
       die("Id: ".$sampleNature->id);
        //return view('masters.sample-nature.edit', compact('sampleNature'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleNature $sampleNature)
    {
        die("Update");
        $validated = $request->validate([
            'sample_nature_code' => 'required|string|max:50|unique:sample_natures,sample_nature_code,'.$sampleNature->id,
            'sample_nature_name' => 'required|string|max:255',
            'remark' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);
        //dd($request->all());		   
        try {
          //  dd($validated);
          $sampleNature->update($validated);
           //dd("Success");
            return redirect()->route('sample-nature.index')
                           ->with('success', 'SampleNature updated successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating SampleNature: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleNature $sampleNature)
    {
        try {
            SampleNature::where('id', $sampleNature->id)->update(['status' => 0]);
            return redirect()->route('sample-nature.index')
                            ->with('success', 'SampleNature Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting SampleNature Master: ' . $e->getMessage());
        }
    }
}
