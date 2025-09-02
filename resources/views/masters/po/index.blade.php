@extends('/layouts/master-layout')
@section('content')

<style>
    /* Consistent styling with customer list page */
    .po-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
    }
    
    .po-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .po-page .form-control,
    .po-page .form-select,
    .po-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Bold, larger headers (14px, weight 700) for better visibility */
    .po-page .table th {
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
        background-color: #3b82f6;
        color: white;
        border-bottom: 2px solid #1d4ed8;
        height: 2.2rem;
    }
    
    .po-page .table td {
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
    
    /* Compact spacing */
    .compact-form {
        margin-bottom: 0.5rem;
    }
    
    .card-body {
        padding: 0.5rem !important;
    }
    
    /* Small action buttons */
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
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .po-page h1 {
            font-size: 1.1rem;
        }
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>

<div class="container-fluid po-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-file-invoice me-2"></i> Purchase Orders
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $pos->total() }} total)</small>
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

    <!-- Add New PO Button -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('po.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            <i class="fas fa-file-invoice me-1"></i> Create New PO
        </a>
    </div>

    <!-- PO List Table -->
    <div class="card shadow">
        <div class="card-body p-0">
            @if($pos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 table-striped">
                        <thead class="table-light">
                            <tr>
                                <th width="12%">PO Number</th>
                                <th width="20%">Customer</th>
                                <th width="15%">Site</th>
                                <th width="10%">PO Date</th>
                                <th width="10%">Start Date</th>
                                <th width="10%">End Date</th>
                                <th width="10%">Total Amount</th>
                                <th width="8%">Status</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pos as $po)
                            <tr>
                                <td>
                                    <strong>{{ $po->po_number }}</strong>
                                </td>
                                <td>
                                    @if($po->customer)
                                        {{ $po->customer->customer_name }}
                                        @if($po->customer->division)
                                            - {{ $po->customer->division }}
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($po->site)
                                        {{ $po->site->site_name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $po->po_date ? $po->po_date->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $po->po_start_date ? $po->po_start_date->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $po->po_end_date ? $po->po_end_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if($po->total_amount)
                                        ₹{{ number_format($po->total_amount, 2) }}
                                    @else
                                        <span class="text-muted">₹0.00</span>
                                    @endif
                                </td>
                                <td>
                                    @if($po->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($po->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('po.show', $po) }}" 
                                           class="btn btn-xs btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('po.edit', $po) }}" 
                                           class="btn btn-xs btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('po.destroy', $po) }}" method="POST" 
                                              style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this PO?')">
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
                @if($pos->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $pos->firstItem() }} to {{ $pos->lastItem() }} 
                                of {{ $pos->total() }} results
                            </div>
                            {{ $pos->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif 
            @else
                <div class="text-center py-4">
                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Purchase Orders found</h5>
                    <p class="text-muted mb-3">
                        Start by creating your first Purchase Order.
                    </p>
                    <a href="{{ route('po.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        <i class="fas fa-file-invoice me-1"></i> Create New PO
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
