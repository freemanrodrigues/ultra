<?php

namespace App\Http\Controllers;

use App\Models\SubAssembly;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class SubAssemblyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = SubAssembly::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('sub_assemblies_code', 'like', "%{$search}%")
                  ->orWhere('sub_assemblies_name', 'like', "%{$search}%");
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

        $subassemblys = $query->paginate(10)->appends($request->query());

        return view('masters.subassembly.index', compact('subassemblys'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
        return view('masters.subassembly.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        $validated = $request->validate([
            'sub_assemblies_code' => 'required|string|max:50|unique:sub_assemblies,sub_assemblies_code',
            'sub_assemblies_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
           SubAssembly::create($validated);
           // dd("Success");
            return redirect()->route('subassembly.index')
                           ->with('success', 'Brand created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating brand: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubAssembly $subAssembly)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubAssembly $subassembly):view
    {
        //dd($subassembly->id);
        return view('masters.subassembly.edit',compact('subassembly'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubAssembly $subassembly)
    {
        //dd($subassembly->id);
        //dd($request->all());
        $validated = $request->validate([
            'sub_assemblies_code' => 'required|string|max:50|unique:sub_assemblies,sub_assemblies_code,'.$subassembly->id,
            'sub_assemblies_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
           $subassembly->update($validated);
            //dd("Success");
            return redirect()->route('subassembly.index')
                           ->with('success', 'Sub Assembly updated successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Sub Assembly: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubAssembly $subassembly)
    {
        
        try {
            SubAssembly::where('id', $subassembly->id)->update(['status' => 0]);
            return redirect()->route('subassembly.index')
                            ->with('success', 'Sub Assembly deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Sub Assembly: ' . $e->getMessage());
        }
    }
}
