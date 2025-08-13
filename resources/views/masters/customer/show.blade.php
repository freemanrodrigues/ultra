@extends('/layouts/master-layout1') 
@section('content')
   
   <div class="container my-2">
   <div class="text-end my-2">
   <a href="{{ route('customer.index') }}" class="btn btn-outline-primary">
                                        Back
                                    </a></div>
  <div class="card shadow-sm border-1">
    <div class="card-header bg-primary text-white text-center rounded-top">
      <h4 class="mb-0">Customer Details</h4>
    </div>

    <div class="card-body ">

      {{-- Row 1 --}}
      <div class="row mb-3 custom-border-row">
        <div class="col-md-3 text-end fw-semibold text-muted">Customer Name:</div>
        <div class="col-md-3">{{ $customer->customer_name ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">Display Name:</div>
        <div class="col-md-3">{{ $customer->display_name ?? 'N/A' }}</div>
      </div>

      {{-- Row 2 --}}
      <div class="row mb-3 custom-border-row">
        <div class="col-md-3 text-end fw-semibold text-muted">Address:</div>
        <div class="col-md-3">{{ $customer->address ?? 'N/A' }}</div>

        <div class="col-md-3 text-end fw-semibold text-muted">Address1:</div>
        <div class="col-md-3">{{ $customer->address1 ?? 'N/A' }}</div>
      </div>

      {{-- Row 3 --}}
      <div class="row mb-3 custom-border-row">
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
          @if(($customer->status ?? '') === 1)
            <span class="badge bg-success">Active</span>
          @else
            <span class="badge bg-secondary">Inactive</span>
          @endif
        </div>

        <div class="col-md-3 text-end fw-semibold text-muted">Group:</div>
        <div class="col-md-3">{{ $customer->group ?? 'N/A' }}</div>
        <div class="col-md-12 text-center"></div>
      </div>

    </div>
  </div>
 <div class="text-center mt-5 bg-primary text-white text-center rounded-top">
<h3 class="text-center">Customer Contacts</h3> </div>
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


   
            @endsection
                    