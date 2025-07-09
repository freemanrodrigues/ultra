<?php

namespace App\Http\Controllers;

use App\Models\CourierMaster;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class CourierMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = CourierMaster::query();
    //    sample_nature_code	sample_nature_name	remark	status	
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('courier_code', 'like', "%{$search}%")
                  ->orWhere('courier_name', 'like', "%{$search}%");
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

        $couriers = $query->paginate(10)->appends($request->query());

        return view('masters.courier.index', compact('couriers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.courier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
         // dd($request->all());		
         $validated = $request->validate([
            'courier_code' => 'required|string|max:50|unique:courier_masters,courier_code',
            'courier_name' => 'required|string|max:255',
          //  'remark' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);
        //dd($request->all());		   
        try {
          //  dd($validated);
          CourierMaster::create($validated);
           //dd("Success");
            return redirect()->route('courier.index')
                           ->with('success', 'Courier created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating courier: ' . $e->getMessage());
        }
        //dd("XXX");
    }

    /**
     * Display the specified resource.
     */
    public function show(CourierMaster $courierMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourierMaster $courier)
    {   
       // die("XX:".$courier->id);
        return view('masters.courier.edit',compact('courier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourierMaster $courier)
    {
        // dd($request->all());		
        $validated = $request->validate([
            'courier_code' => 'required|string|max:50|unique:courier_masters,courier_code,'.$courier->id,
            'courier_name' => 'required|string|max:255',
          //  'remark' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);
        //dd($request->all());		   
        try {
          //  dd($validated);
          $courier->update($validated);
           //dd("Success");
            return redirect()->route('courier.index')
                           ->with('success', 'Courier updated successfully!');
        } catch (\Exception $e) {
           // dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating courier: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourierMaster $courier)
    {
        try {
            CourierMaster::where('id', $courier->id)->update(['status' => 0]);
            return redirect()->route('courier.index')
                            ->with('success', 'Courier Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Courier Master: ' . $e->getMessage());
        }
    }
}
