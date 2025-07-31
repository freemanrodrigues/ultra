<?php

namespace App\Http\Controllers;

use App\Models\BottleType;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class BottleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = BottleType::query();
        //    sample_nature_code	sample_nature_name	remark	status	
            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('bottle_code', 'like', "%{$search}%")
                      ->orWhere('bottle_name', 'like', "%{$search}%");
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
    
            $bottle_types = $query->paginate(10)->appends($request->query());
    
            return view('masters.bottle-type.index', compact('bottle_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.bottle-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'bottle_code' => 'required|string|max:50|unique:bottle_types,bottle_code',
            'bottle_name' => 'required|string|max:255',
            'remark' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);
  //dd($request->all());		   
        try {
          //  dd($validated);
          BottleType::create($validated);
           //dd("Success");
            return redirect()->route('bottle-type.index')
                           ->with('success', 'Bottle Type created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating courier: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BottleType $bottleType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BottleType $bottleType)
    {
        return view('masters.bottle-type.edit',compact('bottleType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BottleType $bottleType)
    {
       // dd("Updates".$bottleType->id);
        $validated = $request->validate([
            'bottle_code' => 'required|string|max:50|unique:bottle_types,bottle_code,'.$bottleType->id,
            'bottle_name' => 'required|string|max:255',
            'remark' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
          // MakeMst::create($validated);
            $bottleType->update($validated);
           // dd("Success");
            return redirect()->route('bottle-type.index')
                           ->with('success', 'BottleType created successfully!');
        } catch (\Exception $e) {
            //dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating BottleType: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BottleType $bottleType): RedirectResponse|JsonResponse
    {
        try {
            BottleType::where('id', $bottleType->id)->update(['status' => 0]);
            return redirect()->route('bottle-type.index')
                            ->with('success', 'Bottle Type deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Bottle Type: ' . $e->getMessage());
        }
    }
}
