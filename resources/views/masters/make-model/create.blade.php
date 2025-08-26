@extends('/layouts/master-layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Make Model</h5>
        </div>
     @if ($errors->any())
        <div class="bg-danger border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="card-body">
            <form action="{{ route('make-model-masters.store') }}" method="POST" id="siteForm" >
                @csrf
                <div class="row g-3">
              
                    <div class="col-md-6">
                        <label for="make" class="form-label">Make</label>
    <input type="hidden" id="make_id" name="make_id"> 
<input type="text" name="make" id="make" class="form-control search" data-txt_id="make_id" required  autocomplete="off">
<div id="myDropdown_make_id" class="myDropdown"></div>                </div>

                    <div class="col-md-6">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" name="model" id="model" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" id="description" >
                    </div>

                    <div class="col-md-12">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" id="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

               
                <div class="mt-4 text-center">
                    <a href="{{ route('make-model-masters.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Create Make Model</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/js/customer/function_autosuggest33.js?{{date('mmss')}}"></script>

@endsection

