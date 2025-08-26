@extends('/layouts/master-layout')



@section('content')
<link rel="stylesheet" href="/css/customer/autosuggest_pop.css?{{date('mmss')}}" />
<div class="container mt-4">

 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Site Master
        <small class="text-muted">({{ $siteMasters->total() }} total)</small>
    </h1>
    <a href="{{ route('site-masters.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Site
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
       @if(session('success')['text'])  {{ session('success')['text'] }}<br> @endif
        @if(session('success')['link'])
        <a href="{{ session('success')['link'] }}" class="alert-link">
            {{ session('success')['link_text'] }}
        </a>.
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

 <!-- Search and Filter Form -->
    <div class="search-form h-auto m-2 ">
        <form method="GET" action="{{ route('site-masters.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="id_site_master" class="form-label">Search</label>
                <input type="text" class="form-control search" id="id_site_master" name="search" 
                       value="{{ request('search') }}" placeholder="Search by Site Name..."  data-txt_id="site_master_id"  autocomplete="off">
                <input type="hidden" id="site_master_id" name="site_master_id">    
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
                               
                                <th>Site Name</th>
                                <th>City/District</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_customer_index">
                            @foreach($siteMasters as $siteMaster)
                                <tr>
                                    
                                    <td>{{ $siteMaster->site_name }}</td>
                                   <td>{{ $siteMaster->city }}</td>
                                   <td>{!! $siteMaster->status_badge !!}
                                        @if(($siteMaster->status ?? '') === 1)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                   </td>
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
            {{ $siteMasters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
<div id="searchModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div id="modal-search-results"></div>
    </div>
</div>  
<script src="/js/customer/tbody_customer_index.js?{{date('mmss')}}"></script>
@endsection