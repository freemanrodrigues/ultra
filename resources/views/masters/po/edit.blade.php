@extends('/layouts/master-layout')

@section('content')

<div class="container mt-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Create New PO</h5>
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
            <form action="{{ route('po.update',$po->id ) }}" method="POST" id="siteForm" >
            @csrf
            @method('PUT')
                <div class="row g-3">
              
                    <div class="col-md-6">
                        <label for="id_company" class="form-label">Party</label>
 <div class="myDropdownCover">
<input type="hidden" id="company_id" name="party_id" value="{{ $po->party_id }}"> 
<input type="text" class="form-control search"  name="search" id="id_company" data-txt_id="company_id"
value="{{ request('search')??$po->party_id }}" placeholder="Search by companyname..."  autocomplete="off">
<div id="myDropdown_company_id" class="myDropdown"></div>
</div>
                    </div>
                <div class="col-md-6">
                        <label for="site_id" class="form-label">Site</label>
                        <input type="text" name="site_id" id="site_id" class="form-control"   value="{{ old('site_id')??$po->site_id}}">
                    </div>
                    <div class="col-md-6">
                        <label for="po_number" class="form-label">Po Number</label>
                        <input type="text" name="po_number" id="po_number" class="form-control" required  value="{{ old('po_number')??$po->po_number}}">
                    </div>
                    <div class="col-md-6">
                        <label for="po_date" class="form-label">PO Date</label>
                        <input type="date" name="po_date" id="po_date" class="form-control" required  value="{{ old('po_date')??date('Y-m-d', strtotime($po->po_date))}}">
                    </div>

                  
                    <div class="col-md-6">
                        <label for="valid_from" class="form-label">Start Date</label>
                        <input type="date" name="valid_from" id="valid_from" class="form-control" required  value="{{ old('valid_from')??date('Y-m-d', strtotime($po->valid_from))}}">
                    </div>
                    <div class="col-md-6">
                        <label for="valid_to" class="form-label">End Date</label>
                        <input type="date" name="valid_to" id="valid_to" class="form-control" required  value="{{ old('valid_to')??date('Y-m-d', strtotime($po->valid_to))}}">
                    </div>

                     <div class="col-md-6">
                        <label for="currency" class="form-label">Currency</label>
                        <input type="text" name="currency" id="currency" class="form-control"  value="{{ old('currency')??$po->currency}}">
                    </div>
                    <div class="col-md-6">
                        <label for="sample_type_id" class="form-label">Sample Type</label>

                        
                        <select name="sample_type_id" class="form-select"  id="sample_type_id">
                        <option value="">Select</option>
                            @foreach($sample_types as $sample_type)
                            <option value="{{$sample_type->id}}" {{ (old('sample_type_id')??$po->sample_type_id) == $sample_type->id ? 'selected' : '' }}>{{$sample_type->sample_type_name }}</option> 
                            @endforeach
                            
                        </select>
                    </div>
                     <div class="col-md-6">
                        <label for="test_rate" class="form-label">Test Rate</label>
                        <input type="text" name="test_rate" id="test_rate" class="form-control"  value="{{ old('test_rate')??$po->test_rate}}">
                    </div>
                    <div class="col-md-6">
                        <label for="test_limit" class="form-label">Test Limit</label>
                        <input type="text" name="test_limit" id="test_limit" class="form-control" value="{{ old('test_limit')??$po->test_limit}}">
                    </div>

                    

                    <div class="col-md-12">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" id="status" @error('status') is-invalid @enderror>
                    <option value="">Select Status</option>
                                @foreach(config('constants.PO_STATUS') as $k => $val)
                            <option value="{{$k}}" {{ (old('status')??$po->status) == $k ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                        </select>
                      </div>
                </div>

               
                <div class="mt-4 text-end">
                    <a href="{{ route('po.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update PO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/js/customer/function_autosuggest33.js?{{date('mmss')}}"></script>
@endsection

