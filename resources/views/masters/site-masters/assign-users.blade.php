@extends('/layouts/master-layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Site Master: {{ $siteMaster[0]->site_code }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('site-masters.save-assign-users') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="site_id" class="form-label">Site Master <span class="text-danger">*</span></label>
                            <select class="form-select @error('site_id') is-invalid @enderror" id="site_id" name="site_id" required>
                                <option value="">Select Site</option>
                                @foreach($siteMaster as $sm)
                                    <option value="{{$sm->id}}" >{{$sm->site_name}}</option>
                                @endforeach
                            </select>
                            @error('site_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select One.</div>
                        </div>

                        <div class="mb-3">
                            <label for="users" class="form-label">Contact  User <span class="text-danger">*</span></label>
                            <select multiple class="form-select @error('users') is-invalid @enderror" id="users" name="users[]" required>
                            <!--    <option value="">Select Status</option> -->
                                @foreach($users as $user)
                                    <option value={{ $user->id }}>{{ $user->firstname }} {{ $user->lastname }}</option>
                                @endforeach
                            </select>
                            @error('users')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can select multiple</div>
                        </div>

                        

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Site Master
                            </button>
                            <a href="{{ route('site-masters.show', $siteMaster[0]->id) }}" class="btn btn-outline-secondary">
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