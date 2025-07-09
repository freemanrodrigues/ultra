<?php

namespace App\Http\Controllers;

use App\Models\GradeMaster;
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;


class GradeMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = GradeMaster::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('grade_code', 'like', "%{$search}%")
                  ->orWhere('grade_name', 'like', "%{$search}%");
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

        $grades = $query->paginate(10)->appends($request->query());

        return view('masters.grade.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.grade.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
         // dd($request->all());
         $validated = $request->validate([
            'grade_code' => 'required|string|max:50|unique:grade_masters,grade_code',
            'grade_name' => 'required|string|max:255',
            'status' => 'required|in:1,0'
        ]);

        try {
           // dd($validated);
           GradeMaster::create($validated);
           // dd("Success");
            return redirect()->route('grade.index')
                           ->with('success', 'Grade created successfully!');
        } catch (\Exception $e) {
            dd("Fail". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating Grade: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GradeMaster $gradeMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GradeMaster $gradeMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GradeMaster $gradeMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GradeMaster $gradeMaster)
    {
        //
    }
}
