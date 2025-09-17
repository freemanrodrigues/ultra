@extends('/layouts/master-layout')
@section('content')

<style>
    /* Consistent styling for customer-site-masters page - Optimized for viewport */
    .customer-site-masters-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
    }
    
    .customer-site-masters-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .customer-site-masters-page .form-control,
    .customer-site-masters-page .form-select,
    .customer-site-masters-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Bold, larger headers (14px, weight 700) for better visibility */
    .customer-site-masters-page .table th {
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
        background-color: #3b82f6;
        color: white;
        border-bottom: 2px solid #1d4ed8;
        height: 2.2rem;
    }
    
    .customer-site-masters-page .table td {
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
        .customer-site-masters-page h1 {
            font-size: 1.1rem;
        }
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>

<div class="container-fluid customer-site-masters-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-building me-2"></i> Customer Site Management
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $customerSiteMasters->total() }} total)</small>
            </h1>
            <a href="{{ route('customer-site-masters.create', ['customer_id' => $_GET['customer_id'] ?? null]) }}" 
               class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Customer Site
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success')['text'] }}<br>
            <a href="{{ session('success')['link'] }}" class="alert-link">
                {{ session('success')['link_text'] }}
            </a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search and Filter Form -->
    <div class="card shadow-sm compact-form">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('customer-site-masters.index') }}" class="row g-2 form-row-aligned">
                <div class="col-md-3">
                    <div class="input-group position-relative">
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by code or name..." 
                               autocomplete="off">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <div class="search-loading" id="search-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="site_code" {{ request('sort_by') === 'site_code' ? 'selected' : '' }}>Site Code</option>
                        <option value="site_name" {{ request('sort_by') === 'site_name' ? 'selected' : '' }}>Site Name</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="sort_order" name="sort_order">
                        <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('customer-site-masters.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body p-0">
            @if($customerSiteMasters->count() > 0)
                <div class="table-responsive compact-table">
                    <table class="table table-hover mb-0 table-striped table-compact">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Customer</th>
                                <th width="20%">Customer Site Name</th>
                                <th width="20%">Site Name</th>
                                <th width="15%">Site Code</th>
                                <th width="15%">Contact</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customerSiteMasters as $customerSiteMaster)
                            <tr>
                                <td>@if(!empty($customers[$customerSiteMaster->customer_id])){{ $customers[$customerSiteMaster->customer_id] }} @endif</td>
                                <td>{{ $customerSiteMaster->siteMaster->site_name }}</td>
                                <td>
                                    <strong>{{ $customerSiteMaster->site_customer_code }}</strong>
                                </td>
                                <td>{{ $customerSiteMaster->site_customer_name }}</td>
                                <td>
                                    <a class="btn btn-xs btn-outline-secondary m-1 get_contacts" 
                                       data-id="{{ $customerSiteMaster->id }}" 
                                       data-customer_id="{{ $customerSiteMaster->customer_id }}"  
                                       data-company_id="{{ $customerSiteMaster->company_id }}"  
                                       data-bs-toggle="modal" 
                                       data-bs-target="#m3" 
                                       title="Assign Contact">
                                        <i class="bi bi-person-plus"></i>
                                    </a>
                                    <a class="btn btn-xs btn-outline-info m-1 assigned_contact" 
                                       data-id="{{ $customerSiteMaster->site_master_id }}" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#assigned_contact_Modal" 
                                       title="View Assigned Contacts">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customer-site-masters.edit', $customerSiteMaster) }}" 
                                           class="btn btn-xs btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('customer-site-masters.destroy', $customerSiteMaster) }}" 
                                              method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($customerSiteMasters->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted pagination-info">
                                Showing {{ $customerSiteMasters->firstItem() ?? 0 }} to {{ $customerSiteMasters->lastItem() ?? 0 }} 
                                of {{ $customerSiteMasters->total() }} results
                            </div>
                            {{ $customerSiteMasters->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif 
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No customer sites found</h5>
                    <p class="text-muted mb-3">
                        @if(request()->hasAny(['search', 'status']))
                            No customer sites match your search criteria.
                        @else
                            Start by adding your first customer site.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('customer-site-masters.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('customer-site-masters.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-building me-1"></i> Add New Customer Site
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="assign_contact_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h5 class="modal-title">Assigned Contacts for <span id=''></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container my-4" id="assign_contact_div">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="assigned_contact_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h5 class="modal-title">Assigned Contacts for <span id=''></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container my-4" id="assigned_contact_div">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="m3" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h5 class="modal-title">Assigned Contacts for </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="assignContact">
                    <div class="container my-4" id="m3_div">
                    </div>
                </form>
            </div>
            <div id="formErrors" class="text-danger"></div>
            <div class="modal-footer">
                <input type="hidden" name="site_id" id="site_id">
                <input type="hidden" name="customer_id" id="customer_id">
                <button type="button" class="btn btn-success" id="assign_submit">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="/js/customer/assign-contact.js?{{date('mmss')}}"></script>    
<script>
    const URL = "{{ route('contact-assignments.store') }}";
</script>

@endsection