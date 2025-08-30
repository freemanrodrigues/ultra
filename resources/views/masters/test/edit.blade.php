@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Test Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('test.update', $test->id) }}">
                        @csrf
                        @method('PUT')
                        

                        <div class="mb-3">
                            <label for="test_name" class="form-label">Test Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('test_name') is-invalid @enderror" 
                                   id="test_name" 
                                   name="test_name" 
                                   value="{{ old('test_name')??$test->test_name }}" 
                                   placeholder="Enter sample_type name"
                                   maxlength="255"
                                   required>
                            @error('test_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                     <div class="mb-3">
                            <label for="default_unit" class="form-label">Default Unit <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('default_unit') is-invalid @enderror" 
                                   id="default_unit" 
                                   name="default_unit" 
                                   value="{{ old('default_unit')??$test->default_unit }}" 
                                   placeholder="Enter Default Unit"
                                   maxlength="255"
                                   required>
                            @error('default_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="mb-3">
                            <label for="tat_hours_default" class="form-label">Default TAT Hours <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('tat_hours_default') is-invalid @enderror" 
                                   id="tat_hours_default" 
                                   name="tat_hours_default" 
                                   value="{{ old('tat_hours_default')??$test->tat_hours_default }}" 
                                   placeholder="Default TAT Hours"
                                   maxlength="255"
                                   required>
                            @error('tat_hours_default')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                       

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ (old('status')??$test->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status')??$test->status) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Test Master
                            </button>
                            <a href="{{ route('test.index') }}" class="btn btn-secondary">
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
    document.getElementById('test_name').addEventListener('input', function() {
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