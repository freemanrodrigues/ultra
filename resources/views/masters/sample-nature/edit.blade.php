@extends('/layouts/master-layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Nature Of Sample Master</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sample-nature.update', $sampleNature) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="sample_nature_code" class="form-label">Nature Of Sample Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('sample_nature_code') is-invalid @enderror" 
                                   id="sample_nature_code" 
                                   name="sample_nature_code" 
                                   value="{{ old('sample_nature_code', $sampleNature->sample_nature_code) }}" 
                                   placeholder="Enter unique Nature Of Sample code (e.g., sample-nature001)"
                                   maxlength="50"
                                   required>
                            @error('sample_nature_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="sample_nature_name" class="form-label">Nature Of Sample Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('sample_nature_name') is-invalid @enderror" 
                                   id="sample_nature_name" 
                                   name="sample_nature_name" 
                                   value="{{ old('sample_nature_name', $sampleNature->sample_nature_name) }}" 
                                   placeholder="Enter Nature Of Sample name"
                                   maxlength="255"
                                   required>
                            @error('sample_nature_name')
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
                                   value="{{ old('remark', $sampleNature->remark) }}" 
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
                                <option value="1" {{ old('status', $sampleNature->status) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $sampleNature->status) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Nature Of Sample Master
                            </button>
                            <a href="{{ route('sample-nature.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection