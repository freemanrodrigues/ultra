@extends('/layouts/master-layout')
@section('content')
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
<link rel="stylesheet" href="/css/customer/autosuggest_pop.css?{{date('mmss')}}" />


    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Contact Master</h3>
        <a href="{{ route('contacts-masters.create')}}" class="btn btn-primary">Add Contact</a>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('contacts-masters.index') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Company</label>
                <input type="text" class="form-control search" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search Company Name..."  data-txt_id="company_id">
                <input type="hidden" id="company_id" name="company_id">
                <!-- select class="form-control" id="search" name="search">
                <option value="">List by Company</option>
                @foreach($companies as $company)
                <option value="{{$company->id}}">{{$company->company_name}}</option>
                @endforeach
                </select --> 
                      

            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

    <!-- Users Table -->
        <div class="card">
        <div class="card-body p-0">
            @if($users->count() > 0)
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Phone</th>
                <th>company</th>
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
                <td>{{  $user->company->company_name; }}</td>
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
@else
                <div class="text-center py-3">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Contact found</h5>
                    <p class="text-muted">Get started by adding your first contact.</p>
                    <a href="{{ route('contacts-masters.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Contact
                    </a>
                </div>
@endif


          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

<div id="searchModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div id="modal-search-results"></div>
    </div>
</div>      
<script src="/js/customer/function_autosuggest3.js?{{date('mmss')}}"></script>
@stop