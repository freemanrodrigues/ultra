@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">grade Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('grade.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="grade_code" class="form-label">grade Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('grade_code') is-invalid @enderror" 
                                   id="grade_code" 
                                   name="grade_code" 
                                   value="{{ old('grade_code') }}" 
                                   placeholder="Enter unique grade code (e.g., grade001)"
                                   maxlength="50"
                                   required>
                            @error('grade_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="grade_name" class="form-label">grade Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('grade_name') is-invalid @enderror" 
                                   id="grade_name" 
                                   name="grade_name" 
                                   value="{{ old('grade_name') }}" 
                                   placeholder="Enter grade name"
                                   maxlength="255"
                                   required>
                            @error('grade_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create grade Master
                            </button>
                            <a href="{{ route('grade.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-generate grade code based on grade name (optional)
    document.getElementById('grade_name').addEventListener('input', function() {
        const gradeName = this.value;
        const gradeCodeField = document.getElementById('grade_code');
        
        if (gradeCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = gradeName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            gradeCodeField.value = code;
        }
    });
</script>
@endpush