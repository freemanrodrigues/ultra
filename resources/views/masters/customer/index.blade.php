@extends('/layouts/master-layout')
@section('content')

<div class="container mt-4">
 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Customer
        <small class="text-muted">({{-- $customers->total() --}} total)</small>
    </h1>
    <a href="{{ route('customer.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Customer
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success')['text'] }}
       <a href="{{ session('success')['link'] }}" class="alert-link">
            {{ session('success')['link_text'] }}
        </a>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('customer.index') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search by code or name...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div id="bulk-actions" style="display: none;" class="mb-3">
    <form method="POST" action="{{ route('customer.bulk_delete') }}" onsubmit="return confirm('Are you sure you want to delete selected items?')">
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
<div class="card">
    <div class="card-body p-0">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all" onchange="selectAll()" class="form-check-input">
                            </th>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>GST No</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_ids[]" value="{{ $customer->id }}" 
                                       onchange="toggleBulkActions()" class="form-check-input">
                            </td>
                            <td><strong>{{ $customer->id }}</strong></td>
                            <td>    <a href="{{ route('customer.show', $customer) }}" class="text-decoration-none">
                                <code class="bg-light px-2 py-1 rounded">{{ $customer->customer_name }}</code></a>
                            </td>
                            <td>{{ $customer->gst_no }}</td>
                            <td>{{-- $customer->mobile --}}
                           
                            </td>
                            <td>
                                <span class="badge status-badge {{ $customer->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas {{ $customer->status == 1 ? 'fa-check' : 'fa-times' }}"></i>
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">@if(!empty($customer->created_at)){{ $customer->created_at->format('M d, Y') }} @endif</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
<a href="{{ route('customer-site-masters.index', ['customer_id' => $customer->id]) }}" 
                                       class="btn btn-sm btn-outline-info" title="List Sites">
                                        <i class="bi bi-list"></i>
                                    </a>
                               
                                    <a href="{{ route('customer.show', $customer) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('customer.edit', $customer) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('customer.destroy',  $customer) }}" method="POST" 
                                          style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
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
                        <div class="text-muted">
                            Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} 
                            of {{ $customers->total() }} results
                        </div>
                        {{ $customers->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif 
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Site Masters Found</h4>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'status']))
                        No sites match your search criteria.
                    @else
                        Start by creating your first site master.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
                <a href="{{ route('customer.create') }}" class="btn btn-primary new-customer">
                    <i class="fas fa-plus"></i> Add New Customer
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
