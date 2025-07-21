@extends('/layouts/master-layout')
@section('content')
<div class="container m-5">
    <h2>Add New Site Machine Detail</h2>
    <hr>
@if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
    <form action="{{ route('site-master-device.store') }}" method="POST">
        @csrf

<div class="mb-3">
            <label for="site_master_id" class="form-label">Site Master ID:</label>
            <!-- input type="number" class="form-control @error('site_master_id') is-invalid @enderror" id="site_master_id" name="site_master_id" value="{{ old('site_master_id') }}" required -->
            <select class="form-control @error('site_master_id') is-invalid @enderror" id="site_master_id" name="site_master_id"  required>
            @foreach($sitemasters as $sm)
                <option value="{{$sm->id}}">{{$sm->site_name}}</option>
            @endforeach
            </select>
            @error('site_master_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="model_id" class="form-label">Model ID:</label>

<select class="form-control @error('model_id') is-invalid @enderror" id="model_id" name="model_id"  required>
            @foreach($sitedevices as $sd)
                <option value="{{$sd->id}}">{{$sd->make_name}}</option>
            @endforeach
            </select>
            
            @error('model_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        

        <div class="mb-3">
            <label for="machine_number" class="form-label">Machine Number:</label>
            <input type="text" class="form-control @error('machine_number') is-invalid @enderror" id="machine_number" name="machine_number" value="{{ old('machine_number') }}" required>
            @error('machine_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="machine_code" class="form-label">Machine Code:</label>
            <input type="text" class="form-control @error('machine_code') is-invalid @enderror" id="machine_code" name="machine_code" value="{{ old('machine_code') }}" required>
            @error('machine_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Add Machine Detail</button>
        <a href="{{ route('site-master-device.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection