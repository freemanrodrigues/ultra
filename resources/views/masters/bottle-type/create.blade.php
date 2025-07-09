@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ferrography Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('ferrography.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="ferrography_code" class="form-label">ferrography Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ferrography_code') is-invalid @enderror" 
                                   id="ferrography_code" 
                                   name="ferrography_code" 
                                   value="{{ old('ferrography_code') }}" 
                                   placeholder="Enter unique ferrography code (e.g., ferrography001)"
                                   maxlength="50"
                                   required>
                            @error('ferrography_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="ferrography_name" class="form-label">ferrography Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ferrography_name') is-invalid @enderror" 
                                   id="ferrography_name" 
                                   name="ferrography_name" 
                                   value="{{ old('ferrography_name') }}" 
                                   placeholder="Enter ferrography name"
                                   maxlength="255"
                                   required>
                            @error('ferrography_name')
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
                                <i class="bi bi-check-circle"></i> Create ferrography Master
                            </button>
                            <a href="{{ route('ferrography.index') }}" class="btn btn-secondary">
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
    // Auto-generate ferrography code based on ferrography name (optional)
    document.getElementById('ferrography_name').addEventListener('input', function() {
        const ferrographyName = this.value;
        const ferrographyCodeField = document.getElementById('ferrography_code');
        
        if (ferrographyCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = ferrographyName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            ferrographyCodeField.value = code;
        }
    });
</script>
@endpush