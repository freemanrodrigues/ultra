@extends('/layouts/master-layout')
@section('content')
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Contact Master</h3>
        <a href="{{ route('contacts-masters.create')}}" class="btn btn-primary">Add Contact</a>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Users Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Phone</th>
                <th>User Type</th>
                <th>Status</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr data-id="{{ $user->id }}">
                <td>{{ $user->email }}</td>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ ucfirst($user->user_type) }}</td>
                <td><span class="badge bg-{{ $user->status ? 'success' : 'secondary' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <!-- button class="btn btn-sm btn-warning editUserBtn">Edit</button -->
   
                     <a href="{{ route('contacts-masters.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                    <form method="POST" action="{{ route('contacts-masters.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Del</button>
                    </form>
                   
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>



          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

@stop