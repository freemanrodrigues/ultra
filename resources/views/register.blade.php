@extends('/layouts/master-layout1')

@section('content')

 
    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


 @if ($errors->any())
        <div class="alert alert-danger border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
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
 
        <form id="userForm" method="POST" action="{{ route('register-user') }}">
            @csrf
            <div class="card m-3 p-4 rounded-4 shadow">
                <div class="modal-header m-1 p-1 bg-primary text-white">
                    <h5 class="modal-title">User Form</h5>
                    
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="user_id">

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone"  maxlength="10" required>

                    </div>
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                    
                    
                    
                  
                    <div class="col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                            
                    </div>
                </div>
                <div class="justify-content-center mt-3  align-items-center">
                    <button class="btn btn-secondary mr-4 p-2" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="submit">Save</button>
                </div>
            </div>
        </form>
  

@endsection


