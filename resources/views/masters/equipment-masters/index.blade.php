@extends('/layouts/master-layout')



@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">Equipment Masters</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Equipment Masters</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('equipment-masters.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Equipment
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filters Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Search & Filter
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('equipment-masters.index') }}" id="filterForm">
                        <div class="row g-3">
                            <!-- Search -->
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by code or name...">
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            
                            <!-- Actions -->
                            <div class="col-md-5">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                    <a href="{{ route('equipment-masters.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Clear
                                    </a>
                                    <button type="button" class="btn btn-outline-info" id="exportBtn">
                                        <i class="fas fa-download me-2"></i>Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Equipment</h6>
                                    <h4 class="mb-0">{{ $equipmentMasters->total() }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tools fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Active</h6>
                                    <h4 class="mb-0">{{ $equipmentMasters->where('status', 1)->count() }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Inactive</h6>
                                    <h4 class="mb-0">{{ $equipmentMasters->where('status', 0)->count() }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">This Page</h6>
                                    <h4 class="mb-0">{{ $equipmentMasters->count() }}</h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-list fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Equipment List
                        @if(request('search') || request('status') !== null)
                            <span class="badge bg-secondary ms-2">Filtered</span>
                        @endif
                    </h6>
                    <div class="d-flex gap-2">
                        <!-- Bulk Actions -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                    type="button" 
                                    id="bulkActions" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                <i class="fas fa-cogs me-2"></i>Bulk Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="bulkActions">
                                <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">
                                    <i class="fas fa-check me-2"></i>Activate Selected
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">
                                    <i class="fas fa-times me-2"></i>Deactivate Selected
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">
                                    <i class="fas fa-trash me-2"></i>Delete Selected
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($equipmentMasters->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Equipment Name</th>
                                        
                                        <th>Serial Number</th>
                                        <th>Make Model</th>
                                        <th width="100">Status</th>
                                        <th width="120">Created</th>
                                        <th width="120">Updated</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipmentMasters as $equipment)
                                        <tr>
                                            <td>
                                                <input type="checkbox" 
                                                       class="form-check-input row-checkbox" 
                                                       value="{{ $equipment->id }}">
                                            </td>
                                            <td>
                                              <a href="{{ route('equipment-masters.show', $equipment) }}" 
                                                       
                                                       title="View Details"> {{ $equipment->equipment_name }}</a>
                                            </td>
                                            <td>{{ $equipment->serial_number }}</td>
                                            <td>{{ $equipment->	make_model_id }}</td>
                                            <td>
                                                <span class="badge bg-{{ $equipment->status ? 'success' : 'danger' }}">
                                                    {{ $equipment->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $equipment->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $equipment->updated_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">


                                         
                                           
                                                    <!-- View Button -->
                                                 
                                                
                                                    <a href="{{ route('equipment-masters.show', $equipment) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       title="View Details">
                                                       <i class="bi bi-eye"></i>
                                                    </a>
                                                    
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('equipment-masters.edit', $equipment) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="Edit">
                                                       <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <!-- Delete/Restore Button -->
                                                    <button type="button" 
                                                            class="btn btn-outline-{{ $equipment->status ? 'danger' : 'success' }} btn-sm" 
                                                            title="{{ $equipment->status ? 'Deactivate' : 'Activate' }}"
                                                            onclick="toggleStatus({{ $equipment->id }}, '{{ $equipment->em_name }}', {{ $equipment->status ? 'false' : 'true' }})">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($equipmentMasters->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Showing {{ $equipmentMasters->firstItem() }} to {{ $equipmentMasters->lastItem() }} 
                                        of {{ $equipmentMasters->total() }} results
                                    </div>
                                    {{ $equipmentMasters->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Equipment Found</h5>
                            <p class="text-muted">
                                @if(request('search') || request('status') !== null)
                                    No equipment matches your search criteria.
                                    <br><a href="{{ route('equipment-masters.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Clear Filters
                                    </a>
                                @else
                                    Get started by adding your first equipment.
                                    <br><a href="{{ route('equipment-masters.create') }}" class="btn btn-sm btn-primary mt-2">
                                        Add Equipment
                                    </a>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="statusModalMessage"></p>
                <div class="alert alert-info" id="statusModalEquipment"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="statusForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" id="statusConfirmBtn">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkModal" tabindex="-1" aria-labelledby="bulkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkModalLabel">Bulk Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="bulkModalMessage"></p>
                <div id="bulkModalCount" class="alert alert-info"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="bulkConfirmBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    selectAllCheckbox?.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === rowCheckboxes.length;
        });
    });
    
    // Auto-submit search form on status change
    document.getElementById('status')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Export functionality
    document.getElementById('exportBtn')?.addEventListener('click', function() {
        // You can implement CSV/Excel export here
        alert('Export functionality would be implemented here');
    });
});

// Toggle equipment status
function toggleStatus(equipmentId, equipmentName, activate) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const message = document.getElementById('statusModalMessage');
    const equipmentDiv = document.getElementById('statusModalEquipment');
    const confirmBtn = document.getElementById('statusConfirmBtn');
    
    const action = activate ? 'activate' : 'deactivate';
    const actionText = activate ? 'Activate' : 'Deactivate';
    
    message.textContent = `Are you sure you want to ${action} this equipment?`;
    equipmentDiv.innerHTML = `<strong>Equipment:</strong> ${equipmentName}`;
    
    form.action = `/equipment-masters/${equipmentId}`;
    confirmBtn.textContent = actionText;
    confirmBtn.className = `btn btn-${activate ? 'success' : 'danger'}`;
    
    modal.show();
}

// Bulk actions
function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one equipment to perform bulk action.');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('bulkModal'));
    const message = document.getElementById('bulkModalMessage');
    const countDiv = document.getElementById('bulkModalCount');
    const confirmBtn = document.getElementById('bulkConfirmBtn');
    
    let actionText = '';
    switch (action) {
        case 'activate':
            actionText = 'activate';
            break;
        case 'deactivate':
            actionText = 'deactivate';
            break;
        case 'delete':
            actionText = 'permanently delete';
            break;
    }
    
    message.textContent = `Are you sure you want to ${actionText} the selected equipment?`;
    countDiv.textContent = `${checkedBoxes.length} equipment(s) selected`;
    confirmBtn.textContent = `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Selected`;
    
    confirmBtn.onclick = function() {
        // Here you would implement the actual bulk action
        // For now, just show an alert
        alert(`Bulk ${actionText} would be performed on ${checkedBoxes.length} items`);
        modal.hide();
    };
    
    modal.show();
}
</script>
@endpush
@endsection