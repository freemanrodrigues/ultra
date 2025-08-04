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

<!-- Create/Edit Modal -->
 
        <form id="userForm" method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="card m-3 p-4 rounded-4 shadow">
                <div class="modal-header m-1 p-1 bg-primary text-white">
                    <h5 class="modal-title">User Form</h5>
                    
                </div>
                <div class="modal-body row g-3">
                   
                    <div class="col-md-6">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone">Phone</label>
                        <input type="tel" name="phone" class="form-control" id="phone" pattern="[6-9][0-9]{9}"  minlength="10" maxlength="10"  required>
                    </div>
                    <div class="col-md-6">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname"  id="firstname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control">
                    </div>
                    
                    
                    <div class="col-md-6">
                        <label for="customer_id">Customer</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">--Select--</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>User Type</label>
                        <select name="user_type" class="form-select" required>
                        <option value="">--Select--</option>
                        @foreach(config('constants.USER_TYPE') as $k => $val)
                            <option value="{{$k}}">{{$val}}</option>
                        @endforeach 
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>User Role</label>
                        <select name="user_role" class="form-select" required>
                        <option value="">--Select--</option>
                        @foreach(config('constants.USER_ROLE') as $k => $val)
                            <option value="{{$k}}">{{$val}}</option>
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

