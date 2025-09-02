@extends('/layouts/master-layout')

@section('content')
<link rel="stylesheet" href="/css/customer/autosuggest_pop.css?{{date('mmss')}}" />
<style>
    .site-name-link {
        transition: all 0.3s ease;
        border-bottom: 1px solid transparent;
    }
    .site-name-link:hover {
        color: #1d4ed8 !important;
        border-bottom-color: #1d4ed8;
        text-decoration: none !important;
        transform: translateY(-1px);
    }
    .site-name-link:active {
        transform: translateY(0);
    }
</style>

<div class="container mt-4">
    <!-- Search and Filter Form -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-building text-primary"></i> Site Master List
            <small class="text-muted">({{ $siteMasters->total() }} total)</small>
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('site-masters.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Site
            </a>
            <a href="{{ route('site-masters.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-map-marked-alt"></i> Map View
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            @if(session('success')['text']) {{ session('success')['text'] }}<br> @endif
            @if(session('success')['link'])
                <a href="{{ session('success')['link'] }}" class="alert-link">
                    {{ session('success')['link_text'] }}
                </a>.
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search and Filter Form -->
    <div class="search-form h-auto">
        <form method="GET" action="{{ route('site-masters.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="id_site_master" class="form-label">Search</label>
                <div class="input-group">    
                    <input type="text" class="form-control search" id="id_site_master" name="search" 
                           value="{{ request('search') }}" placeholder="Search by Site Name..."  data-txt_id="site_master_id"  autocomplete="off">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>                
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
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('site-masters.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            @if($siteMasters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Site Name</th>
                                <th>City/District</th>
                                <th>State</th>
                                <th>Coordinates</th>
                                <th>Status</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_customer_index">
                            @foreach($siteMasters as $siteMaster)
                                <tr>
                                    <td>
                                        <a href="{{ route('site-masters.show', $siteMaster) }}" 
                                           class="text-primary text-decoration-none fw-semibold site-name-link"
                                           title="Click to view site details">
                                            {{ $siteMaster->site_name }}
                                        </a>
                                    </td>
                                    <td>{{ $siteMaster->city }}</td>
                                    <td>{{ $siteMaster->state_table ? $siteMaster->state_table->statename : 'N/A' }}</td>
                                    <td>
                                        @if($siteMaster->lat && $siteMaster->long)
                                            <span class="text-success">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                {{ $siteMaster->lat }}, {{ $siteMaster->long }}
                                            </span>
                                        @else
                                            <span class="text-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Missing
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($siteMaster->status ?? '') === 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
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
