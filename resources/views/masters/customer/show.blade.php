@extends('/layouts/master-layout1') 
@section('content')
   
   <div class="container">
   <div class="card ">
   <div class="row mb-2 mt-2">
         <div class="col-sm-12 text-center"><h3 class="mb-0">Customer Details</h3></div>
        </div>
        <div class="row">
            <div class="col-sm-2 m-1 "><h5 class="text-center">Customer Name:</h5></div>
            <div class="col-sm-4 m-1">{{ $customer->customer_name ?? 'N/A' }}</div>
            <div class="col-sm-2 m-1"><h5 class="text-center">Display Name:</h5></div>
            <div class="col-sm-3 m-1">{{ $customer->display_name ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="col-sm-2 m-1"><h5 class="text-center">Address:</h5></div>
             <div class="col-sm-4 m-1">{{ $customer->address ?? 'N/A' }}</div>
            <div class="col-sm-2 m-1"><h5 class="text-center">Address1:</h5></div>
            <div class="col-sm-3 m-1">{{ $customer->address1 ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="col-sm-2 m-1"><h5 class="text-center">Company</h5></div>
            <div class="col-sm-4 m-1">{{ $customer->company_id ?? 'N/A' }}</div>
            <div class="col-sm-2 m-1"><h5 class="text-center">GST No.</h5></div>
            <div class="col-sm-3 m-1">{{ $customer->gst_no ?? 'N/A' }}</div>
        </div>
        <div class="row ">
            <div class="col-sm-2 m-1"><h5 class="text-center">City</h5></div>
            <div class="col-sm-4 m-1">{{ $customer->city ?? 'N/A' }}</div>
            <div class="col-sm-2 m-1 "><h5 class="text-center">State</h5></div>
            <div class="col-sm-3 m-1 ">{{ $customer->state ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="col-sm-2 m-1"><h5 class="text-center">Country</h5></div>
            <div class="col-sm-4 m-1">{{ $customer->country ?? 'N/A' }}</div>
            <div class="col-sm-2 m-1"><h5 class="text-center">Pincode</h5></div>
            <div class="col-sm-3 m-1">{{ $customer->pincode ?? 'N/A' }}</div>
        </div>
         <div class="row">
            <div class="col-sm-2 m-1"><h5 class="text-center">Billing Cycle</h5></div>
            <div class="col-sm-4 m-1">{{ $customer->billing_cycle ?? 'N/A' }}</div>
            <div class="col-sm-2 m-1"><h5 class="text-center">Credit Cycle</div>
            <div class="col-sm-3 m-1">{{ $customer->credit_cycle ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="col-sm-2 m-1"><h5 class="text-center">Status</h5></div>
            <div class="col-sm-4 m-1">
                @if(($customer->status ?? '') == 'active')
                    <span class="badge bg-success rounded-pill">Active</span>
                @else
                    <span class="badge bg-secondary rounded-pill">Inactive</span>
                @endif
            </div>
            <div class="col-sm-2 m-1"><h5 class="text-center">Group</h5></div>
            <div class="col-sm-3 m-1">{{ $customer->group ?? 'N/A' }}</div>
        </div>
      </div>
    </div>

   
            @endsection
                    