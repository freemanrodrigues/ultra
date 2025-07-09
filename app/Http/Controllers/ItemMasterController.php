<?php

namespace App\Http\Controllers;

use App\Models\ItemMaster;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;

class ItemMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = ItemMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%");
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

        $items = $query->paginate(10)->appends($request->query());

        return view('masters.item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
        return view('masters.item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:50|unique:item_masters,item_code',
            'item_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
           ItemMaster::create($validated);
           // dd("Success");
            return redirect()->route('item.index')
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
    public function show(ItemMaster $itemMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemMaster $item)
    {
       return view('masters.item.edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemMaster $item)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:50|unique:item_masters,item_code,'.$item->id,
            'item_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
           $item->update($validated);
           // dd("Success");
            return redirect()->route('item.index')
                           ->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Item: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemMaster $item)
    {
        try {
            ItemMaster::where('id', $item->id)->update(['status' => 0]);
            return redirect()->route('item.index')
                            ->with('success', 'Item Master deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Item Master: ' . $e->getMessage());
        }
    }
}
