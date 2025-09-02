@extends('/layouts/master-layout')
@section('content')

<style>
    /* Consistent styling for make page - Optimized for viewport */
    .make-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
    }
    
    .make-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .make-page .form-control,
    .make-page .form-select,
    .make-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Bold, larger headers (14px, weight 700) for better visibility */
    .make-page .table th {
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
        background-color: #3b82f6;
        color: white;
        border-bottom: 2px solid #1d4ed8;
        height: 2.2rem;
    }
    
    .make-page .table td {
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
        .make-page h1 {
            font-size: 1.1rem;
        }
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>

<div class="container-fluid make-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-cogs me-2"></i> Make Management
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $makes->total() }} total)</small>
            </h1>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search and Filter Form with Add New Make inline -->
    <div class="card shadow-sm compact-form">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('make.index') }}" class="row g-2 form-row-aligned">
                <div class="col-md-4">
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
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('make.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('make.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-cogs me-1"></i> Add New Make
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" style="display: none;" class="mb-2">
        <form method="POST" action="{{ route('make.bulk_delete') }}" onsubmit="return confirm('Are you sure you want to delete selected items?')">
            @csrf
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted">With selected:</span>
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
            <input type="hidden" name="ids" id="bulk-ids">
        </form>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body p-0">
            @if($makes->count() > 0)
                <div class="table-responsive compact-table">
                    <table class="table table-hover mb-0 table-striped table-compact">
                        <thead class="table-light">
                            <tr>
                                <th width="8%">ID</th>
                                <th width="15%">Make Code</th>
                                <th width="30%">Make Name</th>
                                <th width="20%">Brand</th>
                                <th width="12%">Status</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($makes as $make)
                            <tr>
                                <td>{{ str_pad($make->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded">{{ $make->make_code }}</code>
                                </td>
                                <td>{{ $make->make_name }}</td>
                                <td>{{ $make->brand_id }}</td>
                                <td> 
                                    @if($make->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('make.show', $make) }}" 
                                           class="btn btn-xs btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('make.edit', $make) }}" 
                                           class="btn btn-xs btn-outline-warning" title="Edit">
                                           <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('make.destroy',  $make) }}" method="POST" 
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
                @if($makes->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted pagination-info">
                                Showing {{ $makes->firstItem() }} to {{ $makes->lastItem() }} 
                                of {{ $makes->total() }} results
                            </div>
                            {{ $makes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif 
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No makes found</h5>
                    <p class="text-muted mb-3">
                        @if(request()->hasAny(['search', 'status']))
                            No makes match your search criteria.
                        @else
                            Start by adding your first make.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('make.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('make.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-cogs me-1"></i> Add New Make
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection