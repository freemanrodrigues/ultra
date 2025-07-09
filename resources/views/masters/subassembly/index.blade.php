@extends('/layouts/master-layout')
@section('content')

<div class="container mt-4">
 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Sub Assembly
        <small class="text-muted">({{ $subassemblys->total() }} total)</small>
    </h1>
    <a href="{{ route('subassembly.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Sub Assembly
    </a>
</div>

<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('subassembly.index') }}">
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
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('subassembly.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div id="bulk-actions" style="display: none;" class="mb-3">
    <form method="POST" action="{{ route('subassembly.bulk_delete') }}" onsubmit="return confirm('Are you sure you want to delete selected items?')">
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
        @if($subassemblys->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all" onchange="selectAll()" class="form-check-input">
                            </th>
                            <th>ID</th>
                            <th>Sub Assembly Code</th>
                            <th>Sub Assembly Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subassemblys as $subassembly)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_ids[]" value="{{ $subassembly->id }}" 
                                       onchange="toggleBulkActions()" class="form-check-input">
                            </td>
                            <td><strong>{{ $subassembly->id }}</strong></td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $subassembly->sub_assemblies_code }}</code>
                            </td>
                            <td>{{ $subassembly->sub_assemblies_name }}</td>
                            <td>
                                <span class="badge status-badge {{ $subassembly->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas {{ $subassembly->status == 'active' ? 'fa-check' : 'fa-times' }}"></i>
                                    {{ ucfirst($subassembly->status) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">@if(!empty($subassembly->created_at)){{ $subassembly->created_at->format('M d, Y') }} @endif</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('subassembly.show', $subassembly) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('subassembly.edit', $subassembly) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('subassembly.destroy',  $subassembly) }}" method="POST" 
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
            @if($subassemblys->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $subassemblys->firstItem() }} to {{ $subassemblys->lastItem() }} 
                            of {{ $subassemblys->total() }} results
                        </div>
                        {{ $subassemblys->links() }}
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
                    <a href="{{ route('subassembly.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
                <a href="{{ route('subassembly.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Site
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
