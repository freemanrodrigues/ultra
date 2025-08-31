@extends('/layouts/master-layout')

@section('content')
<style>
    .card-title {
        font-weight: 600;
        color: #3b82f6;
        margin-bottom: 20px;
    }
    .data-label {
        font-weight: 500;
        color: #555;
    }
    .data-value {
        color: #212529;
    }
    .site-details-row > .col-md-4, .site-details-row > .col-md-12 {
        margin-bottom: 15px;
    }
    .status-badge {
        font-size: 1rem;
        padding: 0.5em 1em;
    }
    .customer-card {
        border-left: 4px solid #3b82f6;
        transition: all 0.3s ease;
    }
    .customer-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
        margin-bottom: 0;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .info-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }
</style>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('site-masters.index') }}">Site Masters</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $siteMaster->site_name }}</li>
        </ol>
    </nav>

    <!-- Site Details Section -->
    <div class="card shadow mb-4">
        <div class="section-header">
            <h4 class="mb-0">
                <i class="fas fa-building me-2"></i>
                Site Details
            </h4>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="data-label">Site Name:</div>
                    <div class="data-value fw-bold fs-5">{{ $siteMaster->site_name }}</div>
                </div>
                
                <div class="info-item">
                    <div class="data-label">Status:</div>
                    <div class="data-value">
                        @if($siteMaster->status == 1)
                            <span class="badge bg-success status-badge">Active</span>
                        @else
                            <span class="badge bg-secondary status-badge">Inactive</span>
                        @endif
                    </div>
                </div>
                
                @if($siteMaster->address)
                <div class="info-item">
                    <div class="data-label">Address:</div>
                    <div class="data-value">{{ $siteMaster->address }}</div>
                </div>
                @endif
                
                @if($siteMaster->city)
                <div class="info-item">
                    <div class="data-label">City:</div>
                    <div class="data-value">{{ $siteMaster->city }}</div>
                </div>
                @endif
                
                @if($siteMaster->state_table)
                <div class="info-item">
                    <div class="data-label">State:</div>
                    <div class="data-value">{{ $siteMaster->state_table->statename }}</div>
                </div>
                @endif
                
                @if($siteMaster->country)
                <div class="info-item">
                    <div class="data-label">Country:</div>
                    <div class="data-value">{{ $siteMaster->country }}</div>
                </div>
                @endif
                
                @if($siteMaster->lat && $siteMaster->long)
                <div class="info-item">
                    <div class="data-label">Coordinates:</div>
                    <div class="data-value">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        {{ $siteMaster->lat }}, {{ $siteMaster->long }}
                    </div>
                </div>
                @endif
                
                <div class="info-item">
                    <div class="data-label">Created:</div>
                    <div class="data-value">
                        <i class="fas fa-calendar-alt text-info me-1"></i>
                        {{ $siteMaster->created_at->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="data-label">Last Updated:</div>
                    <div class="data-value">
                        <i class="fas fa-clock text-warning me-1"></i>
                        {{ $siteMaster->updated_at->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Linked to This Site Section -->
    <div class="card shadow mb-4">
        <div class="section-header">
            <h4 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Customers Linked to This Site
                <span class="badge bg-light text-dark ms-2">{{ $customers->count() }}</span>
            </h4>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="row">
                    @foreach($customers as $customerSite)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card customer-card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-user me-2"></i>
                                        {{ $customerSite->customer->customer_name ?? 'N/A' }}
                                    </h6>
                                    
                                    @if($customerSite->site_customer_name)
                                        <p class="card-text mb-2">
                                            <strong>Site Code:</strong> 
                                            <span class="badge bg-info">{{ $customerSite->site_customer_name }}</span>
                                        </p>
                                    @endif
                                    
                                    @if($customerSite->site_customer_code)
                                        <p class="card-text mb-2">
                                            <strong>Customer Site Code:</strong> 
                                            <span class="badge bg-secondary">{{ $customerSite->site_customer_code }}</span>
                                        </p>
                                    @endif
                                    
                                    @if($customerSite->address)
                                        <p class="card-text mb-2">
                                            <strong>Address:</strong><br>
                                            <small class="text-muted">{{ $customerSite->address }}</small>
                                        </p>
                                    @endif
                                    
                                    @if($customerSite->city)
                                        <p class="card-text mb-2">
                                            <strong>City:</strong> {{ $customerSite->city }}
                                        </p>
                                    @endif
                                    
                                    @if($customerSite->state)
                                        <p class="card-text mb-2">
                                            <strong>State:</strong> {{ $customerSite->state }}
                                        </p>
                                    @endif
                                    
                                    @if($customerSite->pincode)
                                        <p class="card-text mb-2">
                                            <strong>Pincode:</strong> {{ $customerSite->pincode }}
                                        </p>
                                    @endif
                                    
                                    <div class="mt-auto">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            Linked on {{ $customerSite->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No customers linked to this site</h5>
                    <p class="text-muted">This site is not currently assigned to any customers.</p>
                    <a href="{{ route('customer-site-masters.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Assign Customer to Site
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('site-masters.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Site Masters
                </a>
                
                <div class="btn-group">
                    <a href="{{ route('site-masters.edit', $siteMaster) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Site
                    </a>
                    
                    @if($customers->count() > 0)
                        <a href="{{ route('customer-site-masters.create', ['site_master_id' => $siteMaster->id]) }}" 
                           class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Add Another Customer
                        </a>
                    @else
                        <a href="{{ route('customer-site-masters.create', ['site_master_id' => $siteMaster->id]) }}" 
                           class="btn btn-success">
                            <i class="fas fa-link me-2"></i>Link Customer
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
