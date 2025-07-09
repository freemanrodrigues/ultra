@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Unit Master Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('unit-masters.update',$unitMaster->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="unit_code" class="form-label">Unit Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('unit_code') is-invalid @enderror" 
                                   id="unit_code" 
                                   name="unit_code" 
                                   value="{{ old('unit_code')??$unitMaster->unit_code }}" 
                                   placeholder="Enter unique unit code (e.g., unit001)"
                                   maxlength="50"
                                   required>
                            @error('unit_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="unit_name" class="form-label">Unit Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('unit_name') is-invalid @enderror" 
                                   id="unit_name" 
                                   name="unit_name" 
                                   value="{{ old('unit_name')??$unitMaster->unit_name }}" 
                                   placeholder="Enter unit name"
                                   maxlength="255"
                                   required>
                            @error('unit_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ (old('status')??$unitMaster->status )=== 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status')??$unitMaster->status ) === 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Unit Master
                            </button>
                            <a href="{{ route('unit-masters.index') }}" class="btn btn-secondary">
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
    // Auto-generate unit code based on unit name (optional)
    document.getElementById('unit_name').addEventListener('input', function() {
        const unitName = this.value;
        const unitCodeField = document.getElementById('unit_code');
        
        if (unitCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = unitName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            unitCodeField.value = code;
        }
    });
</script>
@endpush