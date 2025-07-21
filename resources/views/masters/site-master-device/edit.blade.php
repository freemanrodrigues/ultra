@extends('/layouts/master-layout')
@section('content')
<div class="container">
    <h2>Edit Site Machine Detail</h2>
    <hr>

    <form action="{{ route('site-master-device.update', $siteMachineDetail->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Use PUT method for update operations --}}

        <div class="mb-3">
            <label for="model_id" class="form-label">Model ID:</label>
            <input type="number" class="form-control @error('model_id') is-invalid @enderror" id="model_id" name="model_id" value="{{ old('model_id', $siteMachineDetail->model_id) }}" required>
            @error('model_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="site_master_id" class="form-label">Site Master ID:</label>
            <input type="number" class="form-control @error('site_master_id') is-invalid @enderror" id="site_master_id" name="site_master_id" value="{{ old('site_master_id', $siteMachineDetail->site_master_id) }}" required>
            @error('site_master_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="machine_number" class="form-label">Machine Number:</label>
            <input type="text" class="form-control @error('machine_number') is-invalid @enderror" id="machine_number" name="machine_number" value="{{ old('machine_number', $siteMachineDetail->machine_number) }}" required>
            @error('machine_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="machine_code" class="form-label">Machine Code:</label>
            <input type="text" class="form-control @error('machine_code') is-invalid @enderror" id="machine_code" name="machine_code" value="{{ old('machine_code', $siteMachineDetail->machine_code) }}" required>
            @error('machine_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Machine Detail</button>
        <a href="{{ route('site-master-device.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection