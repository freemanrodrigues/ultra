@extends('/layouts/master-layout')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{asset('/css/customer/autosuggest_pop.css')}}?{{date('mmss')}}" />

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <div class="card shadow-lg border-0">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                    <div class="form-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1"><i class="bi bi-clipboard-data me-2"></i>Sample Receipt</h3>
                                <p class="mb-0 opacity-75">Laboratory Sample Tracking Form</p>
                            </div>
                            <div class="text-end">
                                <small class="opacity-75">Form ID: SR-2025</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('sample.store') }}" id="sampleForm">
                        @csrf
                            <!-- Header Information Row -->
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    <label for="lotNo" class="form-label fw-semibold">Lot No.</label>
                                    <input type="text" class="form-control" id="lotNo"  name="lot_no" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="courierName" class="form-label fw-semibold">Courier Name</label>
                                    <div class="icon-input">
                                        <select   class="form-control" id="courierName" name="courier_id" required>
                                        <option value="" >Select Courier </option>
                                            @foreach($courier_mst as $k => $courier)
                                                <option value="{{ $k }}" {{ request('courier_id') == $k ? 'selected' : '' }}>{{ $courier }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="noOfSamples" class="form-label fw-semibold">No of Samples</label>
                                    <input type="number" class="form-control required-field" id="noOfSamples" name="no_of_samples" value="{{ old('no_of_samples')?? 0 }}" min="1">
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label fw-semibold">Date</label>
                                    <input type="date" class="form-control required-field" id="date"  name="sample_date" value="{{ old('sample_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="podNo" class="form-label fw-semibold">POD No.</label>
                                    <input type="text" class="form-control" id="podNo" name="pod_no" value="{{ old('pod_no') }}">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Customer Information Section -->
                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded h-100">
                                        <h5 class="text-primary mb-3"><i class="bi bi-person-circle me-2"></i>Customer Information</h5>
                                      

                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label fw-semibold">Customer</label>
                                            <div class="icon-input">
                                            
<div class="myDropdownCover">
<input type="hidden" id="customer_id" name="customer_id" value="{{ old('customer_id') }}"> 
<input type="text" class="form-control search"  name="search" id="id_customer" data-txt_id="customer_id"
value="{{ old('search') }}" placeholder="Search by code or name..."  autocomplete="off">
<div id="myDropdown_customer_id" class="myDropdown"></div>
</div>
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
  
                                        <div class="mb-3">
                                            <div id="address_place_holder">
                                            
                                            </div>
                                        </div>

                                        
                                    </div>
                                </div>

                                <!-- Site Contact Information Section -->
                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded h-100">
                                        <h5 class="text-primary mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Site Contact Information</h5>
                                        
                                        <div class="mb-3">
                                            <label for="site_master_id" class="form-label fw-semibold">Site Master</label><br>
                                            
                                            <div class="icon-input" >
                                               <select name="site_master_id" id="site_master_id" class="form-control">
                                               <option value="">Select Customer Site</option> 
                                               </select>
                                                <i class="bi bi-person-badge"></i>
                                            </div>
     
                                        </div>

                                        <div class="mb-3">
                                             <div id="siteAddress">
                                            
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="contact_id" class="form-label fw-semibold">Contact</label><br>
                                            
                                            <div class="icon-input" >
                                               <div id="contact_place_holder">
                                            
                                            </div>
                                                <i class="bi bi-person-badge"></i>
                                            </div>
     
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <!-- Work Order and Timeline Section -->
                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded">
                                        <h5 class="text-primary mb-3"><i class="bi bi-calendar-check me-2"></i>Work Order & Timeline</h5>
                                        
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="reportExpectedBy" class="form-label fw-semibold">Report Expected by</label>
                                                <input type="date" class="form-control required-field" id="reportExpectedBy" name="expected_report_date"  value="{{ old('expected_report_date') }}">
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label for="workOrderDate" class="form-label fw-semibold">Work Order Date</label>
                                                <input type="date" class="form-control required-field" id="workOrderDate" name="work_order_date" value="{{ old('work_order_date') }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="workOrder" class="form-label fw-semibold">Work Order</label>
                                            <input type="text" class="form-control" id="workOrder" name="work_order" value="{{ old('work_order') }}">

                                             <div class="icon-input" >
                                               <select name="workOrder_desc" id="workOrder_desc" class="form-control" required>
                                               <option value="">Select Work Order</option> 
                                               </select>
                                                <i class="bi bi-person-badge"></i>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="freightCharges" class="form-label fw-semibold">Freight Charges</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control" id="freightCharges" name="freight_charges" value="{{ old('freight_charges')?? 0 }}" min="0" step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="section-divider p-3 rounded">
                                        <h5 class="text-primary mb-3"><i class="bi bi-box-seam me-2"></i>Additional Information</h5>
                                        
                                        <div class="mb-3">
                                            <label for="additionalInfo" class="form-label fw-semibold">Additional Information</label>
                                            <textarea class="form-control" id="additionalInfo" name="additional_info" rows="6" placeholder="Enter any additional notes or special instructions">{{ old('additional_info')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Tracking Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="section-divider p-3 rounded">
                                        <h5 class="text-primary mb-3"><i class="bi bi-arrow-repeat me-2"></i>Sample Tracking Timeline</h5>
                                        
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 mb-3">
                                                <label for="sampleDispatchedFromSite" class="form-label fw-semibold">Sample Dispatched from Site</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleDispatchedFromSite" name="site_sample_dispacted_date" value="{{ old('site_sample_dispacted_date')}}">
                                            </div>
                                            <div class="col-lg-6 col-md-6 mb-3">
                                                <label for="sampleCollectedFromCenter" class="form-label fw-semibold">Sample Collected from Collection Center</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleCollectedFromCenter" name="collection_center_sample_received_date"  value="{{ old('collection_center_sample_received_date')}}">
                                            </div>
                                            <div class="col-lg-6 col-md-6 mb-3">
                                                <label for="sampleReceivedAtCenter" class="form-label fw-semibold">Sample Received at Collection Center</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleReceivedAtCenter" name="collection_center_sample_collected_date"  value="{{ old('collection_center_sample_collected_date')}}">
                                            </div>
                                            <div class="col-lg-6 col-md-6 mb-3">
                                                <label for="sampleReceivedAtLab" class="form-label fw-semibold">Sample Received at Lab</label>
                                                <input type="datetime-local" class="form-control required-field" id="sampleReceivedAtLab" name="lab_sample_received_date"   value="{{ old('lab_sample_received_date')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                        </button>
                                    <!--    <button type="button" class="btn btn-outline-primary" id="previewBtn">
                                            <i class="bi bi-eye me-1"></i>Preview
                                        </button>
                                        <button type="button" class="btn btn-outline-success" id="saveDraftBtn">
                                            <i class="bi bi-download me-1"></i>Save Draft
                                        </button> -->
                                        <button type="submit" class="btn btn-custom text-white">
                                            <i class="bi bi-check-circle me-1"></i>Submit Receipt
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('/js/customer/function_autosuggest33.js')}}?{{date('mmss')}}"></script>
    <script src="{{asset('/js/sample/create-sample.js')}}?{{date('mmss')}}"></script>
    
@endsection
