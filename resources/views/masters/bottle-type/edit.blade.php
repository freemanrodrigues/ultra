@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Type Of Bottle Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('bottle-type.update',$bottleType) }}">
                    
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="bottle_code" class="form-label">Bottle Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('bottle_code') is-invalid @enderror" 
                                   id="bottle_code" 
                                   name="bottle_code" 
                                   value="{{ old('bottle_code')??$bottleType->bottle_code }}" 
                                   placeholder="Enter Unique Bottle code (e.g., bottle001)"
                                   maxlength="50"
                                   required>
                            @error('bottle_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be Unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="bottle_name" class="form-label">Bottle Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('bottle_name') is-invalid @enderror" 
                                   id="bottle_name" 
                                   name="bottle_name" 
                                   value="{{ old('bottle_name')??$bottleType->bottle_name }}" 
                                   placeholder="Enter Bottle name"
                                   maxlength="255"
                                   required>
                            @error('bottle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="remark" class="form-label">Remark <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('remark') is-invalid @enderror" 
                                   id="remark" 
                                   name="remark" 
                                   value="{{ old('remark')??$bottleType->remark }}" 
                                   placeholder="Enter Remark"
                                   maxlength="255"
                                   required>
                            @error('remark')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ (old('status')??$bottleType->status) === 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status')??$bottleType->status) === 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Bottle Master 
                            </button>
                            <a href="{{ route('bottle-type.index') }}" class="btn btn-secondary">
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
    // Auto-generate bottle code based on bottle name (optional)
    document.getElementById('bottle_name').addEventListener('input', function() {
        const bottleName = this.value;
        const bottleCodeField = document.getElementById('bottle_code');
        
        if (bottleCodeField.value === '') {
            // Generate code from first 3 letters + random number
            const code = bottleName.substring(0, 3).toUpperCase() + 
                         Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            bottleCodeField.value = code;
        }
    });
</script>
@endpush