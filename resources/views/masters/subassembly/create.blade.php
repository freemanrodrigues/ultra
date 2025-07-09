@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sub Assembly Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('subassembly.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="sub_assemblies_code" class="form-label">Sub Assembly Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('sub_assemblies_code') is-invalid @enderror" 
                                   id="sub_assemblies_code" 
                                   name="sub_assemblies_code" 
                                   value="{{ old('sub_assemblies_code') }}" 
                                   placeholder="Enter Unique Sub Assembly code (e.g., grade001)"
                                   maxlength="50"
                                   required>
                            @error('sub_assemblies_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="sub_assemblies_name" class="form-label">Sub Assembly Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('sub_assemblies_name') is-invalid @enderror" 
                                   id="sub_assemblies_name" 
                                   name="sub_assemblies_name" 
                                   value="{{ old('sub_assemblies_name') }}" 
                                   placeholder="Enter Sub Assembly name"
                                   maxlength="255"
                                   required>
                            @error('sub_assemblies_name')
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
                                <i class="bi bi-check-circle"></i> Create Sub Assembly Master
                            </button>
                            <a href="{{ route('subassembly.index') }}" class="btn btn-secondary">
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
    document.getElementById('sub_assemblies_name').addEventListener('input', function() {
        const gradeName = this.value;
        const gradeCodeField = document.getElementById('sub_assemblies_code');
        
        if (gradeCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = gradeName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            gradeCodeField.value = code;
        }
    });
</script>
@endpush