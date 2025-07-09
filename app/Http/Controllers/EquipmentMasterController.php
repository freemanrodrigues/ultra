<?php

namespace App\Http\Controllers;

use App\Models\EquipmentMaster;
use Illuminate\Http\{Request,RedirectResponse, JsonResponse};
use Illuminate\View\View;
use Illuminate\Validation\Rule;


class EquipmentMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): view
    {
        $query = EquipmentMaster::query();
       
        // Filter by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('em_code', 'like', "%{$search}%")
                  ->orWhere('em_name', 'like', "%{$search}%");
            });
        }
        
        // Order by created_at desc by default
        $equipmentMasters = $query->orderBy('created_at', 'desc')->paginate(15);
       
        /*
        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $equipmentMasters,
                'message' => 'Equipment masters retrieved successfully'
            ]);
        }
        */
        // Return view for web requests
       // dd($equipmentMasters);
        return view('masters.equipment-masters.index', compact('equipmentMasters'));
        
      //  return view('masters.equipment-masters.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('masters.equipment-masters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'em_code' => 'required|string|max:50|unique:equipment_masters,em_code',
            'em_name' => 'required|string|max:255',
            'status' => 'sometimes|boolean'
        ]);
        
        // Set default status to 1 (active) if not provided
        $validatedData['status'] = $validatedData['status'] ?? 1;
        
        $equipmentMaster = EquipmentMaster::create($validatedData);
        /*
        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $equipmentMaster,
                'message' => 'Equipment master created successfully'
            ], 201);
        }
        */
        // Return redirect for web requests
        return redirect()->route('equipment-masters.index')
            ->with('success', 'Equipment master created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentMaster $equipmentMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentMaster $equipmentMaster)
    {
        return view('masters.equipment-masters.edit', compact('equipmentMaster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentMaster $equipmentMaster): RedirectResponse|JsonResponse
    {
        $validatedData = $request->validate([
            'em_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('equipment_masters', 'em_code')->ignore($equipmentMaster->id)
            ],
            'em_name' => 'required|string|max:255',
            'status' => 'sometimes|boolean'
        ]);
        
        $equipmentMaster->update($validatedData);
        
        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $equipmentMaster->fresh(),
                'message' => 'Equipment master updated successfully'
            ]);
        }
        
        // Return redirect for web requests
        return redirect()->route('equipment-masters.index')
            ->with('success', 'Equipment master updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentMaster $equipmentMaster): RedirectResponse|JsonResponse
    {
        // Soft delete by setting status to 0
        $equipmentMaster->update(['status' => 0]);
        
        // Return JSON for API requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Equipment master deactivated successfully'
            ]);
        }
        
        // Return redirect for web requests
        return redirect()->route('equipment-masters.index')
            ->with('success', 'Equipment master deactivated successfully');
    }
}
