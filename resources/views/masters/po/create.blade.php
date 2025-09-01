@extends('/layouts/master-layout')
@section('content')

<link rel="stylesheet" href="{{ asset('css/po/po.css')}}">

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
                    <!-- Customer Selection -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Customer Name *</label>
                        <select class="form-select" id="customer_id" name="customer_id" required>
                            <option value="">Select Customer</option>
                            @foreach($companies as $customer)
	<option value="{{ $customer->id }}" 
			data-customer-name="{{ $customer->company_name }}">
		{{ $customer->company_name }}
		
	</option>
@endforeach
                        </select>
                    </div>
                    
                    <!-- Customer Site Selection -->
                    <div class="col-md-6">
                        <label for="site_id" class="form-label">Customer Site *</label>
                        <select class="form-select" id="site_id" name="site_id"  disabled>
                            <option value="">Select Customer First</option>
                        </select>
                    </div>
                    
                    <!-- PO Number -->
                    <div class="col-md-6">
                        <label for="po_number" class="form-label">PO Number *</label>
                        <input type="text" class="form-control" id="po_number" name="po_number" 
                               placeholder="Enter PO Number" required>
                    </div>
                    
                    <!-- PO Date -->
                    <div class="col-md-6">
                        <label for="po_date" class="form-label">PO Date *</label>
                        <input type="date" class="form-control" id="po_date" name="po_date" required>
                    </div>
                    
                    <!-- PO Start Date -->
                    <div class="col-md-6">
                        <label for="po_start_date" class="form-label">PO Start Date *</label>
                        <input type="date" class="form-control" id="po_start_date" name="po_start_date" required>
                    </div>
                    
                    <!-- PO End Date -->
                    <div class="col-md-6">
                        <label for="po_end_date" class="form-label">PO End Date *</label>
                        <input type="date" class="form-control" id="po_end_date" name="po_end_date" required>
                    </div>

                                    <!-- PO Start Date -->
                    <div class="col-md-6">
                        <label for="test_rate" class="form-label">Test Rate *</label>
                        <input type="text" class="form-control" id="test_rate" name="test_rate" required>
                    </div>
                    
                    <!-- PO End Date -->
                    <div class="col-md-6">
                        <label for="test_limit" class="form-label">Test Limit *</label>
                        <input type="text" class="form-control" id="test_limit" name="test_limit" required>
                    </div>
                     <!-- PO End Date -->
                    <div class="col-md-6">
                        <label for="currency" class="form-label">Currency *</label>
                        <input type="text" class="form-control" id="currency" name="currency" required>
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
    const customerSelect = document.getElementById('customer_id');
    const siteSelect = document.getElementById('site_id');
    const addSampleBtn = document.getElementById('addSampleBtn');
    const samplesContainer = document.getElementById('samplesContainer');
    
    let sampleCounter = 0;
    
    // Customer change event - load sites
    customerSelect.addEventListener('change', function() {
        const customerId = this.value;
        siteSelect.disabled = !customerId;
        siteSelect.innerHTML = '<option value="">Loading sites...</option>';
        
        if (customerId) {
            fetch(`/ajax/company-sites/${customerId}`)
                .then(response => response.json())
                .then(sites => {
                    siteSelect.innerHTML = '<option value="">Select Site</option>';
                    sites.forEach(site => {
                        const option = document.createElement('option');
                        option.value = site.id;
                        option.textContent = site.site_name;
                        siteSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading sites:', error);
                    siteSelect.innerHTML = '<option value="">Error loading sites</option>';
                });
        } else {
            siteSelect.innerHTML = '<option value="">Select Customer First</option>';
        }
    });
    
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
                <div class="col-md-6">
                    <label class="form-label">Sample Type *</label>
                    <select class="form-select " name="samples[${sampleCounter}][sample_type_id]" required>
                        <option value="">Select Sample Type</option>
                        @foreach($sampleTypes as $sampleType)
                            <option value="{{ $sampleType->id }}">{{ $sampleType->sample_type_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sample Description</label>
                    <input type="text" class="form-control" name="samples[${sampleCounter}][description]" 
                           placeholder="Sample description">
                </div>
            </div>
            <div class="tests-container mt-2">
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label">Tests *</label>
                        <select class="form-select test-select" name="samples[${sampleCounter}][tests][]" multiple required size="10">
                            @foreach($tests as $test)
                                <option value="{{ $test->id }}" data-price="{{ $test->default_price ?? 0 }}">
                                    {{ $test->test_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        `;
        samplesContainer.appendChild(sampleDiv);
    }
    
    // Function to remove a sample
    window.removeSample = function(button) {
        button.closest('.sample-item').remove();
        updateSampleNumbers();
    };
    
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
        });
        sampleCounter = samples.length;
    }
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('po_date').value = today;
    document.getElementById('po_start_date').value = today;
    
    // Add one sample by default
    addSample();
});
</script>

@endsection

