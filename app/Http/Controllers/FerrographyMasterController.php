<?php

namespace App\Http\Controllers;

use App\Models\FerrographyMaster;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class FerrographyMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):View
    {
        $query = FerrographyMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('ferrography_code', 'like', "%{$search}%")
                  ->orWhere('ferrography_name', 'like', "%{$search}%");
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

        $ferrographys = $query->paginate(10)->appends($request->query());

        return view('masters.ferrography.index', compact('ferrographys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.ferrography.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());
        $validated = $request->validate([
            'ferrography_code' => 'required|string|max:50|unique:ferrography_masters,ferrography_code',
            'ferrography_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
           FerrographyMaster::create($validated);
           // dd("Success");
            return redirect()->route('ferrography.index')
                           ->with('success', 'Ferrography created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating ferrography: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FerrographyMaster $ferrographyMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FerrographyMaster $ferrography):view
    {
        //dd($ferrography->id);
        return view('masters.ferrography.edit',compact('ferrography'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FerrographyMaster $ferrography)
    {
        //dd("Update");
        $validated = $request->validate([
            'ferrography_code' => 'required|string|max:50|unique:ferrography_masters,ferrography_code,'.$ferrography->id,
            'ferrography_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           //print_r($validated);
           $ferrography->update($validated);
             //dd("Success");
            return redirect()->route('ferrography.index')
                           ->with('success', 'Ferrography updated successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating ferrography: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FerrographyMaster $ferrography)
    {
        try {
            FerrographyMaster::where('id', $ferrography->id)->update(['status' => 0]);
            return redirect()->route('ferrography.index')
                            ->with('success', 'FerrographyMaster deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting FerrographyMaster: ' . $e->getMessage());
        }
    }
}
