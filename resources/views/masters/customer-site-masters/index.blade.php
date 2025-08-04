@extends('/layouts/master-layout')



@section('content')
<div class="container mt-4">

 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Customer Site Master
        <small class="text-muted">({{ $siteMasters->total() }} total)</small>
    </h1>
    <a href="{{ route('site-masters.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Site
    </a>
</div>

 <!-- Search and Filter Form -->
    <div class="search-form h-auto m-2 ">
        <form method="GET" action="{{ route('site-masters.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search by code or name...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort_by" class="form-label">Sort By</label>
                <select class="form-select" id="sort_by" name="sort_by">
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                    <option value="site_code" {{ request('sort_by') === 'site_code' ? 'selected' : '' }}>Site Code</option>
                    <option value="site_name" {{ request('sort_by') === 'site_name' ? 'selected' : '' }}>Site Name</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort_order" class="form-label">Order</label>
                <select class="form-select" id="sort_order" name="sort_order">
                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Descending</option>
                    <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                    <a href="{{ route('site-masters.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <small class="text-muted">
                Showing {{ $siteMasters->firstItem() ?? 0 }} to {{ $siteMasters->lastItem() ?? 0 }} 
                of {{ $siteMasters->total() }} results
            </small>
        </div>
    </div>
    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($siteMasters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Site Code</th>
                                <th>Site Name</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Devices</th>
                                <th>Created Date</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siteMasters as $siteMaster)
                                <tr>
                                    <td>
                                        <strong>{{ $siteMaster->site_customer_code }}</strong>
                                    </td>
                                    <td>{{ $siteMaster->site_customer_name }}</td>
                                    <td>@if(!empty($customers[$siteMaster->customer_id])){{ $customers[$siteMaster->customer_id] }} @endif</td>
                                    <td>{!! $siteMaster->status_badge !!}</td>
                                    <td><a href="{{route('site-device-list',$siteMaster->id )}}">Devices</a></td>
                                    <td>
                                        <small class="text-muted">
                                        @if(!empty($siteMaster->created_at))
                                            {{ $siteMaster->created_at->format('M d, Y') }}
                                        @endif    
                                        </small>
                                    </td>
                                    <td class="table-actions">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('site-masters.show', $siteMaster) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('site-masters.edit', $siteMaster) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('customer-site-masters.assign-contact', $siteMaster->company_id) }}" class="btn btn-sm btn-outline-warning" title="Assign">
                                       <i class="bi bi-person-plus"></i> </a>
                                            <!-- Toggle Status -->
                                            <form method="POST" action="{{ route('site-masters.toggle-status', $siteMaster) }}" 
                                                  style="display: inline;" id="toggle-form-{{ $siteMaster->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-{{ $siteMaster->status === 'active' ? 'warning' : 'success' }}"
                                                        title="{{ $siteMaster->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                                        onclick="confirmToggle(document.getElementById('toggle-form-{{ $siteMaster->id }}'), '{{ $siteMaster->status }}')">
                                                    <i class="bi bi-{{ $siteMaster->status === 'active' ? 'pause-circle' : 'play-circle' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('site-masters.destroy', $siteMaster) }}" 
                                                  style="display: inline;" id="delete-form-{{ $siteMaster->id }}"  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        title="Delete"
                                                        onclick="confirmDelete(document.getElementById('delete-form-{{ $siteMaster->id }}'))">
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
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No site masters found</h5>
                    <p class="text-muted">Get started by adding your first site master.</p>
                    <a href="{{ route('site-masters.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Site
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if($siteMasters->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $siteMasters->links() }}
        </div>
    @endif
</div>
@endsection