@extends('/layouts/master-layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">Edit Equipment Master</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('equipment-masters.index') }}">Equipment Masters</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('equipment-masters.show', $equipmentMaster) }}">{{ $equipmentMaster->em_name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('equipment-masters.show', $equipmentMaster) }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>View Details
                    </a>
                    <a href="{{ route('equipment-masters.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Update Equipment Master Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Current Information Alert -->
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Current Record:</strong> {{ $equipmentMaster->em_code }} - {{ $equipmentMaster->em_name }}
                        <span class="badge bg-{{ $equipmentMaster->status ? 'success' : 'danger' }} ms-2">
                            {{ $equipmentMaster->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <form action="{{ route('equipment-masters.update', $equipmentMaster) }}" 
                          method="POST" 
                          id="equipmentEditForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Equipment Code -->
                            <div class="col-md-6 mb-3">
                                <label for="em_code" class="form-label fw-bold">
                                    Equipment Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('em_code') is-invalid @enderror" 
                                       id="em_code" 
                                       name="em_code" 
                                       value="{{ old('em_code', $equipmentMaster->em_code) }}"
                                       placeholder="Enter equipment code"
                                       maxlength="50"
                                       required>
                                @error('em_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Unique identifier for the equipment (max 50 characters)</div>
                            </div>

                            <!-- Equipment Name -->
                            <div class="col-md-6 mb-3">
                                <label for="em_name" class="form-label fw-bold">
                                    Equipment Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('em_name') is-invalid @enderror" 
                                       id="em_name" 
                                       name="em_name" 
                                       value="{{ old('em_name', $equipmentMaster->em_name) }}"
                                       placeholder="Enter equipment name"
                                       maxlength="255"
                                       required>
                                @error('em_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Full name of the equipment (max 255 characters)</div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status">
                                    <option value="1" {{ old('status', $equipmentMaster->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $equipmentMaster->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Set the current status of the equipment</div>
                            </div>

                            <!-- Record Information -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Record Information</label>
                                <div class="form-control-plaintext">
                                    <small class="text-muted">
                                        <strong>Created:</strong> {{ $equipmentMaster->created_at->format('M d, Y h:i A') }}<br>
                                        <strong>Last Updated:</strong> {{ $equipmentMaster->updated_at->format('M d, Y h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Change Detection -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="alert alert-light border" id="changeAlert" style="display: none;">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    <strong>Changes detected!</strong> Make sure to save your changes.
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal">
                                            <i class="fas fa-trash me-2"></i>
                                            {{ $equipmentMaster->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('equipment-masters.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                        <button type="reset" class="btn btn-outline-secondary" id="resetBtn">
                                            <i class="fas fa-undo me-2"></i>Reset Changes
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Equipment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    {{ $equipmentMaster->status ? 'Deactivate' : 'Activate' }} Equipment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <strong>{{ $equipmentMaster->status ? 'deactivate' : 'activate' }}</strong> this equipment?</p>
                <div class="alert alert-info">
                    <strong>Equipment:</strong> {{ $equipmentMaster->em_code }} - {{ $equipmentMaster->em_name }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('equipment-masters.destroy', $equipmentMaster) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-{{ $equipmentMaster->status ? 'danger' : 'success' }}">
                        {{ $equipmentMaster->status ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store original values for change detection
    const originalValues = {
        em_code: document.getElementById('em_code').value,
        em_name: document.getElementById('em_name').value,
        status: document.getElementById('status').value
    };
    
    // Change detection
    function detectChanges() {
        const currentValues = {
            em_code: document.getElementById('em_code').value,
            em_name: document.getElementById('em_name').value,
            status: document.getElementById('status').value
        };
        
        const hasChanges = Object.keys(originalValues).some(key => 
            originalValues[key] !== currentValues[key]
        );
        
        const changeAlert = document.getElementById('changeAlert');
        if (hasChanges) {
            changeAlert.style.display = 'block';
        } else {
            changeAlert.style.display = 'none';
        }
    }
    
    // Add event listeners for change detection
    document.getElementById('em_code').addEventListener('input', detectChanges);
    document.getElementById('em_name').addEventListener('input', detectChanges);
    document.getElementById('status').addEventListener('change', detectChanges);
    
    // Reset form to original values
    document.getElementById('resetBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('em_code').value = originalValues.em_code;
        document.getElementById('em_name').value = originalValues.em_name;
        document.getElementById('status').value = originalValues.status;
        detectChanges();
    });
    
    // Form validation
    document.getElementById('equipmentEditForm').addEventListener('submit', function(e) {
        const emCode = document.getElementById('em_code').value.trim();
        const emName = document.getElementById('em_name').value.trim();
        
        if (!emCode || !emName) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Confirm if there are no changes
        const hasChanges = Object.keys(originalValues).some(key => 
            originalValues[key] !== document.getElementById(key).value
        );
        
        if (!hasChanges) {
            if (!confirm('No changes detected. Do you still want to update?')) {
                e.preventDefault();
                return false;
            }
        }
    });
    
    // Warn about unsaved changes when leaving page
    let hasUnsavedChanges = false;
    
    document.getElementById('em_code').addEventListener('input', () => hasUnsavedChanges = true);
    document.getElementById('em_name').addEventListener('input', () => hasUnsavedChanges = true);
    document.getElementById('status').addEventListener('change', () => hasUnsavedChanges = true);
    
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
    
    // Remove unsaved changes warning when form is submitted
    document.getElementById('equipmentEditForm').addEventListener('submit', function() {
        hasUnsavedChanges = false;
    });
});
</script>
@endpush
@endsection