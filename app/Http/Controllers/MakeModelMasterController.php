<?php

namespace App\Http\Controllers;

use App\Models\MakeModelMaster;
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;
use Illuminate\Validation\Rule;
class MakeModelMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        //dd("List Customer Sites");
        $query = MakeModelMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
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

        $make_models = $query->paginate(10)->appends($request->query());
       
        return view('masters.make-model.index', compact('make_models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
        return view('masters.make-model.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
                   'make' => 'required|string',
                  'model' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('make_model_masters')->where(function ($query) use ($request) {
                        return $query->where('make', $request->make);
                    }),
                ],
                  "description" => 'string',
                  'status' => 'required|in:1,0'
                ]);

                try {
                    // $data = $request->validate();
                    MakeModelMaster::create($validated);
                    
                     return redirect()->route('make-model-masters.index')
                                    ->with('success', 'Make Model Master created successfully!');
                 } catch (\Exception $e) {
                     
                     return redirect()->back()
                                    ->withInput()
                                    ->with('error', 'Error creating Make Model: ' . $e->getMessage());
                 }    
    }

    /**
     * Display the specified resource.
     */
    public function show(MakeModelMaster $makeModelMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakeModelMaster $makeModelMaster)
    {
       
        return view('masters.make-model.edit', compact('makeModelMaster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakeModelMaster $makeModelMaster)
    {
       //dd($request->all());
        $validated = $request->validate([
            'make' => 'required|string',
                  'model' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('make_model_masters')->where(function ($query) use ($request) {
                        return $query->where('make', $request->make);
                    })->ignore($makeModelMaster->id),
                ],
                  "description" => 'string',
                  'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
          // MakeMst::create($validated);
          $makeModelMaster->update($validated);
            
           // dd("Success");
            return redirect()->route('make-model-masters.index')
                           ->with('success', 'Make Model updated successfully!');
        } catch (\Exception $e) {
            //dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating Make Model: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakeModelMaster $makeModelMaster)
    {
        try {
            MakeModelMaster::where('id', $makeModelMaster->id)->update(['status' => 0]);
            return redirect()->route('make.index')
                            ->with('success', 'Make Model deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting Make Model: ' . $e->getMessage());
        }
    }
}
