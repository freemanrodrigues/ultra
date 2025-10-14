@extends('/layouts/master-layout')
@section('content')

<style>
    .quotation-page { font-size: 12px; }
    .page-header {
        background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .form-control, .form-select { font-size: 12px; height: 32px; padding: 0.25rem 0.5rem; }
    .item-row {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .btn-remove-item { padding: 0.25rem 0.5rem; font-size: 11px; }
</style>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid quotation-page">
            
            <div class="page-header">
                <h1><i class="fas fa-file-invoice me-2"></i> Edit Quotation</h1>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('quotation.update', $quotation->id) }}" id="quotationForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Quotation Number *</label>
                                <input type="text" class="form-control" value="{{ $quotation->formatted_quotation_number }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Reference Number</label>
                                <input type="text" class="form-control" name="reference_number" 
                                       value="{{ old('reference_number', $quotation->reference_number) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quotation Date *</label>
                                <input type="date" class="form-control" name="quotation_date" 
                                       value="{{ old('quotation_date', $quotation->quotation_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Validity (Days) *</label>
                                <input type="number" class="form-control" name="validity_days" 
                                       value="{{ old('validity_days', $quotation->validity_days) }}" min="1" max="365" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card shadow-sm mb-3">
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
                                        <option value="{{ $company->id }}" 
                                                {{ old('company_id', $quotation->company_id) == $company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer</label>
                                <select class="form-select" name="customer_id" id="customer_id">
                                    <option value="">Select Company First</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Site</label>
                                <select class="form-select" name="site_id" id="site_id">
                                    <option value="">Select Customer First</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quotation Items -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Quotation Items</h5>
                        <button type="button" class="btn btn-sm btn-success" id="addItemBtn">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body" id="itemsContainer">
                        <!-- Items will be added here -->
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Discount (%) - Max: {{ $userDiscountLimit }}%</label>
                                <input type="number" class="form-control" name="discount_percent" id="discount_percent"
                                       value="{{ old('discount_percent', $quotation->discount_percent) }}" 
                                       min="0" max="{{ $userDiscountLimit }}" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tax (%) *</label>
                                <input type="number" class="form-control" name="tax_percent" id="tax_percent"
                                       value="{{ old('tax_percent', $quotation->tax_percent) }}" 
                                       min="0" max="100" step="0.01" required>
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
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Terms & Conditions</label>
                                <textarea class="form-control" name="terms_and_conditions" rows="3">{{ old('terms_and_conditions', $quotation->terms_and_conditions) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="3">{{ old('notes', $quotation->notes) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Special Instructions</label>
                                <textarea class="form-control" name="special_instructions" rows="3">{{ old('special_instructions', $quotation->special_instructions) }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Add More Attachments</label>
                                <input type="file" class="form-control" name="attachments[]" multiple 
                                       accept=".pdf,.xlsx,.xls,.doc,.docx">
                                <small class="text-muted">Accepted formats: PDF, Excel, Word</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('quotation.show', $quotation->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Quotation
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</main>

<script>
let itemCounter = 0;
const sampleTypes = @json($sampleTypes);
const tests = @json($tests);
const userDiscountLimit = {{ $userDiscountLimit }};
const existingItems = @json($quotation->items);

document.addEventListener('DOMContentLoaded', function() {
    // Load existing items
    existingItems.forEach(item => {
        addItem(item);
    });
    
    // Add item button
    document.getElementById('addItemBtn').addEventListener('click', () => addItem());
    
    // Calculation events
    document.getElementById('discount_percent').addEventListener('input', calculateTotals);
    document.getElementById('tax_percent').addEventListener('input', calculateTotals);
    
    // Trigger initial calculation
    calculateTotals();
});

function addItem(existingItem = null) {
    itemCounter++;
    
    const itemHtml = `
        <div class="item-row" id="item_${itemCounter}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Sample Type *</label>
                    <select class="form-select sample-type-select" name="items[${itemCounter}][sample_type_id]" 
                            data-item-id="${itemCounter}" required>
                        <option value="">Select Sample Type</option>
                        ${sampleTypes.map(st => `<option value="${st.id}" ${existingItem && existingItem.sample_type_id == st.id ? 'selected' : ''}>${st.sample_type_name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Test *</label>
                    <select class="form-select test-select" name="items[${itemCounter}][test_id]" 
                            data-item-id="${itemCounter}" required>
                        <option value="">Select Test</option>
                        ${tests.map(t => `<option value="${t.id}" ${existingItem && existingItem.test_id == t.id ? 'selected' : ''}>${t.test_name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity *</label>
                    <input type="number" class="form-control item-quantity" name="items[${itemCounter}][quantity]" 
                           value="${existingItem ? existingItem.quantity : 1}" min="1" data-item-id="${itemCounter}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price *</label>
                    <input type="number" class="form-control item-price" name="items[${itemCounter}][unit_price]" 
                           value="${existingItem ? existingItem.unit_price : 0}" min="0" step="0.01" data-item-id="${itemCounter}" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Disc %</label>
                    <input type="number" class="form-control item-discount" name="items[${itemCounter}][discount_percent]" 
                           value="${existingItem ? existingItem.discount_percent : 0}" min="0" max="${userDiscountLimit}" step="0.01" data-item-id="${itemCounter}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100 btn-remove-item" 
                            onclick="removeItem(${itemCounter})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="items[${itemCounter}][description]" 
                           value="${existingItem ? (existingItem.description || '') : ''}" placeholder="Optional">
                </div>
                <div class="col-md-2">
                    <label class="form-label">TAT (Days)</label>
                    <input type="number" class="form-control" name="items[${itemCounter}][tat_days]" 
                           value="${existingItem ? (existingItem.tat_days || '') : ''}" min="0">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="alert alert-info mb-0 w-100 py-1 px-2">
                        <small>Line Total: ₹<span class="item-line-total" id="line_total_${itemCounter}">0.00</span></small>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', itemHtml);
    
    // Add event listeners
    const itemRow = document.getElementById(`item_${itemCounter}`);
    itemRow.querySelector('.item-quantity').addEventListener('input', () => calculateItemTotal(itemCounter));
    itemRow.querySelector('.item-price').addEventListener('input', () => calculateItemTotal(itemCounter));
    itemRow.querySelector('.item-discount').addEventListener('input', () => calculateItemTotal(itemCounter));
    itemRow.querySelector('.sample-type-select').addEventListener('change', () => fetchRate(itemCounter));
    itemRow.querySelector('.test-select').addEventListener('change', () => fetchRate(itemCounter));
    
    // Calculate initial total
    calculateItemTotal(itemCounter);
}

function removeItem(itemId) {
    const itemRow = document.getElementById(`item_${itemId}`);
    if (itemRow) {
        itemRow.remove();
        calculateTotals();
    }
}

function calculateItemTotal(itemId) {
    const quantity = parseFloat(document.querySelector(`input[name="items[${itemId}][quantity]"]`).value) || 0;
    const price = parseFloat(document.querySelector(`input[name="items[${itemId}][unit_price]"]`).value) || 0;
    const discount = parseFloat(document.querySelector(`input[name="items[${itemId}][discount_percent]"]`).value) || 0;
    
    const baseAmount = quantity * price;
    const discountAmount = (baseAmount * discount) / 100;
    const lineTotal = baseAmount - discountAmount;
    
    document.getElementById(`line_total_${itemId}`).textContent = lineTotal.toFixed(2);
    calculateTotals();
}

function calculateTotals() {
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

function fetchRate(itemId) {
    const sampleTypeId = document.querySelector(`select[name="items[${itemId}][sample_type_id]"]`).value;
    const testId = document.querySelector(`select[name="items[${itemId}][test_id]"]`).value;
    
    if (sampleTypeId && testId) {
        fetch(`/api/quotation/sample-type-rates?sample_type_id=${sampleTypeId}&test_id=${testId}`)
            .then(response => response.json())
            .then(data => {
                if (data.rate) {
                    document.querySelector(`input[name="items[${itemId}][unit_price]"]`).value = data.rate;
                    calculateItemTotal(itemId);
                }
            });
    }
}
</script>

@stop

