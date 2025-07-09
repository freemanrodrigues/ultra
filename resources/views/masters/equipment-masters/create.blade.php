@extends('/layouts/master-layout')


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">Add Equipment Master</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('equipment-masters.index') }}">Equipment Masters</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add New</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('equipment-masters.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Equipment Master Information
                    </h5>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('equipment-masters.store') }}" method="POST" id="equipmentForm">
                        @csrf
                        
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
                                       value="{{ old('em_code') }}"
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
                                       value="{{ old('em_name') }}"
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
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Set the current status of the equipment</div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('equipment-masters.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Equipment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate equipment code (optional)
    const emNameInput = document.getElementById('em_name');
    const emCodeInput = document.getElementById('em_code');
    
    // Function to generate code from name
    function generateCode(name) {
        return name.toUpperCase()
                  .replace(/[^A-Z0-9\s]/g, '')
                  .replace(/\s+/g, '_')
                  .substring(0, 20);
    }
    
    // Auto-generate code when name is entered (if code is empty)
    emNameInput.addEventListener('input', function() {
        if (!emCodeInput.value) {
            emCodeInput.value = generateCode(this.value);
        }
    });
    
    // Form validation
    document.getElementById('equipmentForm').addEventListener('submit', function(e) {
        const emCode = document.getElementById('em_code').value.trim();
        const emName = document.getElementById('em_name').value.trim();
        
        if (!emCode || !emName) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });
});
</script>
@endpush
@endsection