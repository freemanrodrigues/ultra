<?php

namespace App\Http\Controllers;

use App\Models\TestMaster;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class TestMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = TestMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
             $query->where('test_name', 'like', "%{$search}%");
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

        return view('masters.test.index', compact('sample_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          return view('masters.test.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'test_name' => 'required|string|max:255|unique:test_masters,test_name',
            'default_unit' => 'nullable|string',
            'tat_hours_default' => 'nullable|string',
            'status' => 'required|in:1,0',
        ]);

        try {
          // dd($validated);
           TestMaster::create($validated);
           // dd("Success");
            return redirect()->route('test.index')
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
    public function show(TestMaster $testMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TestMaster $test)
    {
        
        return view('masters.test.edit',compact('test'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestMaster $test)
    {
       $validated = $request->validate([
            'test_name' => 'required|string|max:255|unique:test_masters,test_name,'.$test->id,
            'default_unit' => 'nullable|string',
            'tat_hours_default' => 'nullable|string',
            'status' => 'required|in:1,0',
        ]);

        try {
          // dd($validated);
          $test->update($validated);
            //dd("Success");
            return redirect()->route('test.index')
                           ->with('success', 'Test Master updated successfully!');
        } catch (\Exception $e) {
           dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Test: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestMaster $test)
    {
         try {
            TestMaster::where('id', $test->id)->update(['status' => 0]);
            return redirect()->route('test.index')
                            ->with('success', 'Test deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Test: ' . $e->getMessage());
        }
    }
}
