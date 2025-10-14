@extends('/layouts/master-layout')
@section('content')

<style>
    .quotation-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .page-header h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .badge-status {
        font-size: 10px;
        padding: 0.25rem 0.5rem;
    }
    
    .table {
        font-size: 12px;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 11px;
    }
</style>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid quotation-page">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>
                        <i class="fas fa-file-invoice me-2"></i> Quotations
                    </h1>
                    <a href="{{ route('quotation.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i> New Quotation
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filter Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('quotation.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control form-control-sm" name="search" 
                                       value="{{ request('search') }}" placeholder="Quotation Number, Reference">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select form-select-sm" name="status">
                                    <option value="">All Statuses</option>
                                    @foreach($statuses as $key => $status)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Company</label>
                                <select class="form-select form-select-sm" name="company_id">
                                    <option value="">All Companies</option>
                                    @foreach($companies as $id => $name)
                                        <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control form-control-sm" name="date_from" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control form-control-sm" name="date_to" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quotations Table -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Quotation List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Quotation #</th>
                                    <th>Date</th>
                                    <th>Company</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Valid Until</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quotations as $quotation)
                                    <tr>
                                        <td>
                                            <strong>{{ $quotation->formatted_quotation_number }}</strong>
                                            @if($quotation->reference_number)
                                                <br><small class="text-muted">Ref: {{ $quotation->reference_number }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $quotation->quotation_date->format('d-M-Y') }}</td>
                                        <td>{{ $quotation->company->company_name ?? 'N/A' }}</td>
                                        <td>{{ $quotation->customer->customer_name ?? 'N/A' }}</td>
                                        <td class="text-end">₹{{ number_format($quotation->total_amount, 2) }}</td>
                                        <td>
                                            {{ $quotation->valid_until->format('d-M-Y') }}
                                            @if($quotation->is_expired)
                                                <span class="badge bg-danger badge-status">Expired</span>
                                            @elseif($quotation->days_until_expiry <= 7 && $quotation->days_until_expiry >= 0)
                                                <span class="badge bg-warning badge-status">Expiring Soon</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    1 => 'secondary', // Draft
                                                    2 => 'warning',   // Pending Approval
                                                    3 => 'success',   // Approved
                                                    4 => 'info',      // Converted to Order
                                                    5 => 'dark',      // Closed
                                                    6 => 'danger',    // Cancelled
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$quotation->status] ?? 'secondary' }} badge-status">
                                                {{ $quotation->status_name }}
                                            </span>
                                        </td>
                                        <td>{{ $quotation->creator->firstname ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('quotation.show', $quotation->id) }}" 
                                                   class="btn btn-sm btn-info btn-action" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($quotation->status == 1)
                                                    <a href="{{ route('quotation.edit', $quotation->id) }}" 
                                                       class="btn btn-sm btn-primary btn-action" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('quotation.pdf', $quotation->id) }}" 
                                                   class="btn btn-sm btn-success btn-action" title="Download PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No quotations found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($quotations->hasPages())
                    <div class="card-footer">
                        {{ $quotations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</main>

@stop

