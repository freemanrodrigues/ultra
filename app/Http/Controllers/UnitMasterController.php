<?php

namespace App\Http\Controllers;

use App\Models\UnitMaster;
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;

class UnitMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        //dd("Site Master Index");
        $query = UnitMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('unit_code', 'like', "%{$search}%")
                  ->orWhere('unit_name', 'like', "%{$search}%");
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

        $unitMasters = $query->paginate(10)->appends($request->query());

        return view('masters.unit.index', compact('unitMasters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        return view('masters.unit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request->all());
       $validated = $request->validate([
        'unit_code' => 'required|string|max:50|unique:unit_masters,unit_code',
        'unit_name' => 'required|string|max:255',
        'status' => 'required|in:1,0'
    ]);

    try {
        UnitMaster::create($validated);
        
        return redirect()->route('unit-masters.index')
                       ->with('success', 'Unit Master created successfully!');
    } catch (\Exception $e) {
        dd("<br>Error : ".$e->getMessage());
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error creating Unit Master: ' . $e->getMessage());
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitMaster $unitMaster)
    {
        die("Show");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitMaster $unitMaster)
    {
        return view('masters.unit.edit',compact('unitMaster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitMaster $unitMaster)
    {
        $validated = $request->validate([
            'unit_code' => 'required|string|max:50|unique:unit_masters,unit_code',
            'unit_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);
    
        try {
            $unitMaster->update($validated);
            
            return redirect()->route('unit-masters.index')
                           ->with('success', 'Unit Master updated successfully!');
        } catch (\Exception $e) {
            dd("<br>Error : ".$e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Unit Master: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitMaster $unitMaster): RedirectResponse
    {
        try {
            UnitMaster::where('id', $unitMaster->id)->update(['status' => 0]);
            return redirect()->route('unit-masters.index')
                            ->with('success', 'Unit Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Unit Master: ' . $e->getMessage());
        }
    }
}
