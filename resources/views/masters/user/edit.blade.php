@extends('/layouts/master-layout')

@section('content')

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->


 
    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<!-- Create/Edit Modal -->
 
        <form id="userForm" method="POST" action="{{ route('users.update',$users->id) }}">
            @csrf
            @method('PUT')
            <div class="card m-3 p-4 rounded-4 shadow">
                <div class="modal-header m-1 p-1 bg-primary text-white">
                    <h5 class="modal-title">User Form</h5>
                    
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="user_id" value="{{$users->id}}">

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{$users->email}}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control"  value="{{$users->phone}}" >
                    </div>
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control"   value="{{$users->firstname}}"  required>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control"  value="{{$users->lastname}}" >
                    </div>
                    <div class="col-md-6">
                        <label>Address 1</label>
                        <input type="text" name="address1" class="form-control"  value="{{$users->address1}}">
                    </div>
                    <div class="col-md-6">
                        <label>Address 2</label>
                        <input type="text" name="address2" class="form-control"  value="{{$users->address2}}">
                    </div>
                    <div class="col-md-4">
                        <label>City</label>
                        <input type="text" name="city" class="form-control"  value="{{$users->city}}">
                    </div>
                    <div class="col-md-4">
                        <label>State</label>
                        <input type="text" name="state" class="form-control"  value="{{$users->state}}">
                    </div>
                    <div class="col-md-4">
                        <label>Pincode</label>
                        <input type="text" name="pincode" class="form-control"  value="{{$users->pincode}}">
                    </div>
                    <div class="col-md-6">
                        <label>Country</label>
                        <select name="country_id" class="form-select">
                            <option value="">--Select--</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if($users->country_id ==$country->id ) selected @endif>{{ $country->countryname }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Company</label>
                        <select name="company_id" class="form-select">
                            <option value="">--Select--</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"  @if($users->company_id ==$company->id ) selected @endif>{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>User Type</label>
                        <select name="user_type" class="form-select">
                         @foreach(config('constants.USER_TYPE') as $k => $val)
                        <option value="{{$k}}"  @if($users->user_type ==$k ) selected @endif>{{$val}}</option>
                        @endforeach 
                           
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>User Role</label>
                        
                        <select name="user_role" class="form-select">
                         @foreach(config('constants.USER_ROLE') as $k => $val)
                        <option value="{{$k}}"  @if($users->user_role ==$k ) selected @endif>{{$val}}</option>
                        @endforeach 
                           
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="justify-content-center align-items-center">
                    <button class="btn btn-secondary mr-4" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="submit">Save</button>
                </div>
            </div>
        </form>
  
            <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
@endsection


