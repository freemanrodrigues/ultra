<?php

namespace App\Http\Controllers;

use App\Models\{MakeMaster,ModelMaster};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ModelMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = ModelMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('model', 'like', "%{$search}%");
        }	

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $models = $query->paginate(10)->appends($request->query());

        return view('masters.model.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():view
    {
        $makes = MakeMaster::all();
        return view('masters.model.create', compact('makes')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       /*
        $validated = $request->validate([
            'make_id' => ['required','integer',
	        Rule::unique('model_masters')->where(function ($query) {
                return $query->where('model', $this->model);
            }),],
        'model' => ['required',	'string',],
            'status' => 'required|in:1,0'
        ]);*/
        $validated = $request->validate([
            'make_id' => 'required|string|max:50',
            'model' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);
       // dd("XX");	
        try {
           // dd($validated);
           ModelMaster::create($validated);
           // dd("Success");
            return redirect()->route('model.index')
                           ->with('success', 'Model created successfully!');
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
    public function show(ModelMaster $modelMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModelMaster $model)
    {
       // dd($model->id);
        $makes = MakeMaster::all();
        return view('masters.model.edit',compact('model','makes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModelMaster $model)
    {
        $validated = $request->validate([
            'make_id' => 'required|string|max:50', 
            'model' => 'required|string|max:255', 
            'status' => 'required|in:1,0'
        ]);
        	
        try {
           // dd($validated);
           $model->update($validated);
           // dd("Success");
            return redirect()->route('model.index')
                           ->with('success', 'Model created successfully!');
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
    public function destroy(ModelMaster $modelMaster)
    {
        try {
            ModelMaster::where('id', $modelMaster->id)->update(['status' => 0]);
            return redirect()->route('make.index')
                            ->with('success', 'MakeMaster deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting MakeMaster: ' . $e->getMessage());
        }
    }
}
