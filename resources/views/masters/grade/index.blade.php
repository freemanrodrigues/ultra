@extends('/layouts/master-layout')
@section('content')

<div class="container mt-4">
 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Grade
        <small class="text-muted">({{ $grades->total() }} total)</small>
    </h1>
    <a href="{{ route('grade.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Grades
    </a>
</div>

<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('grade.index') }}">
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
                    <a href="{{ route('grade.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div id="bulk-actions" style="display: none;" class="mb-3">
    <form method="POST" action="{{ route('grade.bulk_delete') }}" onsubmit="return confirm('Are you sure you want to delete selected items?')">
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
        @if($grades->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all" onchange="selectAll()" class="form-check-input">
                            </th>
                            <th>ID</th>
                            <th>Site Code</th>
                            <th>Site Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_ids[]" value="{{ $grade->id }}" 
                                       onchange="toggleBulkActions()" class="form-check-input">
                            </td>
                            <td><strong>{{ $grade->id }}</strong></td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $grade->grade_code }}</code>
                            </td>
                            <td>{{ $grade->grade_name }}</td>
                            <td>
                                <span class="badge status-badge {{ $grade->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas {{ $grade->status == 'active' ? 'fa-check' : 'fa-times' }}"></i>
                                    {{ ucfirst($grade->status) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">@if(!empty($grade->created_at)){{ $grade->created_at->format('M d, Y') }} @endif</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('grade.show', $grade) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('grade.edit', $grade) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('grade.destroy',  $grade) }}" method="POST" 
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
            @if($grades->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $grades->firstItem() }} to {{ $grades->lastItem() }} 
                            of {{ $grades->total() }} results
                        </div>
                        {{ $grades->links() }}
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
                    <a href="{{ route('grade.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
                <a href="{{ route('grade.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Site
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
