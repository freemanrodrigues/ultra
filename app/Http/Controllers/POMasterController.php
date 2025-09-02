<?php

namespace App\Http\Controllers;



use App\Models\{CompanyMaster,CustomerMaster, CustomerSiteMaster, SampleType, TestMaster, POMaster, POSample, POTest};
use Illuminate\Http\{Request, RedirectResponse, JsonResponse};

use Illuminate\View\View;
use Illuminate\Support\Facades\{DB, Log};


class POMasterController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
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
    public function create(): View
    {
       $sampleTypes = SampleType::getSampleType();
       $tests = TestMaster::where('status',1)->get();
       $companies= CompanyMaster::where('status',1)->get();
            $customers = CustomerMaster::where('status', 1)
            ->orderBy('customer_name')
            ->get();
        return view('masters.po.create',compact('sampleTypes','tests','companies','customers'));

    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customer_masters,id',
            'site_id' => 'nullable|exists:site_masters,id',
            'po_number' => 'required|string|max:255|unique:po_masters,po_number',
            'po_date' => 'required|date',
            'po_start_date' => 'required|date',
            'po_end_date' => 'required|date|after:po_start_date',
 	   'currency' => 'nullable|string',
            'test_rate' => 'nullable|integer',
            'test_limit' => 'nullable|integer',
           
            'samples' => 'required|array|min:1',
            'samples.*.sample_type_id' => 'required|exists:sample_types,id',
            'samples.*.description' => 'nullable|string|max:500',
            'samples.*.tests' => 'required|array|min:1',
            'samples.*.tests.*.test_id' => 'required|exists:test_masters,id',
           'samples.*.tests.*.price' => 'required|numeric|min:0',
            'samples.*.tests.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Create PO
            $po = POMaster::create([
                'customer_id' => $request->customer_id,
                'site_id' => $request->site_id,
                'po_number' => $request->po_number,
                'po_date' => $request->po_date,
                'po_start_date' => $request->po_start_date,
                'po_end_date' => $request->po_end_date,
                'status' => 'active',
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            // Process samples and tests
            foreach ($request->samples as $sampleData) {
                $sample = POSample::create([
                    'po_id' => $po->id,
                    'sample_type_id' => $sampleData['sample_type_id'],
                    'description' => $sampleData['description'] ?? null,
                ]);

                // Process tests for this sample
                foreach ($sampleData['tests'] as $testData) {
                    $testTotal = $testData['price'] * $testData['quantity'];
                    $totalAmount += $testTotal;

                    POTest::create([
                        'po_sample_id' => $sample->id,
                        'test_id' => $testData['test_id'],
                        'price' => $testData['price'],
                        'quantity' => $testData['quantity'],
                        'total' => $testTotal,
                    ]);
                }
            }

            // Update PO total amount
            $po->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('po.show', $po)
                ->with('success', 'Purchase Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PO creation failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create Purchase Order. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(POMaster $po): View
    {
        $po->load(['customer', 'site', 'samples.tests.test', 'samples.sampleType']);
        
        return view('masters.po.show', compact('po'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(POMaster $po): View
    {
        $customers = CustomerMaster::where('status', 1)
            ->orderBy('customer_name')
            ->get();
            
        $sampleTypes = SampleType::where('status', 1)
            ->orderBy('sample_type_name')
            ->get();
            
        $tests = TestMaster::where('active', 1)
            ->orderBy('test_name')
            ->get();
            
        $po->load(['samples.tests.test', 'samples.sampleType']);
        
        return view('masters.po.edit', compact('po', 'customers', 'sampleTypes', 'tests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, POMaster $po): RedirectResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customer_masters,id',
            'site_id' => 'required|exists:site_masters,id',
            'po_number' => 'required|string|max:255|unique:po_masters,po_number,' . $po->id,
            'po_date' => 'required|date',
            'po_start_date' => 'required|date',
            'po_end_date' => 'required|date|after:po_start_date',
            'samples' => 'required|array|min:1',
            'samples.*.sample_type_id' => 'required|exists:sample_types,id',
            'samples.*.description' => 'nullable|string|max:500',
            'samples.*.tests' => 'required|array|min:1',
            'samples.*.tests.*.test_id' => 'required|exists:test_masters,id',
            'samples.*.tests.*.price' => 'required|numeric|min:0',
            'samples.*.tests.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Update PO basic info
            $po->update([
                'customer_id' => $request->customer_id,
                'site_id' => $request->site_id,
                'po_number' => $request->po_number,
                'po_date' => $request->po_date,
                'po_start_date' => $request->po_start_date,
                'po_end_date' => $request->po_end_date,
            ]);

            // Delete existing samples and tests
            $po->samples()->each(function($sample) {
                $sample->tests()->delete();
            });
            $po->samples()->delete();

            $totalAmount = 0;

            // Process new samples and tests
            foreach ($request->samples as $sampleData) {
                $sample = POSample::create([
                    'po_id' => $po->id,
                    'sample_type_id' => $sampleData['sample_type_id'],
                    'description' => $sampleData['description'] ?? null,
                ]);

                // Process tests for this sample
                foreach ($sampleData['tests'] as $testData) {
                    $testTotal = $testData['price'] * $testData['quantity'];
                    $totalAmount += $testTotal;

                    POTest::create([
                        'po_sample_id' => $sample->id,
                        'test_id' => $testData['test_id'],
                        'price' => $testData['price'],
                        'quantity' => $testData['quantity'],
                        'total' => $testTotal,
                    ]);
                }
            }

            // Update PO total amount
            $po->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('po.show', $po)
                ->with('success', 'Purchase Order updated successfully!');

        } catch (\Exception $e) {
 // Update PO total amount
            $po->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('po.show', $po)
                ->with('success', 'Purchase Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PO update failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->withErrors(['error' => 'Failed to update Purchase Order. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(POMaster $po): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Delete samples and tests
            $po->samples()->each(function($sample) {
                $sample->tests()->delete();
            });
            $po->samples()->delete();

            // Delete PO
            $po->delete();

            DB::commit();

            return redirect()->route('po.index')
                ->with('success', 'Purchase Order deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PO deletion failed: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Failed to delete Purchase Order. Please try again.']);
        }
    }

    /**
     * Get customer sites for API
     */
    public function getCustomerSites($customerId): JsonResponse
    {
        $sites = CustomerSiteMaster::where('customer_id', $customerId)
            ->with('siteMaster')
            ->get()
            ->map(function($customerSite) {
                return [
                    'id' => $customerSite->site_master_id,
                    'site_name' => $customerSite->siteMaster->site_name,
                ];
            });

        return response()->json($sites);
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
