<?php

namespace App\Http\Controllers;

use App\Models\{BrandMaster,MakeMst};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class MakeMstController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = MakeMst::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('make_code', 'like', "%{$search}%")
                  ->orWhere('make_name', 'like', "%{$search}%");
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

        $makes = $query->paginate(10)->appends($request->query());

        return view('masters.make.index', compact('makes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
        $brands = BrandMaster::getBrandArray();
        return view('masters.make.create',compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'make_code' => 'required|string|max:50|unique:make_msts,make_code',
            'make_name' => 'required|string|max:255',
            'brand_id'=>'required|integer',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
           MakeMst::create($validated);
           // dd("Success");
            return redirect()->route('make.index')
                           ->with('success', 'Make created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Make: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MakeMst $makeMst)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakeMst $make)
    {
        $brands = BrandMaster::getBrandArray();
        return view('masters.make.edit',compact('make',
        'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakeMst $make)
    {
        
        $validated = $request->validate([
            'make_code' => 'required|string|max:50|unique:make_msts,make_code,'.$make->id,
            'make_name' => 'required|string|max:255',
            'brand_id'=>'required|integer',
            'status' => 'required|in:1,0',
        ]);

        try {
           // dd($validated);
          // MakeMst::create($validated);
             $make->update($validated);
            
           // dd("Success");
            return redirect()->route('make.index')
                           ->with('success', 'Make created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Make: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakeMst $make)
    {
        
        try {
            MakeMst::where('id', $make->id)->update(['status' => 0]);
            return redirect()->route('make.index')
                            ->with('success', 'MakeMaster deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting MakeMaster: ' . $e->getMessage());
        }
    }
}
