@extends('/layouts/master-layout')
@section('content')

<style>
    /* Consistent styling with customer list page */
    .po-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
    }
    
    .po-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    /* Distinct header background */
    .page-header {
        background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Sample and test styling */
    .sample-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .test-item {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .test-item-header {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.25rem;
    }
    
    /* Action buttons */
    .btn-xs {
        padding: 0.1rem 0.2rem;
        font-size: 10px;
        line-height: 1;
        min-width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-xs i {
        font-size: 12px;
    }
</style>

<div class="container-fluid po-page">
    <!-- Page Header with distinct background -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-file-invoice me-2"></i> Purchase Order Details
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('po.edit', $po) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit PO
                </a>
                <a href="{{ route('po.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- PO Basic Information -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>PO Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">PO Number:</label>
                    <p class="mb-0">{{ $po->po_number }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">PO Date:</label>
                    <p class="mb-0">{{ $po->po_date ? $po->po_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Customer:</label>
                    <p class="mb-0">
                        @if($po->customer)
                            {{ $po->customer->customer_name }}
                            @if($po->customer->division)
                                - {{ $po->customer->division }}
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Site:</label>
                    <p class="mb-0">
                        @if($po->site)
                            {{ $po->site->site_name }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Start Date:</label>
                    <p class="mb-0">{{ $po->po_start_date ? $po->po_start_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">End Date:</label>
                    <p class="mb-0">{{ $po->po_end_date ? $po->po_end_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Status:</label>
                    <p class="mb-0">
                        @if($po->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($po->status) }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Total Amount:</label>
                    <p class="mb-0 h5 text-primary">
                        ₹{{ number_format($po->total_amount, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Samples and Tests -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Samples & Tests</h5>
        </div>
        <div class="card-body">
            @if($po->samples->count() > 0)
                @foreach($po->samples as $sample)
                    <div class="sample-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">
                                <i class="fas fa-flask me-2"></i>
                                Sample: {{ $sample->sampleType->sample_type_name ?? 'N/A' }}
                            </h6>
                        </div>
                        
                        @if($sample->description)
                            <p class="text-muted mb-2">{{ $sample->description }}</p>
                        @endif
                        
                        @if($sample->tests->count() > 0)
                            <div class="row g-2">
                                @foreach($sample->tests as $test)
                                    <div class="col-md-12">
                                        <div class="test-item">
                                            <div class="test-item-header">
                                                {{ $test->test->test_name ?? 'N/A' }}
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-3">
                                                    <small class="text-muted">Price:</small>
                                                    <p class="mb-0">₹{{ number_format($test->price, 2) }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Quantity:</small>
                                                    <p class="mb-0">{{ $test->quantity }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Total:</small>
                                                    <p class="mb-0 fw-bold">₹{{ number_format($test->total, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No tests assigned to this sample.</p>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No samples found</h5>
                    <p class="text-muted mb-3">This PO doesn't have any samples yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
                    
