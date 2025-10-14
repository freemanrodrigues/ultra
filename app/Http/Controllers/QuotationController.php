<?php

namespace App\Http\Controllers;

use App\Models\{
    Quotation,
    QuotationItem,
    QuotationItemTest,
    QuotationAttachment,
    QuotationApproval,
    UserDiscountLimit,
    CompanyMaster,
    CustomerMaster,
    SiteMaster,
    SampleType,
    TestMaster,
    SampleTypeRate
};
use Illuminate\Http\{Request, RedirectResponse, JsonResponse};
use Illuminate\View\View;
use Illuminate\Support\Facades\{DB, Log, Auth, Storage};
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class QuotationController extends Controller
{
    /**
     * Display a listing of quotations
     */
    public function index(Request $request): View
    {
        $query = Quotation::with(['company', 'customer', 'site', 'creator'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('quotation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('quotation_date', '<=', $request->date_to);
        }

        $quotations = $query->paginate(15)->appends($request->query());
        
        $companies = CompanyMaster::getCompanyArray();
        $statuses = config('constants.QUOTATION_STATUS');

        return view('quotations.index', compact('quotations', 'companies', 'statuses'));
    }

    /**
     * Show the form for creating a new quotation
     */
    public function create(): View
    {
        $companies = CompanyMaster::where('status', 1)->get();
        $sampleTypes = SampleType::where('status', 1)->get();
        $tests = TestMaster::where('status', 1)->get();
        
        // Get user's discount limit
        $userDiscountLimit = UserDiscountLimit::getUserMaxDiscount(Auth::id());
        
        // Generate quotation number
        $quotationNumber = Quotation::generateQuotationNumber();

        return view('quotations.create', compact(
            'companies',
            'sampleTypes',
            'tests',
            'userDiscountLimit',
            'quotationNumber'
        ));
    }

    /**
     * Store a newly created quotation
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:company_masters,id',
            'customer_id' => 'nullable|exists:customer_masters,id',
            'site_id' => 'nullable|exists:site_masters,id',
            'reference_number' => 'nullable|string|max:255',
            'quotation_date' => 'required|date',
            'validity_days' => 'required|integer|min:1|max:365',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'terms_and_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sample_type_id' => 'required|exists:sample_types,id',
            'items.*.sample_count' => 'required|integer|min:1',
            'items.*.sample_rate' => 'required|numeric|min:0',
            'items.*.test_total' => 'nullable|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tat_days' => 'nullable|integer|min:0',
            'items.*.description' => 'nullable|string',
            'items.*.tests' => 'nullable|array',
       //     'items.*.tests.*.test_id' => 'required|exists:test_masters,id',
            'items.*.tests.*.standard_rate' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Validate user discount limit
            if ($request->discount_percent > 0) {
                $userMaxDiscount = UserDiscountLimit::getUserMaxDiscount(Auth::id());
                if ($request->discount_percent > $userMaxDiscount) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "You can only apply up to {$userMaxDiscount}% discount. Please request approval for higher discount.");
                }
            }

            // Create quotation
            $quotation = new Quotation();
            $quotation->quotation_number = Quotation::generateQuotationNumber();
            $quotation->revision = 1;
            $quotation->company_id = $request->company_id;
            $quotation->customer_id = $request->customer_id;
            $quotation->site_id = $request->site_id;
            $quotation->reference_number = $request->reference_number;
            $quotation->quotation_date = $request->quotation_date;
            $quotation->validity_days = (int) $request->validity_days;
            $quotation->valid_from = $request->quotation_date;
            $quotation->valid_until = Carbon::parse($request->quotation_date)->addDays((int) $request->validity_days);
            $quotation->discount_percent = $request->discount_percent ?? 0;
            $quotation->tax_percent = $request->tax_percent ?? 18;
            $quotation->terms_and_conditions = $request->terms_and_conditions;
            $quotation->notes = $request->notes;
            $quotation->special_instructions = $request->special_instructions;
            $quotation->status = 1; // Draft
            $quotation->created_by = Auth::id();
            
            // Get addresses
            if ($request->customer_id) {
                $customer = CustomerMaster::find($request->customer_id);
                $quotation->billing_address = $customer->address ?? '';
                $quotation->shipping_address = $customer->address ?? '';
            }
            
            $quotation->save();

            // Create quotation items
            foreach ($request->items as $itemData) {
                $item = new QuotationItem();
                $item->quotation_id = $quotation->id;
                $item->sample_type_id = $itemData['sample_type_id'];
                $item->description = $itemData['description'] ?? '';
                $item->sample_count = $itemData['sample_count'];
                $item->sample_rate = $itemData['sample_rate'];
                $item->test_total = $itemData['test_total'] ?? 0;
                $item->discount_percent = $itemData['discount_percent'] ?? 0;
                $item->tat_days = $itemData['tat_days'] ?? null;
                $item->save(); // Totals will be calculated automatically

                // Create test associations
                if (isset($itemData['tests']) && is_array($itemData['tests'])) {
                    foreach ($itemData['tests'] as $testData) {
                        if (isset($testData['test_id'])) {
                            QuotationItemTest::create([
                                'quotation_id' => $quotation->id,
                                'quotation_item_id' => $item->id,
                                'test_id' => $testData['test_id'],
                                'standard_rate' => $testData['standard_rate'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            // Calculate totals
            $quotation->calculateTotals();

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('quotation_attachments', $filename, 'public');

                    QuotationAttachment::create([
                        'quotation_id' => $quotation->id,
                        'file_name' => $filename,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('quotation.show', $quotation->id)
                ->with('success', 'Quotation created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quotation creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating quotation: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified quotation
     */
    public function show(Quotation $quotation): View
    {
        $quotation->load([
            'company',
            'customer',
            'site',
            'items.sampleType',
            'items.tests.test',
            'attachments',
            'approvals.submitter',
            'approvals.approver',
            'creator',
            'approver'
        ]);

        return view('quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the quotation
     */
    public function edit(Quotation $quotation): View
    {
        // Only draft quotations can be edited
        if ($quotation->status != 1) {
            return redirect()->route('quotation.show', $quotation->id)
                ->with('error', 'Only draft quotations can be edited.');
        }

        $quotation->load(['items', 'attachments']);
        
        $companies = CompanyMaster::where('status', 1)->get();
        $sampleTypes = SampleType::where('status', 1)->get();
        $tests = TestMaster::where('status', 1)->get();
        
        $userDiscountLimit = UserDiscountLimit::getUserMaxDiscount(Auth::id());

        return view('quotations.edit', compact(
            'quotation',
            'companies',
            'sampleTypes',
            'tests',
            'userDiscountLimit'
        ));
    }

    /**
     * Update the specified quotation
     */
    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        if ($quotation->status != 1) {
            return redirect()->route('quotation.show', $quotation->id)
                ->with('error', 'Only draft quotations can be updated.');
        }

        $validated = $request->validate([
            'company_id' => 'required|exists:company_masters,id',
            'customer_id' => 'nullable|exists:customer_masters,id',
            'site_id' => 'nullable|exists:site_masters,id',
            'reference_number' => 'nullable|string|max:255',
            'quotation_date' => 'required|date',
            'validity_days' => 'required|integer|min:1|max:365',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'terms_and_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sample_type_id' => 'required|exists:sample_types,id',
            'items.*.test_id' => 'required|exists:test_masters,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tat_days' => 'nullable|integer|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Validate discount
            if ($request->discount_percent > 0) {
                $userMaxDiscount = UserDiscountLimit::getUserMaxDiscount(Auth::id());
                if ($request->discount_percent > $userMaxDiscount) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "You can only apply up to {$userMaxDiscount}% discount.");
                }
            }

            // Update quotation
            $quotation->company_id = $request->company_id;
            $quotation->customer_id = $request->customer_id;
            $quotation->site_id = $request->site_id;
            $quotation->reference_number = $request->reference_number;
            $quotation->quotation_date = $request->quotation_date;
            $quotation->validity_days = (int) $request->validity_days;
            $quotation->valid_from = $request->quotation_date;
            $quotation->valid_until = Carbon::parse($request->quotation_date)->addDays((int) $request->validity_days);
            $quotation->discount_percent = $request->discount_percent ?? 0;
            $quotation->tax_percent = $request->tax_percent ?? 18;
            $quotation->terms_and_conditions = $request->terms_and_conditions;
            $quotation->notes = $request->notes;
            $quotation->special_instructions = $request->special_instructions;
            $quotation->updated_by = Auth::id();
            
            // Update addresses
            if ($request->customer_id) {
                $customer = CustomerMaster::find($request->customer_id);
                $quotation->billing_address = $customer->address ?? '';
                $quotation->shipping_address = $customer->address ?? '';
            }
            
            $quotation->save();

            // Delete existing items and recreate
            $quotation->items()->delete();

            foreach ($request->items as $itemData) {
                $item = new QuotationItem();
                $item->quotation_id = $quotation->id;
                $item->sample_type_id = $itemData['sample_type_id'];
                $item->test_id = $itemData['test_id'];
                $item->description = $itemData['description'] ?? '';
                $item->quantity = $itemData['quantity'];
                $item->unit_price = $itemData['unit_price'];
                $item->discount_percent = $itemData['discount_percent'] ?? 0;
                $item->tat_days = $itemData['tat_days'] ?? null;
                $item->save();
            }

            // Recalculate totals
            $quotation->calculateTotals();

            // Handle new file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('quotation_attachments', $filename, 'public');

                    QuotationAttachment::create([
                        'quotation_id' => $quotation->id,
                        'file_name' => $filename,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('quotation.show', $quotation->id)
                ->with('success', 'Quotation updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quotation update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating quotation: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified quotation
     */
    public function destroy(Quotation $quotation): RedirectResponse
    {
        try {
            if ($quotation->status == 3 || $quotation->status == 4) {
                return redirect()->back()
                    ->with('error', 'Approved or converted quotations cannot be deleted.');
            }

            $quotation->delete();

            return redirect()->route('quotation.index')
                ->with('success', 'Quotation deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting quotation: ' . $e->getMessage());
        }
    }

    /**
     * Submit quotation for approval
     */
    public function submitForApproval(Quotation $quotation): RedirectResponse
    {
        try {
            if ($quotation->status != 1) {
                return redirect()->back()
                    ->with('error', 'Only draft quotations can be submitted for approval.');
            }

            DB::beginTransaction();

            $quotation->status = 2; // Pending Approval
            $quotation->save();

            // Create approval record
            QuotationApproval::create([
                'quotation_id' => $quotation->id,
                'submitted_by' => Auth::id(),
                'submitted_at' => now(),
                'status' => 1, // Pending
            ]);

            DB::commit();

            return redirect()->route('quotation.show', $quotation->id)
                ->with('success', 'Quotation submitted for approval successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error submitting quotation: ' . $e->getMessage());
        }
    }

    /**
     * Approve quotation
     */
    public function approve(Request $request, Quotation $quotation): RedirectResponse
    {
        $request->validate([
            'remarks' => 'nullable|string',
        ]);

        try {
            if ($quotation->status != 2) {
                return redirect()->back()
                    ->with('error', 'Only pending quotations can be approved.');
            }

            DB::beginTransaction();

            $quotation->status = 3; // Approved
            $quotation->approved_by = Auth::id();
            $quotation->approved_at = now();
            $quotation->save();

            // Update approval record
            $approval = $quotation->approvals()->where('status', 1)->latest()->first();
            if ($approval) {
                $approval->status = 2; // Approved
                $approval->approved_by = Auth::id();
                $approval->approved_at = now();
                $approval->remarks = $request->remarks;
                $approval->save();
            }

            DB::commit();

            return redirect()->route('quotation.show', $quotation->id)
                ->with('success', 'Quotation approved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error approving quotation: ' . $e->getMessage());
        }
    }

    /**
     * Reject quotation
     */
    public function reject(Request $request, Quotation $quotation): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        try {
            if ($quotation->status != 2) {
                return redirect()->back()
                    ->with('error', 'Only pending quotations can be rejected.');
            }

            DB::beginTransaction();

            $quotation->status = 1; // Back to Draft
            $quotation->save();

            // Update approval record
            $approval = $quotation->approvals()->where('status', 1)->latest()->first();
            if ($approval) {
                $approval->status = 3; // Rejected
                $approval->approved_by = Auth::id();
                $approval->approved_at = now();
                $approval->rejection_reason = $request->rejection_reason;
                $approval->save();
            }

            DB::commit();

            return redirect()->route('quotation.show', $quotation->id)
                ->with('success', 'Quotation rejected and sent back to draft.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error rejecting quotation: ' . $e->getMessage());
        }
    }

    /**
     * Create new revision
     */
    public function createRevision(Quotation $quotation): RedirectResponse
    {
        try {
            $newQuotation = $quotation->createRevision();

            return redirect()->route('quotation.edit', $newQuotation->id)
                ->with('success', 'New revision created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating revision: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF
     */
    public function generatePDF(Quotation $quotation)
    {
        $quotation->load([
            'company',
            'customer',
            'site',
            'items.sampleType',
            'items.test',
            'creator'
        ]);

        $pdf = PDF::loadView('quotations.pdf', compact('quotation'));
        
        return $pdf->download('quotation_' . $quotation->quotation_number . '.pdf');
    }

    /**
     * Preview PDF
     */
    public function previewPDF(Quotation $quotation)
    {
        $quotation->load([
            'company',
            'customer',
            'site',
            'items.sampleType',
            'items.test',
            'creator'
        ]);

        $pdf = PDF::loadView('quotations.pdf', compact('quotation'));
        
        return $pdf->stream();
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(QuotationAttachment $attachment)
    {
        if (!Storage::exists($attachment->file_path)) {
            return redirect()->back()
                ->with('error', 'File not found.');
        }

        return Storage::download($attachment->file_path, $attachment->original_name);
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment(QuotationAttachment $attachment): RedirectResponse
    {
        try {
            $quotationId = $attachment->quotation_id;
            $attachment->delete();

            return redirect()->route('quotation.show', $quotationId)
                ->with('success', 'Attachment deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting attachment: ' . $e->getMessage());
        }
    }

    /**
     * Get sample type rates (AJAX)
     */
    public function getSampleTypeRates(Request $request): JsonResponse
    {
        $sampleTypeId = $request->input('sample_type_id');
        
        if (!$sampleTypeId) {
            return response()->json(['error' => 'Sample type ID is required'], 400);
        }

        // Get tests through sample_type_rates table
     /*   $tests = DB::table('test_masters')
            ->join('sample_type_rates', 'test_masters.id', '=', 'sample_type_rates.test_id')
            ->where('sample_type_rates.sample_type_id', $sampleTypeId)
            ->where('sample_type_rates.is_active', true)
            ->where('test_masters.status', 1)
            ->select('test_masters.id', 'test_masters.test_name', 'sample_type_rates.rate as standard_test_rate')
            ->get(); */
         $tests = TestMaster::where('status',1)->where('sample_type_id', $sampleTypeId)->get(['id','test_name','standard_test_rate']);    
        return response()->json([
            'success' => true,
            'tests' => $tests->map(function($test) {
                return [
                    'id' => $test->id,
                    'test_name' => $test->test_name,
                    'standard_test_rate' => $test->standard_test_rate,
                ];
            })
        ]);
    }

    /**
     * Get customer sites (AJAX)
     */
    public function getCustomerSites(Request $request): JsonResponse
    {
        $customerId = $request->customer_id;

        $sites = SiteMaster::whereHas('customerSites', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })->get(['id', 'site_name']);

        return response()->json($sites);
    }

    /**
     * Change status
     */
    public function changeStatus(Request $request, Quotation $quotation): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:5,6', // Closed or Cancelled
        ]);

        try {
            $quotation->status = $request->status;
            $quotation->save();

            $statusName = config('constants.QUOTATION_STATUS')[$request->status];

            return redirect()->route('quotation.show', $quotation->id)
                ->with('success', "Quotation marked as {$statusName} successfully!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
}

