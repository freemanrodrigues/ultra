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
 
        <form id="userForm" method="POST" action="{{ route('contacts-masters.update',$contacts_master->id) }}">
            @csrf
            @method('PUT')
            <div class="card m-3 p-4 rounded-4 shadow">
                <div class="modal-header m-1 p-1 bg-primary text-white">
                    <h5 class="modal-title">User Form</h5>
                    
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="user_id" value="{{$contacts_master->id}}">

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{$contacts_master->email}}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control"  value="{{$contacts_master->phone}}" >
                    </div>
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control"   value="{{$contacts_master->firstname}}"  required>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control"  value="{{$contacts_master->lastname}}" >
                    </div>
                    
                    
                    <div class="col-md-6">
                        <label>Company</label>
                        <select name="company_id" class="form-select">
                            
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @if($company->id == $contacts_master->company_id ) selected @endif>{{ $company->company_name }}</option>
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


