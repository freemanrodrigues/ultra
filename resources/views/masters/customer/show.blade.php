@extends('/layouts/master-layout1') 
@section('content')
   
   <div class="container my-5">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white text-center rounded-top">
      <h4 class="mb-0">Customer Details</h4>
    </div>

    <div class="card-body">

      {{-- Row 1 --}}
      <div class="row mb-3">
        <div class="col-md-3 text-end fw-semibold text-muted">Customer Name:</div>
        <div class="col-md-3">{{ $customer->customer_name ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">Display Name:</div>
        <div class="col-md-3">{{ $customer->display_name ?? 'N/A' }}</div>
      </div>

      {{-- Row 2 --}}
      <div class="row mb-3">
        <div class="col-md-3 text-end fw-semibold text-muted">Address:</div>
        <div class="col-md-3">{{ $customer->address ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">Address1:</div>
        <div class="col-md-3">{{ $customer->address1 ?? 'N/A' }}</div>
      </div>

      {{-- Row 3 --}}
      <div class="row mb-3">
        <div class="col-md-3 text-end fw-semibold text-muted">Company:</div>
        <div class="col-md-3">{{ $customer->company_id ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">GST No.:</div>
        <div class="col-md-3">{{ $customer->gst_no ?? 'N/A' }}</div>
      </div>

      {{-- Row 4 --}}
      <div class="row mb-3">
        <div class="col-md-3 text-end fw-semibold text-muted">City:</div>
        <div class="col-md-3">{{ $customer->city ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">State:</div>
        <div class="col-md-3">{{ $customer->state ?? 'N/A' }}</div>
      </div>

      {{-- Row 5 --}}
      <div class="row mb-3">
        <div class="col-md-3 text-end fw-semibold text-muted">Country:</div>
        <div class="col-md-3">{{ $customer->country ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">Pincode:</div>
        <div class="col-md-3">{{ $customer->pincode ?? 'N/A' }}</div>
      </div>

      {{-- Row 6 --}}
      <div class="row mb-3">
        <div class="col-md-3 text-end fw-semibold text-muted">Billing Cycle:</div>
        <div class="col-md-3">{{ $customer->billing_cycle ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">Credit Cycle:</div>
        <div class="col-md-3">{{ $customer->credit_cycle ?? 'N/A' }}</div>
      </div>

      {{-- Row 7 --}}
      <div class="row mb-3 align-items-center">
        <div class="col-md-3 text-end fw-semibold text-muted">Status:</div>
        <div class="col-md-3">
          @if(($customer->status ?? '') === 'active')
            <span class="badge bg-success">Active</span>
          @else
            <span class="badge bg-secondary">Inactive</span>
          @endif
        </div>

        <div class="col-md-3 text-end fw-semibold text-muted">Group:</div>
        <div class="col-md-3">{{ $customer->group ?? 'N/A' }}</div>
      </div>

    </div>
  </div>
</div>


   
            @endsection
                    