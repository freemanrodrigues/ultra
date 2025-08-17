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
                                <i class="bi bi-person-plus-fill me-2"></i>Edit Customer :{{$customer->customer_name}}
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
 @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
            <!-- Main Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <form action="{{ route('customer.update',$customer->id) }}" method="POST" id="customerForm" novalidate>
                        @csrf
                          @method('PUT')
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
                                        <input type="checkbox" class="form-check-input"  id="b2c_customer"  name="b2c_customer" value="1" {{ (old('b2c_customer')??$customer->b2c_customer) ? 'checked' : '' }}><span class="m-2"> <label for="b2c_customer" class="form-label fw-semibold ml-10"> B2C Customer (No GST Required)</label></span>
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
id="gst_no"  name="gst_no" minlength="15"  maxlength="15"
value="{{ $customer->b2c_customer ? '' : old('gst_no', $customer->gst_no) }}" 
pattern="[0-9]{2}[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}[1-9A-Za-z]{1}Z[0-9A-Za-z]{1}" 
autocomplete="off"
@if($customer->b2c_customer) disabled @endif>
                                            <div class="form-text">Format: 22AAAAA0000A1Z5</div>
                                            @error('gst_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="gst_error"></div>
                                            <div id="gst_success"></div>
                                            </div>
                                             <div class="col-md-6 mb-3">
                                                <label for="pan_no" class="form-label fw-semibold">PAN Number</label>
                                           
                                            <input type="text" class="form-control @error('pan_no') is-invalid @enderror" id="pan_no"  name="pan_no"  value="{{ $customer->b2c_customer ? '' : old('pan_no', $customer->pan_no) }}"   placeholder="PAN Number" @if($customer->b2c_customer) disabled @endif readonly >
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
                                            <input type="text" 
                                                   class="form-control @error('customer_name') is-invalid @enderror" 
                                                   id="customer_name" 
                                                   name="customer_name" 
                                                   value="{{ old('customer_name')??$customer->customer_name }}" 
                                                   placeholder="Enter customer full name"
                                                   required>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="state_code" class="form-label fw-semibold">State Code</label>
                                             <input type="text" class="form-control @error('state_code') is-invalid @enderror"   id="state_code"  name="state_code"  value="{{ (old('state_code')??$customer->gst_state_code) }}"  placeholder="State Code" readonly >
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="division" class="form-label fw-semibold">
                                                Division (Optional) <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="division" name="division"  class="form-control @error('division') is-invalid @enderror" placeholder="Enter Division Name" value="{{ old('division')??$customer->division }}" autocomplete="off" >
                                             <input type="hidden" name="company_id" id="company_id" value="{{$customer->company_id}}">
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
                                                      required>{{ old('address')?? $customer->address}}</textarea>
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
                                                      value="{{ (old('address2')??$customer->address1) }}" 
                                                      placeholder="Enter complete address2"
                                                      required>
                                            @error('address2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="city" class="form-label fw-semibold">
                                                City <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" 
                                                   name="city" 
                                                   value="{{ old('city')?? $customer->city }}" 
                                                   placeholder="Enter city"
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
                                                    <option value="{{ $k }}"  {{ (old('state')??$customer->state) ==$k  ? 'selected' : '' }}> {{ $state }} </option>
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
                                             <select class="form-select @error('country') is-invalid @enderror" 
                                                    id="country" name="country"  required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $v => $country)
                                                    <option value="{{ $v }}" 
                                                            {{ ((old('country'))??$customer->country) ==$v  ? 'selected' : '' }}>
                                                        {{ $country }}
                                                    </option>
                                                @endforeach
                                                
                                            </select>  
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                                   value="{{ old('pincode')??$customer->pincode }}" 
                                                   placeholder="Enter pincode"
                                                   pattern="[0-9]{6}"
                                                   maxlength="6"
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
                                                   value="1" {{ (old('is_billing')??$customer->is_billing) ? 'checked' : '' }}> <span class="m-2"><label for="is_billing" class="form-label fw-semibold">
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
                                                    id="billing_cycle" name="billing_cycle" required>
                                                    <option value="">Select Billing Cycle</option>
                                                         @foreach(config('constants.BILLING_CYCLE') as $k => $val)
                                                        <option value="{{$k}}" {{ (old('billing_cycle')??$customer->billing_cycle )== $k ? 'selected' : '' }}>{{$val}}</option>
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
                                                   value="{{ old('credit_cycle')??$customer->credit_cycle }}" 
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
                                                        <option value="{{$k}}" {{ (old('group')??$customer->group )== $k ? 'selected' : '' }}>{{$val}}</option>
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
                                                <option value="1" {{ (old('status')??$customer->status) == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ (old('status')??$customer->status) == 0 ? 'selected' : '' }}>Inactive</option>
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
                                        <i class="bi bi-check-circle me-1"></i>Update Customer
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