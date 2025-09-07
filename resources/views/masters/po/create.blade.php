<?php

<<<<<<< HEAD
namespace App\Http\Controllers;

use App\Models\{BottleType,CustomerMaster,EquipmentAssignment,POTestLine, SampleDetail,SampleMaster,SampleNature,SampleType,MakeModelMaster,SampleDetailTestAssignment};
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleDetailController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
=======
<style>
    /* Consistent styling with customer list page */
    .po-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
>>>>>>> 8dd86ed2aa0174eb2cdc67a417ff01e85e33211b
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleDetail $sampleDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleDetail $sampleDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleDetail $sampleDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleDetail $sampleDetail)
    {
        //
    }

    public function addSampleDetials($id):View
    {

        $make_models =MakeModelMaster::getMakeModel();
        $makes = MakeModelMaster::select('make')->distinct()->pluck('make');
        //dd($makes);
        $sample = SampleMaster::where('id',$id)->with('customer', 'customer_site_masters.siteMaster')->first();  

      // echo "<br> Customer  Id: ". $sample[0]->customer_id;
     //s  echo "<br> Customer Site Id: ". $sample[0]->customer_site_id;
     //  echo "<br> Customer Site Id: ". $sample[0]->customer_id;
        //die();

        $equipments = EquipmentAssignment::getSiteEquipmentList($sample->customer_site_id);
        $sample_natures = SampleNature::getSampleNatureArray();
        $sample_types = SampleType::getSampleTypeArray();
        $bottle_types = BottleType::getBottleTypeArray();

        return view('add-sample-details',compact('sample','equipments','sample_types','sample_natures','bottle_types','make_models','makes'));
        // ,'devices','sample_types','sample_natures','bottle_types'
    }
    // Delete 
    
<<<<<<< HEAD

    public function saveSampleDetials(Request $request)
    {
       // dd($request->all());
=======
    .sample-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .test-item {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .test-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }
    
    /* Remove button styling */
    .btn-remove {
        padding: 0.1rem 0.3rem;
        font-size: 10px;
        line-height: 1;
        min-width: 20px;
        height: 20px;
    }
    
    /* Add button styling */
    .btn-add {
        padding: 0.25rem 0.5rem;
        font-size: 11px;
        line-height: 1.2;
    }
    
    /* Test checkbox styling */
    .tests-checkbox-container {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        max-height: 200px;
        overflow-y: auto;
    }
    
    .form-check {
        margin-bottom: 0.5rem;
    }
    
    .form-check-input {
        margin-top: 0.25rem;
    }
    
    .form-check-label {
        font-size: 12px;
        line-height: 1.4;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .po-page h1 {
            font-size: 1.1rem;
        }
>>>>>>> 8dd86ed2aa0174eb2cdc67a417ff01e85e33211b
        
       
        if(!empty($request['device_id'])) {
            $samples =  SampleMaster::where('id', $request->sample_id)->get();
            foreach($request['device_id'] as $k => $device_id){

<<<<<<< HEAD
           if($device_id == 'New') {
            // Add Device
           }
            
            if(!empty($request->image[$k])) {
               
                $image = $request->image[$k];
                $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $uploadPath = 'images/uploads'; // This will be storage/app/public/images/uploads
                $image_path = $image->storeAs($uploadPath, $fileName, 'public');
        
                // 6. Get the public URL for display
               // $imageUrl = Storage::url($path); 
            } else {
                $image_path = '';
            }
            if(!empty($request->invoice[$k])) {
                
            }
            $customer =  CustomerMaster::getCountryId($samples[0]->customer_id);
               $smd = new SampleDetail();
                $smd->sample_id = $request->sample_id;
                $smd->equipment_assignments_id = $device_id;
                $smd->company_id  = $customer->company_id; 
                $smd->customer_id  = $samples[0]->customer_id;
                $smd->customer_site_id  = $samples[0]->customer_site_id;
                $smd->type_of_sample  = $request->sample_type[$k];
                $smd->nature_of_sample  = $request->nature_of_Sample[$k]??NULL;
                $smd->running_hrs  = $request->running_hrs[$k]??NULL;
                $smd->sub_asy_no  = $request->sub_asy_no[$k]??NULL;
                $smd->sub_asy_hrs  = $request->sub_asy_hrs[$k]??NULL;
                $smd->sampling_date  = $request->sampling_date[$k]??NULL;
                $smd->brand_of_oil  = $request->brand_of_oil[$k]??NULL;
                $smd->grade  = $request->grade[$k]??NULL;
                $smd->lube_oil_running_hrs  = $request->lube_oil_running_hrs[$k]??NULL;
                $smd->top_up_volume  = $request->top_up_volume[$k]??NULL;
                $smd->sump_capacity  = $request->sump_capacity[$k]??NULL;
                $smd->sampling_from  = $request->sampling_from[$k]??NULL;
           //     $smd->report_expected_date  = $request->report_expected_date[$k];
                $smd->qty  = $request->qty[$k]??NULL;
                $smd->bottle_types_id  = $request->type_of_bottle[$k]??NULL;
           //     $smd->problem  = $request->problem[$k];
           //     $smd->comments  = $request->comments[$k];
            //    $smd->customer_note  = $request->customer_note[$k];
                $smd->severity  = $request->severity[$k]??NULL;
                $smd->oil_drained  = $request->oil_drained[$k]??NULL;
           //     $smd->image  = $image_path;
           //     $smd->fir  = $request->fir[$k]??NULL;
          //      $smd->invoice  = $request->invoice[$k];
                $smd->save();
=======
<div class="container-fluid po-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-file-invoice me-2"></i> Create Purchase Order
            </h1>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('po.store') }}" id="poForm">
        @csrf
        
        <!-- PO Basic Information -->
        <div class="card shadow-sm compact-form">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>PO Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Company Selection -->
                    <div class="col-md-6">
                        <label for="company_id" class="form-label">Company Name *</label>
                        <select class="form-select" id="company_id" name="company_id" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                        data-company-name="{{ $company->company_name }}">
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Customer Site Selection -->
                    <div class="col-md-6">
                        <label for="site_id" class="form-label">Customer Site</label>
                        <select class="form-select" id="site_id" name="site_id" disabled>
                            <option value="">Select Company First</option>
                        </select>
                    </div>
                    
                    <!-- PO Number -->
                    <div class="col-md-4">
                        <label for="po_number" class="form-label">PO Number *</label>
                        <input type="text" class="form-control" id="po_number" name="po_number" 
                               placeholder="Enter PO Number" required>
                    </div>
                    
                    <!-- PO Date -->
                    <div class="col-md-4">
                        <label for="po_date" class="form-label">PO Date *</label>
                        <input type="date" class="form-control" id="po_date" name="po_date" required>
                    </div>
                    
                    <!-- Valid From Date -->
                    <div class="col-md-4">
                        <label for="valid_from" class="form-label">Valid From *</label>
                        <input type="date" class="form-control" id="valid_from" name="valid_from" required>
                    </div>
                    
                    <!-- Valid To Date -->
                    <div class="col-md-4">
                        <label for="valid_to" class="form-label">Valid To *</label>
                        <input type="date" class="form-control" id="valid_to" name="valid_to" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Samples Section -->
        <div class="card shadow-sm compact-form">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Samples</h5>
                <button type="button" class="btn btn-primary btn-add" id="addSampleBtn">
                    <i class="fas fa-plus me-1"></i>Add Sample
                </button>
            </div>
            <div class="card-body">
                <div id="samplesContainer">
                    <!-- Sample items will be added here dynamically -->
                </div>
            </div>
        </div>

        <!-- Billing Summary -->
        <div class="card shadow-sm compact-form" id="billingSummary" style="display: none;">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Billing Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div id="sampleTypeSummary">
                            <!-- Sample type summaries will be populated here -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Total Summary</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Total Samples:</span>
                                    <span id="totalSamples">0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Sample Amount:</span>
                                    <span id="totalSampleAmount">₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total Tests:</span>
                                    <span id="totalTestAmount">0</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Grand Total:</span>
                                    <span id="grandTotal">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('po.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Create PO
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const siteSelect = document.getElementById('site_id');
    const addSampleBtn = document.getElementById('addSampleBtn');
    const samplesContainer = document.getElementById('samplesContainer');
    
    let sampleCounter = 0;
    
    // Enable site selection when company is selected
    document.getElementById('company_id').addEventListener('change', function() {
        const companyId = this.value;
        siteSelect.disabled = !companyId;
        if (companyId) {
            siteSelect.innerHTML = '<option value="">Select Site (Optional)</option>';
            // Load sites for the selected company
            loadSitesForCompany(companyId);
        } else {
            siteSelect.innerHTML = '<option value="">Select Company First</option>';
        }
    });
    
    // Function to load sites for a company
    function loadSitesForCompany(companyId) {
        // This would typically make an AJAX call to fetch sites
        // For now, we'll just enable the field
        siteSelect.disabled = false;
    }
    
    // Add sample button click
    addSampleBtn.addEventListener('click', function() {
        addSample();
    });
    
    // Function to add a new sample
    function addSample() {
        sampleCounter++;
        const sampleDiv = document.createElement('div');
        sampleDiv.className = 'sample-item';
        sampleDiv.setAttribute('data-sample-index', sampleCounter);
        sampleDiv.innerHTML = `
            <div class="sample-item-header">
                <h6 class="mb-0">Sample ${sampleCounter}</h6>
                <button type="button" class="btn btn-danger btn-remove" onclick="removeSample(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Sample Type *</label>
                    <select class="form-select sample-type-select" name="samples[${sampleCounter}][sample_type_id]" required onchange="onSampleTypeChange(this, ${sampleCounter})">
                        <option value="">Select Sample Type</option>
                        @foreach($sampleTypes as $sampleType)
                            <option value="{{ $sampleType->id }}">{{ $sampleType->sample_type_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sample Count *</label>
                    <input type="number" class="form-control sample-count" name="samples[${sampleCounter}][sample_count]" 
                           min="1" value="1" required onchange="updateSampleTotal(this, ${sampleCounter})">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sample Rate *</label>
                    <input type="number" class="form-control sample-rate" name="samples[${sampleCounter}][sample_rate]" 
                           step="0.01" min="0" value="0.00" required onchange="updateSampleTotal(this, ${sampleCounter})">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sample Total</label>
                    <input type="text" class="form-control sample-total" readonly value="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Standard Test Total</label>
                    <input type="number" class="form-control standard-test-total" name="samples[${sampleCounter}][standard_test_total]" 
                           step="0.01" min="0" value="0.00" onchange="updateBillingSummary()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="samples[${sampleCounter}][description]" 
                           placeholder="Description">
                </div>
            </div>
            <div class="mt-2">
                <label class="form-label fw-bold">Select Tests for this Sample Type:</label>
                <div class="tests-checkbox-container mt-2" id="tests-${sampleCounter}">
                    <!-- Test checkboxes will be loaded here based on sample type -->
            </div>
            </div>
        `;
        samplesContainer.appendChild(sampleDiv);
    }
    
    // Function to load tests for sample type
    function loadTestsForSampleType(sampleIndex, sampleTypeId) {
        const testsContainer = document.getElementById(`tests-${sampleIndex}`);
        
        if (!sampleTypeId) {
            testsContainer.innerHTML = '<p class="text-muted">Please select a sample type first</p>';
            return;
        }
        
        // Show loading
        testsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading tests...</div>';
        
        // Fetch sample type rates
        fetch(`/api/sample-type-rates?sample_type_id=${sampleTypeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.tests.length > 0) {
                    testsContainer.innerHTML = data.tests.map(test => `
                        <div class="form-check mb-2">
                            <input class="form-check-input test-checkbox" type="checkbox" 
                                   name="samples[${sampleIndex}][tests][${test.id}][test_id]" 
                                   value="${test.id}" 
                                   id="test_${sampleIndex}_${test.id}"
                                   data-standard-rate="${test.standard_test_rate}"
                                   onchange="updateStandardTestTotal(${sampleIndex}); updateBillingSummary()">
                            <label class="form-check-label" for="test_${sampleIndex}_${test.id}">
                                <strong>${test.test_name}</strong> <span class="text-muted">(₹${parseFloat(test.standard_test_rate).toFixed(2)})</span>
                            </label>
                        </div>
                    `).join('');
                } else {
                    testsContainer.innerHTML = '<p class="text-muted">No tests available for this sample type</p>';
                }
            })
            .catch(error => {
                console.error('Error loading tests:', error);
                testsContainer.innerHTML = '<p class="text-danger">Error loading tests</p>';
            });
    }
    
    // Function to remove a sample
    window.removeSample = function(button) {
        button.closest('.sample-item').remove();
        updateSampleNumbers();
        updateBillingSummary();
    };
    
    

    // Function to update sample total
    window.updateSampleTotal = function(input, sampleIndex) {
        const sampleItem = input.closest('.sample-item');
        const count = parseFloat(sampleItem.querySelector('.sample-count').value) || 0;
        const rate = parseFloat(sampleItem.querySelector('.sample-rate').value) || 0;
        const total = count * rate;
        sampleItem.querySelector('.sample-total').value = total.toFixed(2);
        updateBillingSummary();
    };

    // Function to update standard test total
    window.updateStandardTestTotal = function(sampleIndex) {
        const sampleItem = document.querySelector(`[data-sample-index="${sampleIndex}"]`);
        if (!sampleItem) return;
        
        const testCheckboxes = sampleItem.querySelectorAll('.test-checkbox:checked');
        let total = 0;
        
        testCheckboxes.forEach(checkbox => {
            const standardRate = parseFloat(checkbox.getAttribute('data-standard-rate')) || 0;
            total += standardRate;
        });
        
        const standardTestTotalInput = sampleItem.querySelector('.standard-test-total');
        if (standardTestTotalInput) {
            standardTestTotalInput.value = total.toFixed(2);
        }
    };

    // Function to handle sample type change
    window.onSampleTypeChange = function(select, sampleIndex) {
        const sampleTypeId = select.value;
        loadTestsForSampleType(sampleIndex, sampleTypeId);
        updateBillingSummary();
    };


    // Function to update billing summary
    function updateBillingSummary() {
        const samples = samplesContainer.querySelectorAll('.sample-item');
        const sampleTypeSummary = document.getElementById('sampleTypeSummary');
        const billingSummary = document.getElementById('billingSummary');
        
        if (samples.length === 0) {
            billingSummary.style.display = 'none';
            return;
        }
        
        billingSummary.style.display = 'block';
        
        // Group samples by sample type
        const sampleTypeGroups = {};
        let totalSamples = 0;
        let totalSampleAmount = 0;
        let totalTestCount = 0;
        
        samples.forEach(sample => {
            const sampleTypeSelect = sample.querySelector('.sample-type-select');
            const sampleTypeId = sampleTypeSelect.value;
            const sampleTypeName = sampleTypeSelect.options[sampleTypeSelect.selectedIndex].text;
            
            if (!sampleTypeId) return;
            
            if (!sampleTypeGroups[sampleTypeId]) {
                sampleTypeGroups[sampleTypeId] = {
                    name: sampleTypeName,
                    samples: 0,
                    sampleAmount: 0,
                    testCount: 0
                };
            }
            
            const count = parseFloat(sample.querySelector('.sample-count').value) || 0;
            const rate = parseFloat(sample.querySelector('.sample-rate').value) || 0;
            const sampleTotal = count * rate;
            
            // Count selected tests (no rates)
            const testCheckboxes = sample.querySelectorAll('.test-checkbox:checked');
            const testCount = testCheckboxes.length;
            
            sampleTypeGroups[sampleTypeId].samples += count;
            sampleTypeGroups[sampleTypeId].sampleAmount += sampleTotal;
            sampleTypeGroups[sampleTypeId].testCount += testCount;
            
            totalSamples += count;
            totalSampleAmount += sampleTotal;
            totalTestCount += testCount;
        });
        
        // Update sample type summary
        sampleTypeSummary.innerHTML = Object.values(sampleTypeGroups).map(group => `
            <div class="card mb-2">
                <div class="card-body py-2">
                    <h6 class="card-title mb-1">${group.name}</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Samples: ${group.samples}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Tests: ${group.testCount}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="fw-bold">Amount: ₹${group.sampleAmount.toFixed(2)}</small>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Update totals
        document.getElementById('totalSamples').textContent = totalSamples;
        document.getElementById('totalSampleAmount').textContent = `₹${totalSampleAmount.toFixed(2)}`;
        document.getElementById('totalTestAmount').textContent = totalTestCount;
        document.getElementById('grandTotal').textContent = `₹${totalSampleAmount.toFixed(2)}`;
    }
    
    // Function to update sample numbers
    function updateSampleNumbers() {
        const samples = samplesContainer.querySelectorAll('.sample-item');
        samples.forEach((sample, index) => {
            const header = sample.querySelector('.sample-item-header h6');
            header.textContent = `Sample ${index + 1}`;
            
            // Update sample index in form names
            const sampleInputs = sample.querySelectorAll('select, input');
            sampleInputs.forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/samples\[\d+\]/, `samples[${index + 1}]`);
                }
            });
            
            // Update test checkbox names
            const testCheckboxes = sample.querySelectorAll('.test-checkbox');
            testCheckboxes.forEach(checkbox => {
                if (checkbox.name) {
                    // Extract test_id from the current name and update the sample index
                    const testId = checkbox.value;
                    checkbox.name = `samples[${index + 1}][tests][${testId}][test_id]`;
                }
            });
        });
        sampleCounter = samples.length;
    }
    
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('po_date').value = today;
    document.getElementById('valid_from').value = today;
    
    // Add one sample by default
    addSample();
});
</script>
>>>>>>> 8dd86ed2aa0174eb2cdc67a417ff01e85e33211b

                // Assign the test for the Sample
                $po_id = $samples[0]->work_order;
              
               $test_lists = POTestLine::getTestList($po_id, $customer->company_id, $request->sample_id );
              
               foreach($test_lists as $test) {
                SampleDetailTestAssignment::create(['sample_details_id' =>$smd->id,'test_id' => $test->test_id]);
            }
            return redirect()->route('sample.index')
                           ->with('success', 'SampleDetails added successfully!');
        }

    }
    }
}
