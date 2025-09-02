@extends('/layouts/master-layout')
@section('content')
<link rel="stylesheet" href="/css/customer/autosuggest_pop.css?{{date('mmss')}}" />

<style>
    /* Consistent styling for customer page - Optimized for viewport */
    .customer-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
    }
    
    .customer-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .customer-page .form-control,
    .customer-page .form-select,
    .customer-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Bold, larger headers (14px, weight 700) for better visibility */
    .customer-page .table th {
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
        background-color: #3b82f6;
        color: white;
        border-bottom: 2px solid #1d4ed8;
        height: 2.2rem;
    }
    
    .customer-page .table td {
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
        .customer-page h1 {
            font-size: 1.1rem;
        }
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>

<div class="container-fluid customer-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-building me-2"></i> Customer Management
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $customers->total() }} total)</small>
            </h1>
        </div>
    </div>

    @if (session('success'))
    @if (session('success')['text'])
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success')['text'] }}
           <a href="{{ session('success')['link'] }}" class="alert-link">
                {{ session('success')['link_text'] }}
            </a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    @endif

    <!-- Search and Filter Form with Add New Customer inline -->
    <div class="card shadow-sm compact-form">
        <div class="card-body py-2">
            <div class="row g-2 form-row-aligned">
                <div class="col-md-4">
                    <div class="input-group position-relative">
                        <input type="text" class="form-control search" id="search_input" 
                               placeholder="Search by customer name, division, or site..." 
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
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="test-search">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary clear-search">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('customer.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-user me-1"></i> Add New Customer
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body p-0">
            <div id="customer-table-container">
                @if($customers->count() > 0)
                    <div class="table-responsive compact-table">
                        <table class="table table-hover mb-0 table-striped table-compact">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">ID</th>
                                    <th width="45%">Customer Name</th>
                                    <th width="20%">Group</th>
                                    <th width="12%">Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_customer_index">
                                @foreach($customers as $customer)
                                <tr>
                                    <td>{{ str_pad($customer->cus_mas_id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <a href="{{ route('customer.show', $customer->cus_mas_id) }}" class="text-decoration-none">
                                            {{ $customer->customer_name }}
                                            {{ !empty($customer->gst_state_code) ? ' - '.$customer->gst_state_code : '' }}
                                            {{ !empty($customer->division) ? ' - '.$customer->division : '' }} 
                                            {{ !empty($customer->site_name) ? ' - '.$customer->site_name : '' }}
                                            </code>
                                        </a>
                                    </td>
                                    <td>{{ config('constants.CUSTOMER_GROUP.' . $customer->group) ?? 'N/A' }}</td>
                                    <td> 
                                        @if(($customer->status) === 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customer-site-masters.create', ['customer_id' => $customer->cus_mas_id]) }}" 
                                               class="btn btn-xs btn-outline-info" title="Assign Site">
                                                <i class="bi bi-house-add"></i>
                                            </a>
                                            <a href="{{ route('customer-site-masters.index', ['customer_id' => $customer->cus_mas_id]) }}" 
                                               class="btn btn-xs btn-outline-info" title="List Sites">
                                                <i class="bi bi-list"></i>
                                            </a>
                                            <a href="{{ route('customer.show', $customer->cus_mas_id) }}" 
                                               class="btn btn-xs btn-outline-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('customer.edit', $customer->cus_mas_id) }}" 
                                               class="btn btn-xs btn-outline-warning" title="Edit">
                                               <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('customer.destroy',  $customer->cus_mas_id) }}" method="POST" 
                                                  style="display: inline;" onsubmit="return confirm('Are you sure?')">
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
                    @if($customers->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted pagination-info">
                                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} 
                                    of {{ $customers->total() }} results
                                </div>
                                {{ $customers->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif 
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No customers found</h5>
                        <p class="text-muted mb-3">
                            @if(request()->hasAny(['search', 'status']))
                                No customers match your search criteria.
                            @else
                                Start by adding your first customer.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('customer.index') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                        @endif
                        <a href="{{ route('customer.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            <i class="fas fa-user me-1"></i> Add New Customer
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="searchModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div id="modal-search-results">
        </div>
    </div>
</div>



<script>
// Real-time search functionality
let searchTimeout;
const searchInput = document.getElementById('search_input');
const statusSelect = document.getElementById('status');
const searchLoading = document.getElementById('search-loading');
const customerTableContainer = document.getElementById('customer-table-container');

// Function to perform real-time search
function performRealTimeSearch() {
    const searchTerm = searchInput.value.trim();
    const status = statusSelect.value;
    
    // Show loading indicator
    searchLoading.style.display = 'block';
    
    // Make AJAX request to search endpoint
    fetch(`{{ route('customer.search') }}?search=${encodeURIComponent(searchTerm)}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            // Hide loading indicator
            searchLoading.style.display = 'none';
            
            // Update the table container with search results
            updateCustomerTable(data.customers, data.total, searchTerm, status);
        })
        .catch(error => {
            console.error('Search error:', error);
            searchLoading.style.display = 'none';
        });
}

// Function to update customer table with search results
function updateCustomerTable(customers, total, searchTerm, status) {
    if (customers.length === 0) {
        customerTableContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No customers found</h5>
                <p class="text-muted mb-3">
                    ${searchTerm ? `No customers match "${searchTerm}"` : 'No customers match your criteria.'}
                </p>
                <a href="{{ route('customer.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-times"></i> Clear Search
                </a>
            </div>
        `;
        return;
    }
    
    // Build table HTML
    let tableHTML = `
        <div class="table-responsive compact-table">
            <table class="table table-hover mb-0 table-striped table-compact">
                <thead class="table-light">
                    <tr>
                        <th width="8%">ID</th>
                        <th width="45%">Customer Name</th>
                        <th width="20%">Group</th>
                        <th width="12%">Status</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody_customer_index">
    `;
    
    customers.forEach(customer => {
        tableHTML += `
            <tr>
                <td>${String(customer.cus_mas_id).padStart(5, '0')}</td>
                <td>
                    <a href="/masters/customer/${customer.cus_mas_id}" class="text-decoration-none">
                        <code class="bg-light px-2 py-1 rounded">${customer.customer_name}
                        ${customer.gst_state_code ? ' - ' + customer.gst_state_code : ''}
                        ${customer.division ? ' - ' + customer.division : ''} 
                        ${customer.site_name ? ' - ' + customer.site_name : ''}
                        </code>
                    </a>
                </td>
                <td>${customer.group_name || 'N/A'}</td>
                <td> 
                    ${customer.status === 1 ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-secondary">Inactive</span>'
                    }
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="/masters/customer-site-masters/create?customer_id=${customer.cus_mas_id}" 
                           class="btn btn-xs btn-outline-info" title="Assign Site">
                            <i class="bi bi-house-add"></i>
                        </a>
                        <a href="/masters/customer-site-masters?customer_id=${customer.cus_mas_id}" 
                           class="btn btn-xs btn-outline-info" title="List Sites">
                            <i class="bi bi-list"></i>
                        </a>
                        <a href="/masters/customer/${customer.cus_mas_id}" 
                           class="btn btn-xs btn-outline-info" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="/masters/customer/${customer.cus_mas_id}/edit" 
                           class="btn btn-xs btn-outline-warning" title="Edit">
                           <i class="bi bi-pencil"></i>
                        </a>
                        <form action="/masters/customer/${customer.cus_mas_id}" method="POST" 
                              style="display: inline;" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete">
                               <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableHTML += `
                </tbody>
            </table>
        </div>
        
        <!-- Search Results Info -->
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing ${customers.length} of ${total} results
                    ${searchTerm ? `for "${searchTerm}"` : ''}
                </div>
                <div>
                    <a href="{{ route('customer.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear Search
                    </a>
                </div>
            </div>
        </div>
    `;
    
    customerTableContainer.innerHTML = tableHTML;
}

// Event listeners for real-time search
searchInput.addEventListener('input', function() {
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Set new timeout for search (300ms delay)
    searchTimeout = setTimeout(() => {
        performRealTimeSearch();
    }, 300);
});

statusSelect.addEventListener('change', function() {
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Perform search immediately when status changes
    performRealTimeSearch();
});

// Clear search functionality
document.querySelector('.clear-search').addEventListener('click', function() {
    searchInput.value = '';
    statusSelect.value = '';
    // Reload the page to show all customers
    window.location.href = '{{ route('customer.index') }}';
});
</script>

@endsection
