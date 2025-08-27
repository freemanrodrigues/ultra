@extends('/layouts/master-layout') 
@section('content')
<link rel="stylesheet" href="/css/customer/autosuggest.css?{{date('mmss')}}" />
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header Card -->
          <div class="text-end my-2"><a href="{{ route('customer.index') }}" class="btn btn-secondary">Back to List</a></div>
          <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white rounded-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-person-plus-fill me-2"></i>Create New Customer
                            </h4>
                            <small class="opacity-75">Add a new customer to the system</small>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- You can also add success messages similarly --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
 

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
            <!-- Main Form Card -->
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <form id='create_customer' action="{{ route('customer.store') }}" method="POST" id="customerForm" novalidate>
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="border-start border-primary border-4 bg-light p-3 rounded">
                                    <h5 class="text-primary mb-3">
                                        <i class="bi bi-person-badge me-2"></i>Basic Information
                                    </h5>
                                    
                                      <div class="row">
                                        <div class="col-md-12 mb-3">
                                        <input type="hidden" name="b2c_customer" value="0">
                                        <input type="checkbox" class="form-check-input"  id="b2c_customer"  name="b2c_customer" value="1" {{ old('b2c_customer') ? 'checked' : '' }}><span class="m-2"> <label for="b2c_customer" class="form-label fw-semibold ml-10"> B2C Customer (No GST Required)</label></span>
                                            @error('b2c_customer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                       </div>  

                                        <div class="row customer_name_div">
                                        <div class="col-md-6 mb-3">
                                        <label for="gst_no" class="form-label fw-semibold">GST Number</label>
                                           
                                            <input type="text" 
                                                   class="form-control @error('gst_no') is-invalid @enderror" 
                                                   id="gst_no" 
                                                   name="gst_no" minlength="15"  maxlength="15"
                                                   value="{{ old('gst_no') }}" 
                                                   placeholder="Enter GST number (e.g., 22AAAAA0000A1Z5)"
                                                   pattern="[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[Z]{1}[0-9A-Za-z]{1}" autocomplete="off">
                                            <div class="form-text">Format: 22AAAAA0000A1Z5</div>
                                            @error('gst_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="gst_error"></div>
                                            <div id="gst_success"></div>
                                            </div>
                                             <div class="col-md-6 mb-3">
                                                <label for="pan_no" class="form-label fw-semibold">PAN Number</label>
                                           
                                            <input type="text" class="form-control @error('pan_no') is-invalid @enderror" id="pan_no"  name="pan_no"  value="{{ old('pan_no') }}"  placeholder="PAN Number" readonly >
                                            <div class="form-text"></div>
                                            @error('pan_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>  

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="customer_name" class="form-label fw-semibold">
                                                Customer Name <span class="text-danger">*</span>
                                            </label>
        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name')}}"  placeholder="Enter customer full name" autocomplete="off" required>
             <div id="suggestions" class="suggestion-list d-none">
            </div>


            <div id="loading" class="loading-indicator">Loading...</div>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            {{-- <label for="site" class="form-label fw-semibold">
                                                Site <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('site') is-invalid @enderror" 
                                                    id="site" name="site" required>
                                                <option value="">Select OR Add Site</option>
                                                    @foreach(config('constants.SITE_TYPE') as $k => $val)
                                                        <option value="{{$k}}">{{$val}}</option>
                                                    @endforeach
                                            </select -->
                                            @error('site')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror --}}
                                            <label for="state_code" class="form-label fw-semibold">State Code</label>
                                             <input type="text" class="form-control @error('state_code') is-invalid @enderror"   id="state_code"  name="state_code"  value="{{ old('state_code') }}"  placeholder="State Code" readonly >
                                        </div>
                                            
                                    </div>

                                    <div class="row">
                                        

                                        <div class="col-md-12 mb-3">
                                            <label for="division" class="form-label fw-semibold">
                                                Division (Optional) <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="division" name="division"  class="form-control @error('division') is-invalid @enderror" placeholder="Enter Division Name" value="{{ old('division') }}" autocomplete="off" >

                                           
                                             <input type="hidden" name="company_id" id="company_id">
                                           
                                            @error('')
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
                                        <div class="col-12 mb-3">
                                            <label for="address2" class="form-label fw-semibold">
                                                Address 2 <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('address2') is-invalid @enderror" 
                                                      id="address2" 
                                                      name="address2" 
                                                      value="{{ old('address2') }}" 
                                                      placeholder="Enter complete address2" autocomplete="off"
                                                      required>
                                            @error('address2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="city" class="form-label fw-semibold">
                                                City/District <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" 
                                                   name="city" 
                                                   value="{{ old('city') }}" 
                                                   placeholder="Enter city" autocomplete="off"
                                                   required>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="state" class="form-label fw-semibold">
                                                State <span class="text-danger">*</span>
                                            </label>
                                            
                                            <select class="form-select @error('state') is-invalid @enderror" 
                                                    id="state" name="state" required>
                                                <option value="">Select State</option>
                                                @foreach($states as $k => $state)
                                                    <option value="{{ $k }}"  {{ old('state') == $k ? 'selected' : '' }}> {{ $state }} </option>
                                                @endforeach
                                            </select> 
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="country" class="form-label fw-semibold">
                                                Country <span class="text-danger">*</span>
                                            </label>
                                            <!-- input type="text" class="form-control" id="country"  name="country"   required -->
                                           <select class="form-select @error('country') is-invalid @enderror" 
                                                    id="country" name="country"  required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $v => $country)
                                                    <option value="{{ $v }}" 
                                                            {{( old('country')??$v) == 71 ? 'selected' : '' }}>
                                                        {{ $country }}
                                                    </option>
                                                @endforeach
                                                
                                            </select> 
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3 " >
                                            <label for="pincode" class="form-label fw-semibold">
                                                Pincode <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('pincode') is-invalid @enderror" 
                                                   id="pincode" 
                                                   name="pincode" 
                                                   value="{{ old('pincode') }}" 
                                                   placeholder="Enter pincode"
                                                   pattern="^$|^\d{6}$"
                                                   
                                                   required>
                                            @error('pincode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6 mb-3 d-flex align-items-center" >
                                        <input type="checkbox" 
                                                   class="form-check-input bordered @error('is_billing') is-invalid @enderror" 
                                                   id="is_billing" 
                                                   name="is_billing" 
                                                   value="1"> <span class="m-2"><label for="is_billing" class="form-label fw-semibold">
                                                This is billing address <span class="text-danger">*</span></span>
                                            @error('is_billing')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            
                                            </label>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Information Section -->
                        <div class="row mb-4 ">
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
                                                    id="billing_cycle" name="billing_cycle" required>
                                                    <option value="">Select Billing Cycle</option>
                                                         @foreach(config('constants.BILLING_CYCLE') as $k => $val)
                                                        <option value="{{$k}}" {{ old('billing_cycle') == $k ? 'selected' : '' }}>{{$val}}</option>
                                                    @endforeach
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
                                                     @foreach(config('constants.CUSTOMER_GROUP') as $k => $val)
                                                        <option value="{{$k}}" {{ old('group') == $k ? 'selected' : '' }}>{{$val}}</option>
                                                    @endforeach
                                                
                                                
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
                                               <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                               <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
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


<script src="/js/customer/create-customer.js?{{date('mmss')}}"></script>
<script src="/js/customer/autosuggest.js?{{date('mmss')}}"></script>
@endsection