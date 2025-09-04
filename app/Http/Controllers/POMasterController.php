<?php

namespace App\Http\Controllers;


use App\Models\{CompanyMaster,CustomerMaster, CustomerSiteMaster, SampleType, TestMaster, POMaster, POSample, POTest, SampleTypeRate};
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
        $pos = POMaster::with(['customer', 'site'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
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
        
        // Get sample type rates for each sample type
        $sampleTypeRates = [];
        foreach ($sampleTypes as $sampleType) {
            $sampleTypeRates[$sampleType->id] = SampleTypeRate::getRatesForSampleType($sampleType->id);
        }
        
        return view('masters.po.create',compact('sampleTypes','tests','companies','customers','sampleTypeRates'));
    }

    /**
     * Get sample type rates for a specific sample type
     */
    public function getSampleTypeRates(Request $request): JsonResponse
    {
        $sampleTypeId = $request->input('sample_type_id');
        
        if (!$sampleTypeId) {
            return response()->json(['error' => 'Sample type ID is required'], 400);
        }

        $rates = SampleTypeRate::getRatesForSampleType($sampleTypeId);
        
        return response()->json([
            'success' => true,
            'rates' => $rates->map(function($rate) {
                return [
                    'id' => $rate->id,
                    'test_id' => $rate->test_id,
                    'test_name' => $rate->test->test_name,
                    'rate' => $rate->rate,
                    'notes' => $rate->notes,
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_id' => 'required|exists:company_masters,id',
            'site_id' => 'nullable|exists:site_masters,id',
            'po_number' => 'required|string|max:255|unique:po_masters,po_number',
            'po_date' => 'required|date',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'samples' => 'required|array|min:1',
            'samples.*.sample_type_id' => 'required|exists:sample_types,id',
            'samples.*.description' => 'nullable|string|max:500',
            'samples.*.sample_count' => 'required|integer|min:1',
            'samples.*.sample_rate' => 'required|numeric|min:0',
            'samples.*.tests' => 'required|array|min:1',
            'samples.*.tests.*.test_id' => 'required|exists:test_masters,id',
        ]);

        try {
            DB::beginTransaction();

            // Create PO
            $po = POMaster::create([
                'company_id' => $request->company_id,
                'site_id' => $request->site_id ?: null,
                'po_number' => $request->po_number,
                'po_date' => $request->po_date,
                'valid_from' => $request->valid_from,
                'valid_to' => $request->valid_to,
                'status' => 'active',
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            // Process samples and tests
            foreach ($request->samples as $sampleData) {
                $sampleTotal = $sampleData['sample_count'] * $sampleData['sample_rate'];
                $totalAmount += $sampleTotal;

                $sample = POSample::create([
                    'po_id' => $po->id,
                    'sample_type_id' => $sampleData['sample_type_id'],
                    'description' => $sampleData['description'] ?? null,
                    'sample_count' => $sampleData['sample_count'],
                    'sample_rate' => $sampleData['sample_rate'],
                    'sample_total' => $sampleTotal,
                ]);

                // Process tests for this sample
                foreach ($sampleData['tests'] as $testData) {
                    // Process tests (no rates, just test selection)
                    POTest::create([
                        'po_sample_id' => $sample->id,
                        'test_id' => $testData['test_id'],
                        'price' => 0, // No test rates
                        'quantity' => 1,
                        'total' => 0, // No test rates
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
            'company_id' => 'required|exists:company_masters,id',
            'site_id' => 'nullable|exists:site_masters,id',
            'po_number' => 'required|string|max:255|unique:po_masters,po_number,' . $po->id,
            'po_date' => 'required|date',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'samples' => 'required|array|min:1',
            'samples.*.sample_type_id' => 'required|exists:sample_types,id',
            'samples.*.description' => 'nullable|string|max:500',
            'samples.*.sample_count' => 'required|integer|min:1',
            'samples.*.sample_rate' => 'required|numeric|min:0',
            'samples.*.tests' => 'required|array|min:1',
            'samples.*.tests.*.test_id' => 'required|exists:test_masters,id',
        ]);

        try {
            DB::beginTransaction();

            // Update PO basic info
            $po->update([
                'company_id' => $request->company_id,
                'site_id' => $request->site_id ?: null,
                'po_number' => $request->po_number,
                'po_date' => $request->po_date,
                'valid_from' => $request->valid_from,
                'valid_to' => $request->valid_to,
            ]);

            // Delete existing samples and tests
            $po->samples()->each(function($sample) {
                $sample->tests()->delete();
            });
            $po->samples()->delete();

            $totalAmount = 0;

            // Process new samples and tests
            foreach ($request->samples as $sampleData) {
                $sampleTotal = $sampleData['sample_count'] * $sampleData['sample_rate'];
                $totalAmount += $sampleTotal;

                $sample = POSample::create([
                    'po_id' => $po->id,
                    'sample_type_id' => $sampleData['sample_type_id'],
                    'description' => $sampleData['description'] ?? null,
                    'sample_count' => $sampleData['sample_count'],
                    'sample_rate' => $sampleData['sample_rate'],
                    'sample_total' => $sampleTotal,
                ]);

                // Process tests for this sample
                foreach ($sampleData['tests'] as $testData) {
                    // Process tests (no rates, just test selection)
                    POTest::create([
                        'po_sample_id' => $sample->id,
                        'test_id' => $testData['test_id'],
                        'price' => 0, // No test rates
                        'quantity' => 1,
                        'total' => 0, // No test rates
                    ]);
                }
            }

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
}
