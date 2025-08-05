@extends('/layouts/master-layout')



@section('content')
<div class="container mt-4">

 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Make Model
        <small class="text-muted">({{ $make_models->total() }} total)</small>
    </h1>
    <a href="{{ route('make-model-masters.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Make Model
    </a>
</div>

 <!-- Search and Filter Form -->
    <div class="search-form h-auto m-2 ">
        <form method="GET" action="{{ route('make-model-masters.index') }}" class="row g-3">
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
                    <option value="make" {{ request('sort_by') === 'make' ? 'selected' : '' }}>Make</option>
                    <option value="model" {{ request('sort_by') === 'model' ? 'selected' : '' }}>Model</option>
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
                    <a href="{{ route('make-model-masters.index') }}" class="btn btn-outline-secondary">
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
                Showing {{ $make_models->firstItem() ?? 0 }} to {{ $make_models->lastItem() ?? 0 }} 
                of {{ $make_models->total() }} results
            </small>
        </div>
    </div>
    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($make_models->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                               
                                <th>Make</th>
                                <th>Model</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($make_models as $make_model)
                                <tr>
                                    
                                    <td>{{ $make_model->make }}</td>
                                   <td>{{ $make_model->model }}</td>
                                   <td>{{ $make_model->description }}</td>
                                   <td>{{ $make_model->status }}</td>
                                     <td>
                                        <small class="text-muted">
                                        @if(!empty($make_model->created_at))
                                            {{ $make_model->created_at->format('M d, Y') }}
                                        @endif    
                                        </small>
                                    </td>
                                    <td class="table-actions">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('make-model-masters.show', $make_model) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('make-model-masters.edit', $make_model) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <!-- Toggle Status -->
                                            <form method="POST" action="{{ route('make-model-masters.toggle-status', $make_model) }}" 
                                                  style="display: inline;" id="toggle-form-{{ $make_model->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-{{ $make_model->status === 'active' ? 'warning' : 'success' }}"
                                                        title="{{ $make_model->status === 1 ? 'Deactivate' : 'Activate' }}"
                                                        onclick="confirmToggle(document.getElementById('toggle-form-{{ $make_model->id }}'), '{{ $make_model->status }}')">
                                                    <i class="bi bi-{{ $make_model->status === 0 ? 'pause-circle' : 'play-circle' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('make-model-masters.destroy', $make_model) }}" 
                                                  style="display: inline;" id="delete-form-{{ $make_model->id }}"  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        title="Delete"
                                                        onclick="confirmDelete(document.getElementById('delete-form-{{ $make_model->id }}'))">
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
                    <h5 class="mt-3 text-muted">No Make Model Master found</h5>
                    <p class="text-muted">Get started by adding your first make model master.</p>
                    <a href="{{ route('make-model-masters.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Make Model
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if($make_models->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $make_models->links() }}
        </div>
    @endif
</div>
@endsection