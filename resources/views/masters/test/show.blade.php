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
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
        margin-bottom: 0;
    }
</style>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('test.index') }}">Test Masters</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $testMaster->test_name }}</li>
        </ol>
    </nav>

    <!-- Test Details Section -->
    <div class="card shadow mb-4">
        <div class="section-header">
            <h4 class="mb-0">
                <i class="fas fa-flask me-2"></i>
                Test Details
            </h4>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="data-label">Test Name:</div>
                    <div class="data-value fw-bold fs-5">{{ $testMaster->test_name }}</div>
                </div>
                
                <div class="info-item">
                    <div class="data-label">Status:</div>
                    <div class="data-value">
                        {!! $testMaster->status_badge !!}
                    </div>
                </div>
                
                @if($testMaster->default_unit)
                <div class="info-item">
                    <div class="data-label">Default Unit:</div>
                    <div class="data-value">
                        <span class="badge bg-info fs-6">{{ $testMaster->default_unit }}</span>
                    </div>
                </div>
                @endif
                
                @if($testMaster->sampleType)
                <div class="info-item">
                    <div class="data-label">Sample Type:</div>
                    <div class="data-value">
                        <span class="badge bg-secondary fs-6">{{ $testMaster->sampleType->sample_type_name }}</span>
                    </div>
                </div>
                @endif
                
                @if($testMaster->tat_hours_default)
                <div class="info-item">
                    <div class="data-label">Default TAT:</div>
                    <div class="data-value">
                        <span class="badge bg-warning fs-6">{{ $testMaster->tat_hours_default }} hours</span>
                    </div>
                </div>
                @endif
                
                <div class="info-item">
                    <div class="data-label">Created:</div>
                    <div class="data-value">
                        <i class="fas fa-calendar-alt text-info me-1"></i>
                        {{ $testMaster->created_at->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="data-label">Last Updated:</div>
                    <div class="data-value">
                        <i class="fas fa-clock text-warning me-1"></i>
                        {{ $testMaster->updated_at->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('test.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Test Masters
                </a>
                
                <div class="btn-group">
                    <a href="{{ route('test.edit', $testMaster) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Test
                    </a>
                    
                    <form method="POST" action="{{ route('test.destroy', $testMaster) }}" 
                          style="display: inline;" 
                          onsubmit="return confirm('Are you sure you want to deactivate this test?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Deactivate
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

