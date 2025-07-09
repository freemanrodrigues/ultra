@extends('/layouts/master-layout')

@section('title', 'Create Customer')

@section('content')
@dd("XX");
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-person-plus-fill me-2"></i>Create New Customer
                            </h4>
                            <small class="opacity-75">Add a new customer to the system</small>
                        </div>
                        <div>
                            <a href="{{ route('customer.index') }}"" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <form action="{{ route('customer.store') }}" method="POST" id="customerForm" novalidate>
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="border-start border-primary border-4 bg-light p-3 rounded">
                                    <h5 class="text-primary mb-3">
                                        <i class="bi bi-person-badge me-2"></i>Basic Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="customer_name" class="form-label fw-semibold">
                                                Customer Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('customer_name') is-invalid @enderror" 
                                                   id="customer_name" 
                                                   name="customer_name" 
                                                   value="{{ old('customer_name') }}" 
                                                   placeholder="Enter customer full name"
                                                   required>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="display_name" class="form-label fw-semibold">
                                                Display Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('display_name') is-invalid @enderror" 
                                                   id="display_name" 
                                                   name="display_name" 
                                                   value="{{ old('display_name') }}" 
                                                   placeholder="Enter display name"
                                                   required>
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="company_id" class="form-label fw-semibold">
                                                Company <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('company_id') is-invalid @enderror" 
                                                    id="company_id" 
                                                    name="company_id" 
                                                    required>
                                                <option value="">Select Company</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" 
                                                            {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>GG
                                            <input type="text" name="companyid_val" id="companyid_val">HH @dd('HH');
                                            @error('company_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="gst_no" class="form-label fw-semibold">GST Number</label>
                                            <input type="text" 
                                                   class="form-control @error('gst_no') is-invalid @enderror" 
                                                   id="gst_no" 
                                                   name="gst_no" 
                                                   value="{{ old('gst_no') }}" 
                                                   placeholder="Enter GST number (e.g., 22AAAAA0000A1Z5)"
                                                   pattern="[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[Z]{1}[0-9A-Za-z]{1}">
                                            <div class="form-text">Format: 22AAAAA0000A1Z5</div>
                                            @error('gst_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="border-start border-success border-4 bg-light p-3 rounded">
                                    <h5 class="text-success mb-3">
                                        <i class="bi bi-geo-alt-fill me-2"></i>Address Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="address" class="form-label fw-semibold">
                                                Address <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="3" 
                                                      placeholder="Enter complete address"
                                                      required>{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="city" class="form-label fw-semibold">
                                                City <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" 
                                                   name="city" 
                                                   value="{{ old('city') }}" 
                                                   placeholder="Enter city"
                                                   required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="state" class="form-label fw-semibold">
                                                State <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('state') is-invalid @enderror" 
                                                    id="state" 
                                                    name="state" 
                                                    required>
                                                <option value="">Select State</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state }}" 
                                                            {{ old('state') == $state ? 'selected' : '' }}>
                                                        {{ $state }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="country" class="form-label fw-semibold">
                                                Country <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('country') is-invalid @enderror" 
                                                    id="country" 
                                                    name="country" 
                                                    required>
                                                <option value="">Select Country</option>
                                                <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                                <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                                                <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                                                <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                                <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                            </select>
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="pincode" class="form-label fw-semibold">
                                                Pincode <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('pincode') is-invalid @enderror" 
                                                   id="pincode" 
                                                   name="pincode" 
                                                   value="{{ old('pincode') }}" 
                                                   placeholder="Enter pincode"
                                                   pattern="[0-9]{6}"
                                                   maxlength="6"
                                                   required>
                                            @error('pincode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="border-start border-warning border-4 bg-light p-3 rounded">
                                    <h5 class="text-warning mb-3">
                                        <i class="bi bi-briefcase-fill me-2"></i>Business Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="billing_cycle" class="form-label fw-semibold">
                                                Billing Cycle <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('billing_cycle') is-invalid @enderror" 
                                                    id="billing_cycle" 
                                                    name="billing_cycle" 
                                                    required>
                                                <option value="">Select Billing Cycle</option>
                                                <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="quarterly" {{ old('billing_cycle') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                                <option value="half_yearly" {{ old('billing_cycle') == 'half_yearly' ? 'selected' : '' }}>Half Yearly</option>
                                                <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            </select>
                                            @error('billing_cycle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="credit_cycle" class="form-label fw-semibold">
                                                Credit Cycle (Days) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('credit_cycle') is-invalid @enderror" 
                                                   id="credit_cycle" 
                                                   name="credit_cycle" 
                                                   value="{{ old('credit_cycle') }}" 
                                                   placeholder="Enter credit days"
                                                   min="0"
                                                   max="365"
                                                   required>
                                            <div class="form-text">Payment due within specified days</div>
                                            @error('credit_cycle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="group" class="form-label fw-semibold">Customer Group</label>
                                            <select class="form-select @error('group') is-invalid @enderror" 
                                                    id="group" 
                                                    name="group">
                                                <option value="">Select Group</option>
                                                <option value="premium" {{ old('group') == 'premium' ? 'selected' : '' }}>Premium</option>
                                                <option value="standard" {{ old('group') == 'standard' ? 'selected' : '' }}>Standard</option>
                                                <option value="basic" {{ old('group') == 'basic' ? 'selected' : '' }}>Basic</option>
                                                <option value="vip" {{ old('group') == 'vip' ? 'selected' : '' }}>VIP</option>
                                            </select>
                                            @error('group')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="sales_person_id" class="form-label fw-semibold">
                                                Sales Person <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('sales_person_id') is-invalid @enderror" 
                                                    id="sales_person_id" 
                                                    name="sales_person_id" 
                                                    >
                                                <option value="">Select Sales Person</option>
                                                @foreach($salesPersons as $person)
                                                    <option value="{{ $person->id }}" 
                                                            {{ old('sales_person_id') == $person->id ? 'selected' : '' }}>
                                                        {{ $person->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('sales_person_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label fw-semibold">
                                                Status <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status" 
                                                    required>
                                                <option value="">Select Status</option>
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 justify-content-end">
                                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" id="resetBtn">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="previewBtn">
                                        <i class="bi bi-eye me-1"></i>Preview
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Create Customer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="bi bi-eye me-2"></i>Customer Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('customerForm').submit();">
                    <i class="bi bi-check-circle me-1"></i>Confirm & Create
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .border-4 {
        border-width: 4px !important;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        transition: transform 0.2s ease;
    }
    
    .card {
        transition: box-shadow 0.3s ease;
    }
    
    .invalid-feedback {
        display: block;
    }
    
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill display name based on customer name
    document.getElementById('customer_name').addEventListener('input', function() {
        const displayNameField = document.getElementById('display_name');
        if (!displayNameField.value) {
            displayNameField.value = this.value;
        }
    });
    
    // Reset form
    document.getElementById('resetBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            document.getElementById('customerForm').reset();
        }
    });
    
    // Preview functionality
    document.getElementById('previewBtn').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('customerForm'));
        let previewHTML = '<div class="row">';
        
        // Basic Information
        previewHTML += '<div class="col-md-6"><h6 class="text-primary">Basic Information</h6>';
        previewHTML += '<p><strong>Customer Name:</strong> ' + (formData.get('customer_name') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Display Name:</strong> ' + (formData.get('display_name') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Company:</strong> ' + (document.getElementById('company_id').selectedOptions[0]?.text || 'Not selected') + '</p>';
        previewHTML += '<p><strong>GST Number:</strong> ' + (formData.get('gst_no') || 'Not provided') + '</p>';
        previewHTML += '</div>';
        
        // Address Information
        previewHTML += '<div class="col-md-6"><h6 class="text-success">Address Information</h6>';
        previewHTML += '<p><strong>Address:</strong> ' + (formData.get('address') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>City:</strong> ' + (formData.get('city') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>State:</strong> ' + (formData.get('state') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Country:</strong> ' + (formData.get('country') || 'Not provided') + '</p>';
        previewHTML += '<p><strong>Pincode:</strong> ' + (formData.get('pincode') || 'Not provided') + '</p>';
        previewHTML += '</div>';
        
        // Business Information
        previewHTML += '<div class="col-12 mt-3"><h6 class="text-warning">Business Information</h6>';
        previewHTML += '<div class="row">';
        previewHTML += '<div class="col-md-4"><p><strong>Billing Cycle:</strong> ' + (formData.get('billing_cycle') || 'Not selected') + '</p></div>';
        previewHTML += '<div class="col-md-4"><p><strong>Credit Cycle:</strong> ' + (formData.get('credit_cycle') || 'Not provided') + ' days</p></div>';
        previewHTML += '<div class="col-md-4"><p><strong>Group:</strong> ' + (formData.get('group') || 'Not selected') + '</p></div>';
        previewHTML += '<div class="col-md-6"><p><strong>Sales Person:</strong> ' + (document.getElementById('sales_person_id').selectedOptions[0]?.text || 'Not selected') + '</p></div>';
        previewHTML += '<div class="col-md-6"><p><strong>Status:</strong> ' + (formData.get('status') || 'Not selected') + '</p></div>';
        previewHTML += '</div></div>';
        
        previewHTML += '</div>';
        
        document.getElementById('previewContent').innerHTML = previewHTML;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    });
    
    // Form validation
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        const form = this;
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
    // GST number validation
    document.getElementById('gst_no').addEventListener('input', function() {
        const gstPattern = /^[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[Z]{1}[0-9A-Za-z]{1}$/;
        const value = this.value.toUpperCase();
        this.value = value;
        
        if (value && !gstPattern.test(value)) {
            this.setCustomValidity('Please enter a valid GST number');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Pincode validation
    document.getElementById('pincode').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endpush