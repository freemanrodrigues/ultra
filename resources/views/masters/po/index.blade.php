@extends('/layouts/master-layout')
@section('content')
<link rel="stylesheet" href="/css/customer/autosuggest_pop.css?{{date('mmss')}}" />

<div class="container mt-4">
 <!-- Search and Filter Form -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-building text-primary"></i> PO Master
        <small class="text-muted">({{ $po_datas->total() }} total)</small>
    </h1>
    <a href="{{ route('po.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New PO
    </a>
  </div>

@if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
@endif

<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('po.index') }}">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="id_company" class="form-label">Search</label>
                <input type="text" class="form-control search" id="id_company" name="search" 
                       value="{{ request('search') }}" placeholder="Search by customer name..." data-txt_id="company_site_id" autocomplete="off">
                    <input type="hidden" id="company_id" name="company_id">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                        @foreach(config('constants.PO_STATUS') as $k => $val)
                            <option value="{{$k}}" {{ request('status') == $k ? 'selected' : '' }}>{{$val}}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('po.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>



<!-- Data Table -->
<div class="card shadow mb-4">
    <div class="card-body p-0">
        @if($po_datas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
       
                            <th>ID</th>
                            <th>Party Name</th>
                            <th>PO Number</th>
                            <th>Date</th>
                            <th>Valid From</th>
                            <th>Valid To</th>
                         <th>Status</th>
                         
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_customer_index">
                        @foreach($po_datas as $po_data)
                        <tr>
                               <td><strong>{{ $po_data->id }}</strong></td>
                            <td>    {{$po_data->party_id}}</a>
                            </td>
                            <td>{{$po_data->po_number}}</td>
                             <td>{{date('d-m-Y', strtotime($po_data->po_date))}}</td>
                             <td>{{date('d-m-Y', strtotime($po_data->valid_from))}}</td>
                             <td>{{date('d-m-Y', strtotime($po_data->valid_to))}}</td>
                            <td>{{ config('constants.PO_STATUS.' . $po_data->status) ?? 'N/A' }}</td>


                            <td>
                                <div class="btn-group" role="group">
                              
                                    <a href="{{ route('po.show', $po_data->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('po.edit', $po_data->id) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
           
            @if($po_datas->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $po_datas->firstItem() }} to {{ $po_datas->lastItem() }} 
                            of {{ $po_datas->total() }} results
                        </div>
                        {{ $po_datas->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif 
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Site Masters Found</h4>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'status']))
                        No sites match your search criteria.
                    @else
                        Start by creating your first site master.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('po.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                @endif
                <a href="{{ route('po.create') }}" class="btn btn-primary new-customer">
                    <i class="fas fa-plus"></i> Add New Customer
                </a>
            </div>
        @endif
    </div>
</div>
<div id="searchModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div id="modal-search-results">
        </div>
    </div>
</div>
<script src="/js/customer/tbody_customer_index.js?{{date('mmss')}}"></script>
@endsection
