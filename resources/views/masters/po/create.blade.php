@extends('/layouts/master-layout')
@section('content')

<style>
    /* Consistent styling with customer list page */
    .po-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
    }
    
    .po-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .po-page .form-control,
    .po-page .form-select,
    .po-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Form elements height consistency */
    .form-control, .form-select, .btn {
        height: 32px !important;
        padding: 0.25rem 0.5rem;
    }
    
    /* Distinct header background */
    .page-header {
        background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Compact spacing */
    .compact-form {
        margin-bottom: 0.5rem;
    }
    
    .card-body {
        padding: 0.5rem !important;
    }
    
    /* Sample item styling */
    .sample-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
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
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>

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
                if (data.success && data.rates.length > 0) {
                    testsContainer.innerHTML = data.rates.map(rate => `
                        <div class="form-check mb-2">
                            <input class="form-check-input test-checkbox" type="checkbox" 
                                   name="samples[${sampleIndex}][tests][${rate.test_id}][test_id]" 
                                   value="${rate.test_id}" 
                                   id="test_${sampleIndex}_${rate.test_id}"
                                   onchange="updateBillingSummary()">
                            <label class="form-check-label" for="test_${sampleIndex}_${rate.test_id}">
                                <strong>${rate.test_name}</strong>
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

@endsection

