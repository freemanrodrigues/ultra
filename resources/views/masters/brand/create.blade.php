@extends('/layouts/master-layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle text-primary"></i> Create New Brand Master
                    </h4>
                    <a href="{{ route('brand.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('brand.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="brand_code" class="form-label">
                                    Brand Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('brand_code') is-invalid @enderror" 
                                       id="brand_code" 
                                       name="brand_code" 
                                       value="{{ old('brand_code') }}" 
                                       placeholder="e.g., ST001"
                                       maxlength="50"
                                       required>
                                @error('brand_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Unique identifier for the Brand (max 50 characters)</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Select Status</option>
                                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="brand_name" class="form-label">
                            Brand Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('brand_name') is-invalid @enderror" 
                               id="brand_name" 
                               name="brand_name" 
                               value="{{ old('brand_name') }}" 
                               placeholder="Enter brand name"
                               maxlength="255"
                               required>
                        @error('brand_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Full name of the brand (max 255 characters)</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Brand Master
                        </button>
                        <a href="{{ route('brand.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection