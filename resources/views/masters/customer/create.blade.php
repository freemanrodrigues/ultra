@extends('/layouts/master-layout1') 
@section('content')
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
                            <a href="{{ route('customer.index') }}" class="btn btn-outline-light btn-sm">
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
                                                   value="{{ old('customer_name')  }}" 
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

                                        <div class="col-md-6 mb-3">
                                            <label for="company_id" class="form-label fw-semibold">
                                                Company <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="company_id" name="company_id"  class="form-control @error('company_id') is-invalid @enderror" >
                                            <!-- select class="form-select @error('company_id') is-invalid @enderror" 
                                                    id="company_id" 
                                                    name="company_id" 
                                                    required>
                                                <option value="">Select Company</option>
                                                @foreach($companies as $k => $company)
                                                    <option value="{{ $k }}" 
                                                            {{ old('company_id') == $k ? 'selected' : '' }}>
                                                        {{ $company }}
                                                    </option>
                                                @endforeach
                                            </select -->
                                             <input type="text" name="companyid_val" id="companyid_val">
                                           
                                            @error('company_id')
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
                                             <input class="form-control" type="text" id="state"  name="state"   required>
                                            {{--
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
                                            </select> --}}
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label for="country" class="form-label fw-semibold">
                                                Country <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="country"  name="country"   required>
                                        {{--    <select class="form-select @error('country') is-invalid @enderror" 
                                                    id="country" 
                                                    name="country" 
                                                    required>
                                                <option value="">Select Country</option>
                                                <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                                <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                                                <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                                                <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                                <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                            </select> --}}
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
                                            {{--    @foreach($salesPersons as $person)
                                                    <option value="{{ $person->id }}" 
                                                            {{ old('sales_person_id') == $person->id ? 'selected' : '' }}>
                                                        {{ $person->name }}
                                                    </option>
                                                @endforeach --}}
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
                                                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                            s                </select>
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
                <button type="button" class="btn btn-primary create-customer" onclick="document.getElementById('customerForm').submit();">
                    <i class="bi bi-check-circle me-1"></i>Confirm & Create
                </button>
            </div>
        </div>
    </div>
</div>
<script src="/js/sample/create-customer.js"></script>
<script language="javascript">



</script>
@endsection