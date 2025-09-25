@extends('/layouts/master-layout') 
@section('content')
<link rel="stylesheet" href="{{asset('/css/customer/autosuggest_pop.css')}}?{{date('mmss')}}" />
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header Card -->
          
          <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white rounded-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-person-plus-fill me-2"></i>Create New Customer
                            </h4>
                           
                        </div>
                        <div class="text-end my-2"><a href="{{ route('customer.index') }}" class="btn btn-secondary">Back to List</a>
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
                    <form id='create_customer' action="{{ route('customer.store') }}" method="POST" id="customerForm">

                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="border-start border-primary border-4 bg-light p-3 rounded">
                                    <h5 class="text-primary mb-2">
                                        <i class="bi bi-person-badge me-2"></i>Basic Information
                                    </h5>
                                    
            

                                        <div class="row customer_name_div">
       <div class="col-md-2 mb-2">
 <span class="m-2"> <label for="b2c_customer" class="form-label fw-semibold ml-10"></label></span><br>
 <div class="">
                                     <input type="hidden" name="b2c_customer" value="0">
                                        <input type="checkbox" class="form-check-input"  id="b2c_customer"  name="b2c_customer" value="1" {{ old('b2c_customer') ? 'checked' : '' }}> <b> B2C Customer  </b>
                                            @error('b2c_customer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
 </div>                                            
                                        </div>

                                        <div class="col-md-5 mb-2">
                                        <label for="gst_no" class="form-label fw-semibold">GST Number</label>
                                            <input type="text" 
                                                   class="form-control @error('gst_no') is-invalid @enderror" 
                                                   id="gst_no" 
                                                   name="gst_no" minlength="15"  maxlength="15"
                                                   value="{{ old('gst_no') }}" 
                                                   placeholder="Enter GST number (e.g., 22AAAAA0000A1Z5)"
                                                   pattern="[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}[Z]{1}[0-9A-Za-z]{1}" autocomplete="off" style="width: 320px;">
                                           
                                            @error('gst_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="gst_error"></div>
                                            <div id="gst_success"></div>
                                            </div>

                                 

                                        <div class="col-md-3 mb-2">
                                                <label for="pan_no" class="form-label fw-semibold">PAN Number</label>
                                           
                                            <input type="text" class="form-control @error('pan_no') is-invalid @enderror" id="pan_no"  name="pan_no"  value="{{ old('pan_no') }}"  placeholder="PAN Number" readonly style="width: 200px;" >
                                            <div class="form-text"></div>
                                            @error('pan_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            </div>
<div class="col-md-2 mb-2">
<label for="state_code" class="form-label fw-semibold">State Code</label>
                                             <input type="text" class="form-control @error('state_code') is-invalid @enderror"   id="state_code"  name="state_code"  value="{{ old('state_code') }}"  placeholder="State Code" readonly >
</div>
                                        </div>  

                                    <div class="row">
                                        <div class="col-md-8 mb-2">
                                            <label for="id_company" class="form-label fw-semibold">
                                                Customer Name <span class="text-danger">*</span>
                                            </label>
<div class="myDropdownCover"> 
<input type="text" class="form-control search" id="id_company" name="customer_name" 
value="{{ request('search') }}" placeholder="Search by Company Name..."  data-txt_id="company_id"  autocomplete="off">
<input type="hidden" id="company_id" name="company_id">   
<div id="myDropdown_company_id" class="myDropdown"></div>
</div>                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


            <div class="col-md-4 mb-2">
                                            <label for="division" class="form-label fw-semibold">
                                                Division (Optional)
                                            </label>
                                            <input type="text" id="division" name="division"  class="form-control @error('division') is-invalid @enderror" placeholder="Enter Division Name" value="{{ old('division') }}" autocomplete="off" >

                                           
                                             <input type="hidden" name="company_id" id="company_id">
                                           
                                            @error('')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>                            
           </div>

                                    <div class="row">
                                        
                                            
                         
                                        

                                        
                                    </div>
                                </div>
                            </div>
                        </div>


<div class="row g-4">
    <!-- Address Information -->
    <div class="col-lg-6">
        <div class="border-start border-success border-4 bg-light p-4 rounded shadow-sm">
            <h5 class="text-success mb-4">
                <i class="bi bi-geo-alt-fill me-2"></i>Address Information
            </h5>

            <!-- Address -->
            <div class="mb-2">
                <label for="address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                          id="address" name="address" rows="1"
                          placeholder="Enter complete address" required>{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Address 2 & Pincode -->
            <div class="row">
                <div class="col-md-12 mb-2">
                      <input type="text" class="form-control @error('address2') is-invalid @enderror"
                           id="address2" name="address2"
                           value="{{ old('address2') }}"
                           placeholder="Enter complete address" autocomplete="off" >
                    @error('address2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-2">
                      <input type="text" class="form-control @error('address3') is-invalid @enderror"
                           id="address3" name="address3"
                           value="{{ old('address3') }}"
                           placeholder="Enter complete address" autocomplete="off" >
                    @error('address3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        <!-- City, State, Country -->
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label for="city" class="form-label fw-semibold">City/District <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                           id="city" name="city"
                           value="{{ old('city') }}" placeholder="Enter city" autocomplete="off" required>
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-2">
                    <label for="state" class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                    <select class="form-select @error('state') is-invalid @enderror" id="state" name="state" required>
                        <option value="">Select State</option>
                        @foreach($states as $k => $state)
                            <option value="{{ $k }}" {{ old('state') == $k ? 'selected' : '' }}> {{ $state }} </option>
                        @endforeach
                    </select>
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-2">
                    <label for="country" class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                    <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                        <option value="">Select Country</option>
                        @foreach($countries as $v => $country)
                            <option value="{{ $v }}" {{ (old('country')??71) == $v  ? 'selected' : '' }}> {{ $country }} </option>
                        @endforeach
                    </select>
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
            </div>
            <div class="row">
    <!-- Pincode Input Field -->
    <div class="col-md-6 mb-2">
        <label for="pincode" class="form-label fw-semibold">
            Pincode <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control @error('pincode') is-invalid @enderror"
               id="pincode" name="pincode"
               value="{{ old('pincode') }}"
               placeholder="Enter pincode" pattern="^$|^\d{6}$" required>
        @error('pincode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Billing Address Checkbox -->
    <div class="col-md-6 mb-2">
        <!-- A vertical spacer to align with the text input field's label -->
        <div class="mt-4 form-check">
            <input type="checkbox" class="form-check-input @error('is_billing') is-invalid @enderror"
                   id="is_billing" name="is_billing" value="1"
                   {{ old('is_billing') ? 'checked' : '' }}>
            <label for="is_billing" class="form-check-label fw-semibold">
                This is billing address
            </label>
            @error('is_billing')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Business Information -->
    <div class="col-lg-6">
        <div class="border-start border-warning border-4 bg-light p-4 rounded shadow-sm">
            <h5 class="text-warning mb-4">
                <i class="bi bi-briefcase-fill me-2"></i>Business Information
            </h5>

            <div class="row">
<!-- Customer Group -->
                <div class="col-md-6 mb-2">
                    <label for="group" class="form-label fw-semibold">Customer Group</label><span class="text-danger">*</span></label>

                 </div>
                <div class="col-md-6 mb-2">    
                    <select class="form-select @error('group') is-invalid @enderror" id="group" name="group" required>
                        <option value="">Select Group</option>
                        @foreach(config('constants.CUSTOMER_GROUP') as $k => $val)
                            <option value="{{$k}}" {{ old('group') == $k ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                    </select>
                    @error('group')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Billing Cycle -->
                <div class="col-md-6 mb-2">
                    <label for="billing_cycle" class="form-label fw-semibold">Billing Cycle <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6 mb-2">
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

               

                <!-- Credit Cycle -->
                <div class="col-md-6 mb-2">
                    <label for="credit_cycle" class="form-label fw-semibold">Credit Cycle (Days) <span class="text-danger">*</span></label>
                 </div>
                <div class="col-md-6 mb-2">    
                    <input type="number" class="form-control @error('credit_cycle') is-invalid @enderror"
                           id="credit_cycle" name="credit_cycle"
                           value="{{ old('credit_cycle') }}" placeholder="Enter credit days"
                           min="0" max="365" required>
                    
                    @error('credit_cycle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                
            </div>

            <div class="row">
                <!-- Sales Person -->
                <div class="col-md-6 mb-2">
                    <label for="sales_person_id" class="form-label fw-semibold">Sales Person <span class="text-danger">*</span></label>
                 </div>
                <div class="col-md-6 mb-2">        
                    <select class="form-select @error('sales_person_id') is-invalid @enderror"
                            id="sales_person_id" name="sales_person_id">
                        <option value="">Select Sales Person</option>
                      @foreach($users as $user)
                      <option value="{{$user->id}}" {{ (old('sales_person_id') ) == $user->id ? 'selected' : '' }}>{{$user->firstname}} {{$user->lastname}}</option>
                      @endforeach
                    </select>
                    @error('sales_person_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-2">
                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                 </div>
                <div class="col-md-6 mb-2">        
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="1" {{ (old('status')??1) == 1  ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ (old('status')??1) == 0  ? 'selected' : '' }}>Inactive</option>
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

                                    <button type="submit" class="btn btn-primary" id="btn-submit">
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


<script src="{{asset('/js/customer/create-customer.js')}}?{{date('mmss')}}"></script>
<script src="{{asset('/js/customer/function_autosuggest33.js')}}?{{date('mmss')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerInput = document.getElementById('id_company');
        const suggestionsContainer = document.getElementById('myDropdown_company_id');

        if (customerInput && suggestionsContainer) {
            customerInput.addEventListener('blur', function () {
                // We delay hiding to allow click on suggestion to register
                setTimeout(() => {
                    suggestionsContainer.style.display = 'none';
                }, 200);
            });
        }
    });
</script>

@endsection