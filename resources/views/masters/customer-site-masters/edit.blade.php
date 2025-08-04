@extends('/layouts/master-layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Site Master: {{ $siteMaster->site_code }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('site-masters.update', $siteMaster) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="site_code" class="form-label">Site Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('site_code') is-invalid @enderror" 
                                   id="site_code" 
                                   name="site_code" 
                                   value="{{ old('site_code', $siteMaster->site_code) }}" 
                                   placeholder="Enter unique site code"
                                   maxlength="50"
                                   required>
                            @error('site_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 50 characters. Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('site_name') is-invalid @enderror" 
                                   id="site_name" 
                                   name="site_name" 
                                   value="{{ old('site_name', $siteMaster->site_name) }}" 
                                   placeholder="Enter site name"
                                   maxlength="255"
                                   required>
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum 255 characters.</div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ old('status', $siteMaster->status) === 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $siteMaster->status) === 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Site Master
                            </button>
                            <a href="{{ route('site-masters.show', $siteMaster) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('site-masters.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Audit Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Audit Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Created Date</small>
                            <strong>@if(!empty($siteMaster->created_at)) {{ $siteMaster->created_at->format('M d, Y h:i') }} @endif
                        </div>    
                    </div>    
                 </div>    
            </div> 
        </div>    
    </div>                             
</div>   
@endsection                       