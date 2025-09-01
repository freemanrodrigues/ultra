@extends('/layouts/master-layout')
@section('content')

<div class="container mt-4">
 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Courier
        <small class="text-muted">({{ $couriers->total() }} total)</small>
    </h1>
    <a href="{{ route('courier.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Courier
    </a>
</div>
@if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
@endif
<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('courier.index') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
               <input type="text" class="form-control search" id="search" name="search" 
value="{{ request('search') }}" placeholder="Search by code or name..." data-txt_id="courier_id"  autocomplete="off">
<input type="hidden" id="courier_id" name="courier_id">
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
                    <a href="{{ route('courier.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>



<!-- Data Table -->
<div class="card">
    <div class="card-body p-0">
        @if($couriers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Courier Code</th>
                            <th>Courier Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_customer_index">
                        @foreach($couriers as $courier)
                        <tr>
                             <td><strong>{{ $courier->id }}</strong></td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $courier->courier_code }}</code>
                            </td>
                            <td>{{ $courier->courier_name }}</td>
                            <td>
                                <span class="badge status-badge {{ $courier->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas {{ $courier->status == 'active' ? 'fa-check' : 'fa-times' }}"></i>
                                    {{ ucfirst($courier->status) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">@if(!empty($courier->created_at)){{ $courier->created_at->format('M d, Y') }} @endif</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                  
                                    <a href="{{ route('courier.edit', $courier) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('courier.destroy',  $courier) }}" method="POST" 
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
            @if($couriers->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $couriers->firstItem() }} to {{ $couriers->lastItem() }} 
                            of {{ $couriers->total() }} results
                        </div>
                        {{ $couriers->links() }}
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
                    <a href="{{ route('courier.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
                <a href="{{ route('courier.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Site
                </a>
            </div>
        @endif
    </div>
</div>
<script src="/js/customer/tbody_customer_index.js?{{date('mmss')}}"></script>
@endsection
