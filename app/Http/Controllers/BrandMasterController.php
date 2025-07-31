<?php

namespace App\Http\Controllers;

use App\Models\BrandMaster;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;


class BrandMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        
        $query = BrandMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('brand_code', 'like', "%{$search}%")
                  ->orWhere('brand_name', 'like', "%{$search}%");
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

        $brands = $query->paginate(10)->appends($request->query());

        return view('masters.brand.index', compact('brands'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
        return view('masters.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
       // dd($request->all());
        $validated = $request->validate([
            'brand_code' => 'required|string|max:50|unique:brand_masters,brand_code',
            'brand_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
            BrandMaster::create($validated);
           // dd("Success");
            return redirect()->route('brand.index')
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
    public function show(BrandMaster $brandMaster)
    {
        dd('Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BrandMaster $brand): View
    {
   
      
        return view('masters.brand.edit', compact('brand'));
   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BrandMaster $brand)
    {
        //dd($brand->id);
        $validated = $request->validate([
            'brand_code' => 'required|string|max:50|unique:brand_masters,brand_code,'.$brand->id,
            'brand_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
            $brand->update($validated);
            //dd("sucess");
            return redirect()->route('brand.index')
                           ->with('success', 'Brand Master updated successfully!');
        } catch (\Exception $e) {
            //dd("Failed".$e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Site Master: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BrandMaster $brand): RedirectResponse|JsonResponse
    {
       
        try {
            BrandMaster::where('id', $brand->id)->update(['status' => 0]);
            return redirect()->route('brand.index')
                            ->with('success', 'BrandMaster deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting BrandMaster: ' . $e->getMessage());
        }
        
    }
}
