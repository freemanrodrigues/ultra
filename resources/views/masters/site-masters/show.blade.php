@extends('/layouts/master-layout')



@section('content')
<style>
    .card-title {
      font-weight: 600;
      color: #3b82f6;
      margin-bottom: 20px;
    }
    .data-label {
      font-weight: 500;
      color: #555;
    }
    .data-value {
      color: #212529;
    }
    .site-details-row > .col-md-4, .site-details-row > .col-md-12 {
      margin-bottom: 15px;
    }
    .status-badge {
      font-size: 1rem;
      padding: 0.5em 1em;
    }
  </style>
<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="card-title">Site Details</h3>

      <div class="row site-details-row">
        <div class="col-md-12">
          <span class="data-label">Site Name:</span>
          <span class="data-value ms-2">Example Site</span>
        </div>
        <div class="col-md-12">
          <span class="data-label">Address Line 1:</span>
          <span class="data-value ms-2">123 Sample Street</span>
        </div>
        <div class="col-md-4">
          <span class="data-label">City:</span>
          <span class="data-value ms-2">Mumbai</span>
        </div>
        <div class="col-md-4">
          <span class="data-label">State:</span>
          <span class="data-value ms-2">Maharashtra</span>
        </div>
        <div class="col-md-4">
          <span class="data-label">Country:</span>
          <span class="data-value ms-2">India</span>
        </div>
        <div class="col-md-12">
          <span class="data-label">Status:</span>
          <span class="badge bg-success status-badge ms-2">Active</span>
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <a href="#" class="btn btn-primary">Edit</a>
      <a href="#" class="btn btn-secondary">Back</a>
    </div>
  </div>
</div>
@endsection