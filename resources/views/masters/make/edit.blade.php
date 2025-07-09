@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Make Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('make.store',$make->id) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="make_code" class="form-label">make Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('make_code') is-invalid @enderror" 
                                   id="make_code" 
                                   name="make_code" 
                                   value="{{ old('make_code')??$sampleNature->make_code }}" 
                                   placeholder="Enter unique make code (e.g., make001)"
                                   maxlength="50"
                                   required>
                            @error('make_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="make_name" class="form-label">Make Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('make_name') is-invalid @enderror" 
                                   id="make_name" 
                                   name="make_name" 
                                   value="{{ old('make_name')??$sampleNature->make_name }}" 
                                   placeholder="Enter Make name"
                                   maxlength="255"
                                   required>
                            @error('make_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ (old('status')??$sampleNature->status) }} === 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status')??$sampleNature->status) === 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Make Master
                            </button>
                            <a href="{{ route('make.index') }}" class="btn btn-secondary">
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
    // Auto-generate make code based on make name (optional)
    document.getElementById('make_name').addEventListener('input', function() {
        const makeName = this.value;
        const makeCodeField = document.getElementById('make_code');
        
        if (makeCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = makeName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            makeCodeField.value = code;
        }
    });
</script>
@endpush