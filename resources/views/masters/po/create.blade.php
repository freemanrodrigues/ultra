@extends('/layouts/master-layout')
@section('content')

<style>
    /* Consistent styling with customer list page */
    .po-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
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
                    <!-- Customer Selection -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Customer Name *</label>
                        <select class="form-select" id="customer_id" name="customer_id" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                        data-customer-name="{{ $customer->customer_name }}">
                                    {{ $customer->customer_name }}
                                    @if($customer->division)
                                        - {{ $customer->division }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Customer Site Selection -->
                    <div class="col-md-6">
                        <label for="site_id" class="form-label">Customer Site *</label>
                        <select class="form-select" id="site_id" name="site_id" required disabled>
                            <option value="">Select Customer First</option>
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
                    
                    <!-- PO Start Date -->
                    <div class="col-md-4">
                        <label for="po_start_date" class="form-label">PO Start Date *</label>
                        <input type="date" class="form-control" id="po_start_date" name="po_start_date" required>
                    </div>
                    
                    <!-- PO End Date -->
                    <div class="col-md-4">
                        <label for="po_end_date" class="form-label">PO End Date *</label>
                        <input type="date" class="form-control" id="po_end_date" name="po_end_date" required>
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
            fetch(`/api/customers/${customerId}/sites`)
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
                    <select class="form-select" name="samples[${sampleCounter}][sample_type_id]" required>
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
            <div class="mt-2">
                <button type="button" class="btn btn-success btn-add" onclick="addTest(this, ${sampleCounter})">
                    <i class="fas fa-plus me-1"></i>Add Test
                </button>
            </div>
            <div class="tests-container mt-2">
                <!-- Tests will be added here -->
            </div>
        `;
        samplesContainer.appendChild(sampleDiv);
    }
    
    // Function to add a test to a sample
    window.addTest = function(button, sampleIndex) {
        const testsContainer = button.parentElement.nextElementSibling;
        const testCounter = testsContainer.children.length + 1;
        const testDiv = document.createElement('div');
        testDiv.className = 'test-item';
        testDiv.innerHTML = `
            <div class="test-item-header">
                <span class="fw-bold">Test ${testCounter}</span>
                <button type="button" class="btn btn-danger btn-remove" onclick="removeTest(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Test *</label>
                    <select class="form-select" name="samples[${sampleIndex}][tests][${testCounter}][test_id]" required>
                        <option value="">Select Test</option>
                        @foreach($tests as $test)
                            <option value="{{ $test->id }}" data-price="{{ $test->default_price ?? 0 }}">
                                {{ $test->test_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Test Price *</label>
                    <input type="number" class="form-control test-price" step="0.01" min="0" 
                           name="samples[${sampleIndex}][tests][${testCounter}][price]" 
                           placeholder="0.00" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" min="1" value="1"
                           name="samples[${sampleIndex}][tests][${testCounter}][quantity]" 
                           onchange="updateTestTotal(this)">
                </div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Total</label>
                    <input type="text" class="form-control test-total" readonly value="0.00">
                </div>
            </div>
        `;
        testsContainer.appendChild(testDiv);
        
        // Add event listener for test selection to auto-fill price
        const testSelect = testDiv.querySelector('select[name*="[test_id]"]');
        const priceInput = testDiv.querySelector('.test-price');
        testSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.dataset.price || 0;
            priceInput.value = price;
            updateTestTotal(priceInput);
        });
    };
    
    // Function to remove a sample
    window.removeSample = function(button) {
        button.closest('.sample-item').remove();
        updateSampleNumbers();
    };
    
    // Function to remove a test
    window.removeTest = function(button) {
        button.closest('.test-item').remove();
        updateTestNumbers(button.closest('.sample-item'));
    };
    
    // Function to update test total
    window.updateTestTotal = function(input) {
        const testItem = input.closest('.test-item');
        const price = parseFloat(testItem.querySelector('.test-price').value) || 0;
        const quantity = parseInt(testItem.querySelector('input[name*="[quantity]"]').value) || 1;
        const total = price * quantity;
        testItem.querySelector('.test-total').value = total.toFixed(2);
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
            
            // Update test indices
            const tests = sample.querySelectorAll('.test-item');
            tests.forEach((test, testIndex) => {
                const testInputs = test.querySelectorAll('select, input');
                testInputs.forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/tests\[\d+\]/, `tests[${testIndex + 1}]`);
                    }
                });
            });
        });
        sampleCounter = samples.length;
    }
    
    // Function to update test numbers
    function updateTestNumbers(sample) {
        const tests = sample.querySelectorAll('.test-item');
        tests.forEach((test, index) => {
            const header = test.querySelector('.test-item-header span');
            header.textContent = `Test ${index + 1}`;
        });
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

