@extends('/layouts/master-layout') 
@section('content')

<style>
    /* Consistent styling for customer show page - Optimized for viewport */
    .customer-show-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
    }
    
    .customer-show-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .customer-show-page .form-control,
    .customer-show-page .form-select,
    .customer-show-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Bold, larger headers (14px, weight 700) for better visibility */
    .customer-show-page .table th {
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
        background-color: #3b82f6;
        color: white;
        border-bottom: 2px solid #1d4ed8;
        height: 2.2rem;
    }
    
    .customer-show-page .table td {
        font-size: 13px !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
    }
    
    /* Distinct header background */
    .page-header {
        background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Compact layout */
    .compact-form {
        margin-bottom: 0.5rem;
    }
    
    .compact-table {
        max-height: none;
        overflow-y: visible;
    }
    
    /* Compact table rows - Consistent height with header */
    .table-compact tbody tr {
        height: 2.2rem;
    }
    
    /* Small action buttons with reduced size */
    .btn-xs {
        padding: 0.1rem 0.2rem;
        font-size: 10px;
        line-height: 1;
        min-width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-xs i {
        font-size: 12px;
    }
    
    /* Form elements height consistency - Reduced height */
    .form-control, .form-select, .btn {
        height: 32px !important;
        padding: 0.25rem 0.5rem;
    }
    
    /* Input group alignment */
    .input-group .form-control {
        height: 32px !important;
    }
    
    .input-group .input-group-text {
        height: 32px !important;
        padding: 0.25rem 0.5rem;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-left: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Row alignment */
    .form-row-aligned {
        align-items: center;
    }
    
    /* Search loading indicator */
    .search-loading {
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        display: none;
    }
    
    /* Compact card and spacing */
    .card-body {
        padding: 0.5rem !important;
    }
    
    .card-footer {
        padding: 0.5rem !important;
    }
    
    /* Reduced margins and padding */
    .mb-2 {
        margin-bottom: 0.5rem !important;
    }
    
    .mb-3 {
        margin-bottom: 0.75rem !important;
    }
    
    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    .py-4 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .customer-show-page h1 {
            font-size: 1.1rem;
        }
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>

<div class="container-fluid customer-show-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-building me-2"></i> Customer Details
                <small class="d-block d-md-inline ms-md-2 opacity-75">ID: {{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</small>
            </h1>
            <a href="{{ route('customer.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
                                    
    <!-- Customer Details Card -->
    <div class="card shadow mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>Customer Information
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive compact-table">
                <table class="table table-hover mb-0 table-striped table-compact">
                    <tbody>
                        <tr>
                            <td width="20%" class="fw-semibold text-muted">Customer Name</td>
                            <td width="30%">{{ $customer->customer_name ?? 'N/A' }}</td>
                            <td width="20%" class="fw-semibold text-muted">B2C Customer</td>
                            <td width="30%">
                                @if(($customer->b2c_customer ?? '') === 1)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Company</td>
                            <td>{{ $companies[$customer->company_id] ?? 'N/A' }}</td>
                            <td class="fw-semibold text-muted">GST No.</td>
                            <td>{{ $customer->gst_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
    <td colspan="2" class="align-top">
        <h6 class="fw-semibold text-muted mb-2 text-center">Address Details</h6>
        <div class="table-responsive">
            <table class="table mb-0 table-sm">
                <tbody>
                    <tr>
                        <td class="fw-semibold text-muted">Address Name</td>
                        <td>{{ $customer->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Address2</td>
                        <td>{{ $customer->address1 ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Address3</td>
                        <td>{{ $customer->address2 ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">City</td>
                        <td>{{ $customer->city ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">State</td>
                        <td>{{ $states[$customer->state] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Country</td>
                        <td>{{ $countries[$customer->country] ?? 'N/A' }}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </td>

    <td colspan="2" class="align-top">
        <h6 class="fw-semibold text-muted mb-2 text-center">Billing Details</h6>
        <div class="table-responsive">
            <table class="table mb-0 table-sm">
                <tbody>
                    <tr>
                        <td class="fw-semibold text-muted">Group</td>
                        <td>{{ config('constants.CUSTOMER_GROUP')[$customer->group] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Billing Cycle</td>
                        <td>{{ config('constants.BILLING_CYCLE')[$customer->billing_cycle] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Credit Cycle</td>
                        <td>{{ $customer->credit_cycle ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Status</td>
                        <td>
                            <span class="badge {{ ($customer->status ?? '') === 1 ? 'bg-success' : 'bg-secondary' }}">
                                {{ ($customer->status ?? '') === 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Sales Person</td>
                        <td>{{ optional($customer->salesPerson)->firstname }} {{ optional($customer->salesPerson)->lastname }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </td>
</tr>
                          
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Customer Sites Card -->
    <div class="card shadow mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-map-marker-alt me-2"></i>Customer Sites
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $customerSiteMasters->count() }} total)</small>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($customerSiteMasters->count() > 0)
                <div class="table-responsive compact-table">
                    <table class="table table-hover mb-0 table-striped table-compact">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Site Name</th>
                                <th width="15%">Site Code</th>
                                <th width="25%">Customer Site Name</th>
                                <th width="25%">Address</th>
                                <th width="10%">Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customerSiteMasters as $customerSiteMaster)
                                <tr>
                                    <td>{{ $customerSiteMaster->siteMaster->site_name }}</td>
                                    <td>
                                        <strong>{{ $customerSiteMaster->site_customer_code }}</strong>
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
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No customer sites found</h5>
                    <p class="text-muted mb-3">Get started by adding your first customer site.</p>
                    <a href="{{ route('customer-site-masters.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-map-marker-alt me-1"></i> Add New Customer Site
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Customer Contacts Card -->
    <div class="card shadow mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-address-book me-2"></i>Customer Contacts
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $contactmasters->count() }} total)</small>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($contactmasters->count() > 0)
                <div class="table-responsive compact-table">
                    <table class="table table-hover mb-0 table-striped table-compact">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Email</th>
                                <th width="20%">Name</th>
                                <th width="15%">Phone</th>
                                <th width="25%">Company</th>
                                <th width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contactmasters as $user)
                                <tr data-id="{{ $user->id }}">
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->company->company_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->status ? 'success' : 'secondary' }}">
                                            {{ $user->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No customer contacts found</h5>
                    <p class="text-muted mb-3">Get started by adding your first contact.</p>
                    <a href="{{ route('contacts-masters.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-user me-1"></i> Add New Contact
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
                    
