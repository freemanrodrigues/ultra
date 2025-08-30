@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sample Type Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sample-type.store') }}">
                        @csrf
                        
                        

                        <div class="mb-3">
                            <label for="sample_type_name" class="form-label">Sample Type Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('sample_type_name') is-invalid @enderror" 
                                   id="sample_type_name" 
                                   name="sample_type_name" 
                                   value="{{ old('sample_type_name') }}" 
                                   placeholder="Enter Sample Type name"
                                   maxlength="255"
                                   required>
                            @error('sample_type_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                    <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('description') is-invalid @enderror" 
                                   id="description" 
                                   name="description" 
                                   value="{{ old('description') }}" 
                                   placeholder="Enter Remark"
                                   maxlength="255"
                                   required>
                            @error('description')
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
                                <i class="bi bi-check-circle"></i> Create Sample Type Master
                            </button>
                            <a href="{{ route('sample-type.index') }}" class="btn btn-secondary">
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
    // Auto-generate sample_type code based on sample_type name (optional)
    document.getElementById('sample_type_name').addEventListener('input', function() {
        const sample_typeName = this.value;
        const sample_typeCodeField = document.getElementById('sample_type_code');
        
        if (sample_typeCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = sample_typeName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            sample_typeCodeField.value = code;
        }
    });
</script>
@endpush