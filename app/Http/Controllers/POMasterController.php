<?php

namespace App\Http\Controllers;

use App\Models\{CompanyMaster,POMaster,POTestLine,SampleType,TestMaster};
use Illuminate\Http\{Request,RedirectResponse,JsonResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class POMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = DB::table('po_masters')
            ->join('company_masters', 'po_masters.party_id', '=', 'company_masters.id')
            ->leftJoin('site_masters', 'po_masters.site_id', '=', 'site_masters.id')
            ->select(
                'po_masters.*',
                'company_masters.company_name as party_name',
                'site_masters.site_name as site_name'
            );

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
             $query->where('po_number', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('po_masters.status', $request->get('status'));
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'po_masters.created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $pos = $query->paginate(10)->appends($request->query());

        return view('masters.po.index', compact('pos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $sampleTypes = SampleType::getSampleType();
       $tests = TestMaster::where('status',1)->get();
       $companies= CompanyMaster::where('status',1)->get();
        return view('masters.po.create',compact('sampleTypes','tests','companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_masters,po_number',
            'po_date' => 'nullable|date',
            'customer_id' => 'required|integer',
            'site_id' => 'nullable|integer',
            'po_start_date' => 'required|date',
            'po_end_date' => 'required|date',
            'currency' => 'nullable|string',
            'test_rate' => 'nullable|integer',
            'test_limit' => 'nullable|integer',
             'samples' => 'required|array',
             'samples.*.sample_type_id' => 'required|integer',
             'samples.*.description' => 'nullable|string',
             'samples.*.tests' => 'required|array',
             'samples.*.tests.*' => 'integer',
        ]);

        try {
      //      DB::transaction(function () use ($validated, $request) {
                $poMaster = POMaster::create([
                    'po_number' => $validated['po_number'],
                    'po_date' => $validated['po_date'],
                    'party_id' => $validated['customer_id'],
                    'site_id' => $validated['site_id']??NULL,
                    'valid_from' => $validated['po_start_date'],
                    'valid_to' => $validated['po_end_date'],
                    'currency' => $validated['currency'],
                    'test_rate' =>$validated['test_rate'],
                    'test_limit' => $validated['test_limit'],
    
                    'status' => 1, // Assuming default status, adjust as needed
                ]);

                foreach ($validated['samples'] as $sampleData) {
                    foreach ($sampleData['tests'] as $testId) {
                         $arr = [
                           
                            'sample_type_id' => $sampleData['sample_type_id'],
                            'test_id' => $testId,
                             'po_master_id' => $poMaster->id,
                        ];

                        POTestLine::create($arr);
                    }
                }
      //      });

            return redirect()->route('po.index')
                           ->with('success', 'PO created successfully!');
        } catch (\Exception $e) {

        //    dd($e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error creating PO: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(POMaster $pOMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(POMaster $po)
    {

         $sample_types = SampleType::getSampleType();
        return view('masters.po.edit',compact('sample_types','po'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, POMaster $po)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255|unique:po_masters,po_number,'.$po->id,
            'po_date' => 'nullable|date',
            'party_id' => 'required|integer',
            'status' => 'required|integer',
            'site_id' => 'nullable|integer',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'currency' => 'required|string',
            'sample_type_id' => 'required|integer',
            'test_rate' => 'required|integer',
            'test_limit' => 'required|integer',	
        ]);
			
        try {
           $po->update($validated);
            return redirect()->route('po.index')
                           ->with('success', 'PO updated successfully!');
        } catch (\Exception $e) {
         //   dd("Fail ". $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error updating PO: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(POMaster $pOMaster)
    {
        //
    }

    public function getPoTests($id): JsonResponse
    {
        $tests = DB::table('po_test_lines')
            ->join('sample_types', 'po_test_lines.sample_type_id', '=', 'sample_types.id')
            ->join('test_masters', 'po_test_lines.test_id', '=', 'test_masters.id')
            ->where('po_test_lines.po_master_id', $id)
            ->select('sample_types.sample_type_name', 'test_masters.test_name')
            ->get()
            ->groupBy('sample_type_name');

        return response()->json($tests);
    }
}
