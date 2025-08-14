@extends('/layouts/master-layout1') 
@section('content')
     <style>
    .bordered-cell {
      border: 1px solid #dee2e6; 
      padding: 10px;
      text-align: center;
    }
  </style>
   <div class="container my-2">
   <div class="text-end my-2">
   <a href="{{ route('customer.index') }}" class="btn btn-outline-primary">  Back</a></div>
                                    
  <div class="card shadow-sm border-1">
    <div class="card-header bg-primary text-white text-center rounded-top">
      <h4 class="mb-0">Customer Details</h4>
    </div>

<div class="container mt-4 ">
  <div class="row">
    <div class="col bordered-cell text-end fw-semibold text-muted">Customer Name</div>
    <div class="col bordered-cell">{{ $customer->customer_name ?? 'N/A' }}</div>
    <div class="col bordered-cell text-end fw-semibold text-muted">B2C_Customer:</div>
    <div class="col bordered-cell">@if(($customer->b2c_customer ?? '') === 1)
                     <span class="badge bg-success">Yes</span>
                  @else
                      <span class="badge bg-secondary">No</span>
                  @endif</div>
  </div>
<div class="row">
    <div class="col bordered-cell text-end fw-semibold text-muted">Company</div>
    <div class="col bordered-cell">{{ $companies[$customer->company_id] ?? 'N/A' }}</div>
    <div class="col bordered-cell text-end fw-semibold text-muted">GST No.:</div>
    <div class="col bordered-cell">{{ $customer->gst_no ?? 'N/A' }}</div>
  </div>
<div class="row">
    <div class="col bordered-cell text-end fw-semibold text-muted">Address Name</div>
    <div class="col bordered-cell">{{ $customer->address ?? 'N/A' }}</div>
    <div class="col bordered-cell text-end fw-semibold text-muted">Address1:</div>
    <div class="col bordered-cell">{{ $customer->address1 ?? 'N/A' }}</div>
  </div>
    
      
<div class="row">
    <div class="col bordered-cell text-end fw-semibold text-muted">City</div>
    <div class="col bordered-cell">{{ $customer->city ?? 'N/A' }}</div>
     <div class="col bordered-cell text-end fw-semibold text-muted">Pincode:</div>
    <div class="col bordered-cell">{{ $customer->pincode ?? 'N/A' }}</div>
  </div>
     <div class="row">
         <div class="col bordered-cell text-end fw-semibold text-muted">State:</div>
    <div class="col bordered-cell">{{ $states[$customer->state]  ?? 'N/A' }}</div>
    <div class="col bordered-cell text-end fw-semibold text-muted">Country</div>
    <div class="col bordered-cell">{{ $countries[$customer->country] ?? 'N/A' }}</div>
   
  </div> 

  <div class="row">
    <div class="col bordered-cell text-end fw-semibold text-muted">Billing Cycle:</div>
    <div class="col bordered-cell">{{ $customer->billing_cycle ?? 'N/A' }}</div>
    <div class="col bordered-cell text-end fw-semibold text-muted">Credit Cycle:</div>
    <div class="col bordered-cell">{{ $customer->credit_cycle ?? 'N/A' }}</div>
  </div> 

  <div class="row">
    <div class="col bordered-cell text-end fw-semibold text-muted">Status:</div>
    <div class="col bordered-cell"> @if(($customer->status ?? '') === 1)
            <span class="badge bg-success">Active</span>
          @else
            <span class="badge bg-secondary">Inactive</span>
          @endif</div>
    <div class="col bordered-cell text-end fw-semibold text-muted">Group:</div>
    <div class="col bordered-cell">{{ $customer->group ?? 'N/A' }}</div>
  </div> 

  </div>

<div class="text-center mt-5 bg-primary text-white text-center rounded-top">
<h3 class="text-center">Customer Sites</h3> </div>
 <div class="card">
        <div class="card-body p-0">
            @if($customerSiteMasters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Site Code</th>
                                <th>Site Name</th>
                                <th>Address</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customerSiteMasters as $customerSiteMaster)
                                <tr>
                                    <td>
                                        <strong>{{$customerSiteMaster->site_customer_code }}</strong>
                                    </td>
                                    <td>{{ $customerSiteMaster->site_customer_name }}</td>
                                    <td>{{ $customerSiteMaster->address }}</td>
                                    <td>
                                        <small class="text-muted">
                                        @if(!empty($customerSiteMaster->created_at))
                                            {{ $customerSiteMaster->created_at->format('M d, Y') }}
                                        @endif    
                                        </small>
                                    </td>
                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-3">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No site masters found</h5>
                    <p class="text-muted">Get started by adding your first site master.</p>
                    <a href="{{ route('customer-site-masters.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Customer Site
                    </a>
                </div>
            @endif
        </div>
    </div>

 <div class="text-center mt-5 bg-primary text-white text-center rounded-top">
<h3 class="text-center">Customer Contacts</h3> </div>
  <div class="card">
  <div class="card-body p-0">
      @if($contactmasters->count() > 0)
  <!-- Users Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Phone</th>
                <th>company</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contactmasters as $user)
            <tr data-id="{{ $user->id }}">
                <td>{{ $user->email }}</td>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{  $user->company->company_name; }}</td>
                <td><span class="badge bg-{{ $user->status ? 'success' : 'secondary' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span></td>
               
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
    <div class="text-center py-3">
        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3 text-muted">No Contact found</h5>
        <p class="text-muted">Get started by adding your first contact.</p>
        <a href="{{ route('contacts-masters.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Contact
        </a>
    </div>
@endif

   
            @endsection
                    