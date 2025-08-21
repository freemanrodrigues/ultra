@extends('/layouts/master-layout')



@section('content')
<div class="container mt-4">

 <!-- Search and Filter Form --><div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> Customer Site Master
        <small class="text-muted">({{ $customerSiteMasters->total() }} total)</small>
    </h1>
    <a href="{{ route('customer-site-masters.create', ['customer_id' => $_GET['customer_id'] ?? null]) }}" 
   class="btn btn-primary">
    <i class="fas fa-plus"></i> Add New Customer Site
</a>
</div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success')['text'] }}<br>
        <a href="{{ session('success')['link'] }}" class="alert-link">
            {{ session('success')['link_text'] }}
        </a>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
 <!-- Search and Filter Form -->
    <div class="search-form h-auto m-2 ">
        <form method="GET" action="{{ route('customer-site-masters.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search by code or name...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort_by" class="form-label">Sort By</label>
                <select class="form-select" id="sort_by" name="sort_by">
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                    <option value="site_code" {{ request('sort_by') === 'site_code' ? 'selected' : '' }}>Site Code</option>
                    <option value="site_name" {{ request('sort_by') === 'site_name' ? 'selected' : '' }}>Site Name</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort_order" class="form-label">Order</label>
                <select class="form-select" id="sort_order" name="sort_order">
                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Descending</option>
                    <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                    <a href="{{ route('customer-site-masters.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <small class="text-muted">
                Showing {{ $customerSiteMasters->firstItem() ?? 0 }} to {{ $customerSiteMasters->lastItem() ?? 0 }} 
                of {{ $customerSiteMasters->total() }} results
            </small>
        </div>
    </div>
    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($customerSiteMasters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Site Name</th>
                                <th>Site Code</th>
                                <th>Customer Site Name</th>
                               
                                <th>Customer</th>
                                <th>Contact</th>
                                
                                <th>Created Date</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customerSiteMasters as $customerSiteMaster)
                      
                                <tr>
                                    <td>{{ $customerSiteMaster->siteMaster->site_name }}</td>
                                    <td>
                                        <strong>{{$customerSiteMaster->site_customer_code }}</strong>
                                    </td>
                                    <td>{{ $customerSiteMaster->site_customer_name }}</td>
                                    <td>@if(!empty($customers [$customerSiteMaster->customer_id])){{ $customers[$customerSiteMaster->customer_id] }} @endif</td>

                                    <td>
                              
                                    <!-- a class="btn btn-sm btn-outline-secondary assigned_contact m-1"  href="{{ route('assign-contact-assignments', [ 'site_id' =>$customerSiteMaster->id , 'customer_id' => $customerSiteMaster->customer_id ]) }}" class= "m-1">
                                       <i class="bi bi-person-plus"></i> </a -->
<a class="btn btn-sm btn-outline-secondary m3 m-1" data-id="{{ $customerSiteMaster->id }}" data-customer_id="{{ $customerSiteMaster->customer_id }}"  data-company_id="{{ $customerSiteMaster->company_id }}"  data-bs-toggle="modal" data-bs-target="#m3" >
                                    <i class="bi bi-person-plus"></i></a>

                                       <a class="btn btn-sm btn-outline-secondary assigned_contact m-1" data-id="{{ $customerSiteMaster->site_master_id }}" data-bs-toggle="modal" data-bs-target="#assigned_contact_Modal" ><i class="bi bi-eye"></i></a>
                                     </td>
                                   
                                    </td>
                                    <td>

                                        <small class="text-muted">
                                        @if(!empty($customerSiteMaster->created_at))
                                            {{ $customerSiteMaster->created_at->format('M d, Y') }}
                                        @endif    
                                        </small>
                                    </td>
                                    <td class="table-actions">
                                        <div class="btn-group" role="group">
                                            <!-- a href="{{ route('customer-site-masters.show', $customerSiteMaster) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a -->
                                            <a href="{{ route('customer-site-masters.edit', $customerSiteMaster) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                 
                                      
                                            <!-- Toggle Status -->
                                            <!-- form method="POST" action="{{ route('site-masters.toggle-status', $customerSiteMaster) }}" 
                                                  style="display: inline;" id="toggle-form-{{ $customerSiteMaster->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-{{ $customerSiteMaster->status === 'active' ? 'warning' : 'success' }}"
                                                        title="{{ $customerSiteMaster->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                                        onclick="confirmToggle(document.getElementById('toggle-form-{{ $customerSiteMaster->id }}'), '{{ $customerSiteMaster->status }}')">
                                                    <i class="bi bi-{{ $customerSiteMaster->status === 'active' ? 'pause-circle' : 'play-circle' }}"></i>
                                                </button>
                                            </form -->
                                            
                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('customer-site-masters.destroy', $customerSiteMaster) }}" 
                                                  style="display: inline;" id="delete-form-{{ $customerSiteMaster->id }}"  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <buttomyModalasn type="button" class="btn btn-sm btn-outline-danger" 
                                                        title="Delete"
                                                        onclick="confirmDelete(document.getElementById('delete-form-{{ $customerSiteMaster->id }}'))">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No site masters found</h5>
                    <p class="text-muted">Get started by adding your first site master.</p>
                    <a href="{{ route('customer-site-masters.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Customer Site
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if($customerSiteMasters->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $customerSiteMasters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
<!-- The Modal -->
        <div class="modal fade" id="assign_contact_Modal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog  modal-lg">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h5 class="modal-title ">Assigned Contacts for <span id=''></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <div class="container my-4" id="assign_contact_div">
                </div>
            </div>

        </div>
    </div>
</div>

<!-- The Modal -->
    <div class="modal fade" id="assigned_contact_Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h5 class="modal-title ">Assigned Contacts for <span id=''></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="container my-4" id="assigned_contact_div">
                </div>

            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
    <div class="modal fade" id="m3" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h5 class="modal-title ">Assigned Contacts for </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="assignContact">
                <div class="container my-4" id="m3_div">
                </div>
            </div>
             <div id="formErrors" class="text-danger"></div>
             <div class="modal-footer" >
              <input type="hidden" name="site_id"  id="site_id">
              <input type="hidden" name="customer_id" id="customer_id">
          <button type="button" class="btn btn-success" id="assign_submit">Submit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
        </form>
        </div>
    </div>
</div>
<script src="/js/customer/assign-contact.js?{{date('mmss')}}"></script>    
<script>
  const URL = "{{ route('contact-assignments.store') }}";
</script>
@endsection