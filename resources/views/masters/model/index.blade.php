@extends('/layouts/master-layout')
@section('content')

<style>
    /* Consistent styling for model page - Optimized for viewport */
    .model-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
    }
    
    .model-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .model-page .form-control,
    .model-page .form-select,
    .model-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Bold, larger headers (14px, weight 700) for better visibility */
    .model-page .table th {
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
        background-color: #3b82f6;
        color: white;
        border-bottom: 2px solid #1d4ed8;
        height: 2.2rem;
    }
    
    .model-page .table td {
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
        .model-page h1 {
            font-size: 1.1rem;
        }
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>

<div class="container-fluid model-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-cube me-2"></i> Model Management
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $models->total() }} total)</small>
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

    <!-- Search and Filter Form with Add New Model inline -->
    <div class="card shadow-sm compact-form">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('model.index') }}" class="row g-2 form-row-aligned">
                <div class="col-md-4">
                    <div class="input-group position-relative">
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by name..." 
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
                        <a href="{{ route('model.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('model.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-cube me-1"></i> Add New Model
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" style="display: none;" class="mb-2">
        <form method="POST" action="{{ route('model.bulk_delete') }}" onsubmit="return confirm('Are you sure you want to delete selected items?')">
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
            @if($models->count() > 0)
                <div class="table-responsive compact-table">
                    <table class="table table-hover mb-0 table-striped table-compact">
                        <thead class="table-light">
                            <tr>
                                <th width="8%">ID</th>
                                <th width="25%">Make</th>
                                <th width="35%">Model Name</th>
                                <th width="12%">Status</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($models as $model)
                            <tr>
                                <td>{{ str_pad($model->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $model->make->make_name ?? 'N/A' }}</td>
                                <td>{{ $model->model }}</td>
                                <td> 
                                    @if($model->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('model.show', $model) }}" 
                                           class="btn btn-xs btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('model.edit', $model) }}" 
                                           class="btn btn-xs btn-outline-warning" title="Edit">
                                           <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('model.destroy',  $model) }}" method="POST" 
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
                @if($models->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted pagination-info">
                                Showing {{ $models->firstItem() }} to {{ $models->lastItem() }} 
                                of {{ $models->total() }} results
                            </div>
                            {{ $models->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif 
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No models found</h5>
                    <p class="text-muted mb-3">
                        @if(request()->hasAny(['search', 'status']))
                            No models match your search criteria.
                        @else
                            Start by adding your first model.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('model.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                    @endif
                    <a href="{{ route('model.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-cube me-1"></i> Add New Model
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection