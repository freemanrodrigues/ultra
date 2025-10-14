@extends('/layouts/master-layout')
@section('content')

<style>
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
    
    .po-page .form-control, .po-page .form-select, .po-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    .form-control, .form-select, .btn {
        height: 32px !important;
        padding: 0.25rem 0.5rem;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .compact-form {
        margin-bottom: 0.5rem;
    }
    
    .card-body {
        padding: 0.5rem !important;
    }
    
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
    
    .btn-remove {
        padding: 0.1rem 0.3rem;
        font-size: 10px;
        line-height: 1;
        min-width: 20px;
        height: 20px;
    }
    
    .btn-add {
        padding: 0.25rem 0.5rem;
        font-size: 11px;
        line-height: 1.2;
    }
    
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
</style>

<div class="container-fluid po-page">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-file-invoice me-2"></i> Create Quotation</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('quotation.store') }}" id="quotationForm" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information -->
        <div class="card shadow-sm compact-form">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quotation Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Quotation Number *</label>
                        <input type="text" class="form-control" value="{{ $quotationNumber }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control" name="reference_number" value="{{ old('reference_number') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quotation Date *</label>
                        <input type="date" class="form-control" name="quotation_date" id="quotation_date" 
                               value="{{ old('quotation_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Validity (Days) *</label>
                        <input type="number" class="form-control" name="validity_days" 
                               value="{{ old('validity_days', 30) }}" min="1" max="365" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card shadow-sm compact-form">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Company *</label>
                        <select class="form-select" name="company_id" id="company_id" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Customer Site</label>
                        <select class="form-select" name="site_id" id="site_id" disabled>
                            <option value="">Select Company First</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Samples Section -->
        <div class="card shadow-sm compact-form">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Quotation Items</h5>
                <button type="button" class="btn btn-primary btn-add" id="addSampleBtn">
                    <i class="fas fa-plus me-1"></i>Add Item
                </button>
            </div>
            <div class="card-body">
                <div id="samplesContainer">
                    <!-- Sample items will be added here dynamically -->
                </div>
            </div>
        </div>

        <!-- Pricing Summary -->
        <div class="card shadow-sm compact-form">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Pricing Summary</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Discount (%) - Max: {{ $userDiscountLimit }}%</label>
                        <input type="number" class="form-control" name="discount_percent" id="discount_percent"
                               value="{{ old('discount_percent', 0) }}" min="0" max="{{ $userDiscountLimit }}" step="0.01" onchange="updateGrandTotal()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tax (%) *</label>
                        <input type="number" class="form-control" name="tax_percent" id="tax_percent"
                               value="{{ old('tax_percent', 18) }}" min="0" max="100" step="0.01" required onchange="updateGrandTotal()">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Subtotal:</strong></td>
                                <td class="text-end" id="subtotal_display">₹0.00</td>
                            </tr>
                            <tr>
                                <td><strong>Discount:</strong></td>
                                <td class="text-end" id="discount_display">₹0.00</td>
                            </tr>
                            <tr>
                                <td><strong>Tax:</strong></td>
                                <td class="text-end" id="tax_display">₹0.00</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total:</strong></td>
                                <td class="text-end"><strong id="total_display">₹0.00</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card shadow-sm compact-form">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Additional Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Terms & Conditions</label>
                        <textarea class="form-control" name="terms_and_conditions" rows="3" style="height: auto;">{{ old('terms_and_conditions', 'Payment terms: 30 days from invoice date.&#10;Delivery: As per agreed schedule.&#10;Prices are subject to change without notice.') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" style="height: auto;">{{ old('notes') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Special Instructions</label>
                        <textarea class="form-control" name="special_instructions" rows="3" style="height: auto;">{{ old('special_instructions') }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Attachments</label>
                        <input type="file" class="form-control" name="attachments[]" multiple accept=".pdf,.xlsx,.xls,.doc,.docx" style="height: auto; padding: 0.375rem 0.5rem;">
                        <small class="text-muted">Accepted formats: PDF, Excel, Word</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('quotation.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Create Quotation
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
    const sampleTypes = @json($sampleTypes);
    const userDiscountLimit = {{ $userDiscountLimit }};
    
    // Enable site selection when company is selected
    document.getElementById('company_id').addEventListener('change', function() {
        const companyId = this.value;
        siteSelect.disabled = !companyId;
        if (companyId) {
            siteSelect.innerHTML = '<option value="">Select Site (Optional)</option>';
        } else {
            siteSelect.innerHTML = '<option value="">Select Company First</option>';
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
        sampleDiv.setAttribute('data-sample-index', sampleCounter);
        sampleDiv.innerHTML = `
            <div class="sample-item-header">
                <h6 class="mb-0">Item ${sampleCounter}</h6>
                <button type="button" class="btn btn-danger btn-remove" onclick="removeSample(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Sample Type *</label>
                    <select class="form-select sample-type-select" name="items[${sampleCounter}][sample_type_id]" required onchange="onSampleTypeChange(this, ${sampleCounter})">
                        <option value="">Select Sample Type</option>
                        ${sampleTypes.map(st => `<option value="${st.id}">${st.sample_type_name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sample Count *</label>
                    <input type="number" class="form-control sample-count" name="items[${sampleCounter}][sample_count]" 
                           min="1" value="1" required onchange="updateSampleTotal(this, ${sampleCounter})">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sample Rate *</label>
                    <input type="number" class="form-control sample-rate" name="items[${sampleCounter}][sample_rate]" 
                           step="0.01" min="0" value="0.00" required onchange="updateSampleTotal(this, ${sampleCounter})">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sample Total</label>
                    <input type="text" class="form-control sample-total" readonly value="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Test Total</label>
                    <input type="text" class="form-control test-total" name="items[${sampleCounter}][test_total]" readonly value="0.00">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Disc %</label>
                    <input type="number" class="form-control item-discount" name="items[${sampleCounter}][discount_percent]" 
                           value="0" min="0" max="${userDiscountLimit}" step="0.01" onchange="updateItemTotal(${sampleCounter})">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="items[${sampleCounter}][description]" placeholder="Optional">
                </div>
                <div class="col-md-2">
                    <label class="form-label">TAT (Days)</label>
                    <input type="number" class="form-control" name="items[${sampleCounter}][tat_days]" min="0" placeholder="Optional">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="alert alert-info mb-0 w-100 py-1 px-2">
                        <small>Item Total: ₹<span class="item-line-total" id="line_total_${sampleCounter}">0.00</span></small>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <label class="form-label fw-bold">Select Tests for this Sample Type:</label>
                <div class="tests-checkbox-container mt-2" id="tests-${sampleCounter}">
                    <p class="text-muted">Please select a sample type first</p>
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
        
        testsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading tests...</div>';
        
        fetch(`/api/quotation/sample-type-rates?sample_type_id=${sampleTypeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.tests.length > 0) {
                    testsContainer.innerHTML = data.tests.map(test => `
                        <div class="form-check mb-2">
                            <input class="form-check-input test-checkbox" type="checkbox" 
                                   name="items[${sampleIndex}][tests][${test.id}][test_id]" 
                                   value="${test.id}" 
                                   id="test_${sampleIndex}_${test.id}"
                                   data-standard-rate="${test.standard_test_rate}"
                                   onchange="updateTestTotal(${sampleIndex})">
                            <label class="form-check-label" for="test_${sampleIndex}_${test.id}">
                                <strong>${test.test_name}</strong> <span class="text-muted">(₹${parseFloat(test.standard_test_rate).toFixed(2)})</span>
                            </label>
                            <input type="hidden" name="items[${sampleIndex}][tests][${test.id}][standard_rate]" value="${test.standard_test_rate}">
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
        updateGrandTotal();
    };
    
    // Function to update sample total
    window.updateSampleTotal = function(input, sampleIndex) {
        const sampleItem = input.closest('.sample-item');
        const count = parseFloat(sampleItem.querySelector('.sample-count').value) || 0;
        const rate = parseFloat(sampleItem.querySelector('.sample-rate').value) || 0;
        const total = count * rate;
        sampleItem.querySelector('.sample-total').value = total.toFixed(2);
        updateItemTotal(sampleIndex);
    };

    // Function to update test total
    window.updateTestTotal = function(sampleIndex) {
        const sampleItem = document.querySelector(`[data-sample-index="${sampleIndex}"]`);
        if (!sampleItem) return;
        
        const testCheckboxes = sampleItem.querySelectorAll('.test-checkbox:checked');
        let total = 0;
        
        testCheckboxes.forEach(checkbox => {
            const standardRate = parseFloat(checkbox.getAttribute('data-standard-rate')) || 0;
            total += standardRate;
        });
        
        const testTotalInput = sampleItem.querySelector('.test-total');
        if (testTotalInput) {
            testTotalInput.value = total.toFixed(2);
        }
        
        updateItemTotal(sampleIndex);
    };

    // Function to update item total
    function updateItemTotal(sampleIndex) {
        const sampleItem = document.querySelector(`[data-sample-index="${sampleIndex}"]`);
        if (!sampleItem) return;
        
        const sampleTotal = parseFloat(sampleItem.querySelector('.sample-total').value) || 0;
        const testTotal = parseFloat(sampleItem.querySelector('.test-total').value) || 0;
        const discountPercent = parseFloat(sampleItem.querySelector('.item-discount').value) || 0;
        
        const baseAmount = sampleTotal + testTotal;
        const discountAmount = (baseAmount * discountPercent) / 100;
        const itemTotal = baseAmount - discountAmount;
        
        document.getElementById(`line_total_${sampleIndex}`).textContent = itemTotal.toFixed(2);
        
        updateGrandTotal();
    }

    // Function to handle sample type change
    window.onSampleTypeChange = function(select, sampleIndex) {
        const sampleTypeId = select.value;
        loadTestsForSampleType(sampleIndex, sampleTypeId);
    };

    // Function to update grand total
    function updateGrandTotal() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-line-total').forEach(element => {
            subtotal += parseFloat(element.textContent) || 0;
        });
        
        const discountPercent = parseFloat(document.getElementById('discount_percent').value) || 0;
        const taxPercent = parseFloat(document.getElementById('tax_percent').value) || 0;
        
        const discountAmount = (subtotal * discountPercent) / 100;
        const amountAfterDiscount = subtotal - discountAmount;
        const taxAmount = (amountAfterDiscount * taxPercent) / 100;
        const total = amountAfterDiscount + taxAmount;
        
        document.getElementById('subtotal_display').textContent = '₹' + subtotal.toFixed(2);
        document.getElementById('discount_display').textContent = '₹' + discountAmount.toFixed(2);
        document.getElementById('tax_display').textContent = '₹' + taxAmount.toFixed(2);
        document.getElementById('total_display').textContent = '₹' + total.toFixed(2);
    }
    
    window.updateGrandTotal = updateGrandTotal;
    
    // Set default date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('quotation_date').value = today;
    
    // Add one sample by default
    addSample();
});
</script>

@endsection
