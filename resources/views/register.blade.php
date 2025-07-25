@extends('/layouts/master-layout1')

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
                        <input type="text" name="lastname" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Address 1</label>
                        <input type="text" name="address1" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Address 2</label>
                        <input type="text" name="address2" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>City</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>State</label>
                        <input type="text" name="state" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Pincode</label>
                        <input type="text" name="pincode" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Country</label>
                        <select name="country_id" class="form-select">
                            <option value="">--Select--</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if($country->id == 71) selected  @endif>{{ $country->countryname }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Customer</label>
                        <select name="customer_id" class="form-select">
                            <option value="">--Select--</option>
                           <option value="0">Internal</option>
                            @foreach($customers as $company)
                                <option value="{{ $company->id }}"  >{{ $company->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{--
                    <div class="col-md-6">
                        <label>User Type</label>
                        <select name="user_type" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="client">Client</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>User Role</label>
                        <input type="text" name="user_role" class="form-control">
                    </div>
                    --}}
                    <div class="col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                            
                    </div>
                </div>
                <div class="justify-content-center mt-3  align-items-center">
                    <button class="btn btn-secondary mr-4 p-2" data-bs-dismiss="modal">Cancel</button>
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

@push('scripts')
<script>
$(document).ready(function () {
    // Prefill form for editing
    $('.editUserBtn').on('click', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const cells = row.find('td');
        $('#userForm')[0].reset();
        $('#user_id').val(id);
        $('input[name="email"]').val(cells.eq(0).text().trim());
        $('input[name="firstname"]').val(cells.eq(1).text().split(' ')[0]);
        $('input[name="lastname"]').val(cells.eq(1).text().split(' ')[1] || '');
        $('input[name="city"]').val(cells.eq(2).text().trim());
        $('input[name="phone"]').val(cells.eq(3).text().trim());
        $('select[name="user_type"]').val(cells.eq(4).text().toLowerCase());
        $('select[name="status"]').val(cells.eq(5).text().includes('Active') ? '1' : '0');

        $('#userModal').modal('show');
    });

    // Reset modal on open
    $('#createUserBtn').on('click', function () {
        $('#userForm')[0].reset();
        $('#user_id').val('');
    });
});
</script>
@endpush
