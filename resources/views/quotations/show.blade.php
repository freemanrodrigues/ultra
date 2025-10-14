@extends('/layouts/master-layout')
@section('content')

<style>
    .quotation-page { font-size: 12px; }
    .page-header {
        background: linear-gradient(135deg, #667eef 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .info-label { font-weight: 600; color: #666; }
    .info-value { color: #333; }
    .status-badge { font-size: 14px; padding: 0.5rem 1rem; }
</style>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid quotation-page">
            
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1><i class="fas fa-file-invoice me-2"></i> Quotation Details</h1>
                    <div>
                        <a href="{{ route('quotation.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                        @if($quotation->status == 1)
                            <a href="{{ route('quotation.edit', $quotation->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('quotation.pdf', $quotation->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-pdf me-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Status & Actions -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4>
                                Status: 
                                @php
                                    $statusColors = [1 => 'secondary', 2 => 'warning', 3 => 'success', 4 => 'info', 5 => 'dark', 6 => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$quotation->status] ?? 'secondary' }} status-badge">
                                    {{ $quotation->status_name }}
                                </span>
                            </h4>
                            @if($quotation->is_expired)
                                <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle"></i> This quotation has expired</p>
                            @elseif($quotation->days_until_expiry <= 7 && $quotation->days_until_expiry >= 0)
                                <p class="text-warning mb-0"><i class="fas fa-clock"></i> Expires in {{ $quotation->days_until_expiry }} days</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            @if($quotation->status == 1)
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#submitApprovalModal">
                                    <i class="fas fa-paper-plane me-1"></i> Submit for Approval
                                </button>
                            @elseif($quotation->status == 2)
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-1"></i> Reject
                                </button>
                            @elseif($quotation->status == 3)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#revisionModal">
                                    <i class="fas fa-copy me-1"></i> Create Revision
                                </button>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#statusModal">
                                    <i class="fas fa-exchange-alt me-1"></i> Change Status
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <span class="info-label">Quotation Number:</span><br>
                            <span class="info-value">{{ $quotation->formatted_quotation_number }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="info-label">Reference Number:</span><br>
                            <span class="info-value">{{ $quotation->reference_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="info-label">Quotation Date:</span><br>
                            <span class="info-value">{{ $quotation->quotation_date->format('d-M-Y') }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="info-label">Valid Until:</span><br>
                            <span class="info-value">{{ $quotation->valid_until->format('d-M-Y') }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="info-label">Company:</span><br>
                            <span class="info-value">{{ $quotation->company->company_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="info-label">Customer:</span><br>
                            <span class="info-value">{{ $quotation->customer->customer_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="info-label">Site:</span><br>
                            <span class="info-value">{{ $quotation->site->site_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Quotation Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Sample Type</th>
                                    <th>Tests</th>
                                    <th>Description</th>
                                    <th class="text-center">Sample Qty</th>
                                    <th class="text-end">Sample Rate</th>
                                    <!-- th class="text-end">Test Total</th -->
                                    <th class="text-center">Disc %</th>
                                    <th class="text-end">Item Total</th>
                                    <th class="text-center">TAT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotation->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->sampleType->sample_type_name ?? 'N/A' }}</td>
                                        <td>
                                            @if($item->tests && $item->tests->count() > 0)
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($item->tests as $test)
                                                        <li>• {{ $test->test->test_name ?? 'N/A' }} </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->description ?? '-' }}</td>
                                        <td class="text-center">{{ $item->sample_count }}</td>
                                        <td class="text-end">₹{{ number_format($item->sample_rate, 2) }}</td>
                                        <!-- td class="text-end">₹{{ number_format($item->test_total, 2) }}</td -->
                                        <td class="text-center">{{ $item->discount_percent }}%</td>
                                        <td class="text-end">₹{{ number_format($item->line_total, 2) }}</td>
                                        <td class="text-center">{{ $item->tat_days ?? '-' }} days</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pricing Summary -->
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Pricing Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">₹{{ number_format($quotation->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount ({{ $quotation->discount_percent }}%):</strong></td>
                                    <td class="text-end">₹{{ number_format($quotation->discount_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax ({{ $quotation->tax_percent }}%):</strong></td>
                                    <td class="text-end">₹{{ number_format($quotation->tax_amount, 2) }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>₹{{ number_format($quotation->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($quotation->terms_and_conditions || $quotation->notes || $quotation->special_instructions)
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Additional Information</h5>
                    </div>
                    <div class="card-body">
                        @if($quotation->terms_and_conditions)
                            <div class="mb-3">
                                <strong>Terms & Conditions:</strong>
                                <p class="mb-0">{{ $quotation->terms_and_conditions }}</p>
                            </div>
                        @endif
                        @if($quotation->notes)
                            <div class="mb-3">
                                <strong>Notes:</strong>
                                <p class="mb-0">{{ $quotation->notes }}</p>
                            </div>
                        @endif
                        @if($quotation->special_instructions)
                            <div>
                                <strong>Special Instructions:</strong>
                                <p class="mb-0">{{ $quotation->special_instructions }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Attachments -->
            @if($quotation->attachments->count() > 0)
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>Attachments</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($quotation->attachments as $attachment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file me-2"></i>
                                        {{ $attachment->original_name }}
                                        <small class="text-muted">({{ $attachment->formatted_file_size }})</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('quotation.download-attachment', $attachment->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Approval History -->
            @if($quotation->approvals->count() > 0)
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Approval History</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Submitted By</th>
                                        <th>Submitted At</th>
                                        <th>Approved By</th>
                                        <th>Approved At</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotation->approvals as $approval)
                                        <tr>
                                            <td>{{ $approval->submitter->firstname ?? 'N/A' }}</td>
                                            <td>{{ $approval->submitted_at->format('d-M-Y H:i') }}</td>
                                            <td>{{ $approval->approver->firstname ?? '-' }}</td>
                                            <td>{{ $approval->approved_at ? $approval->approved_at->format('d-M-Y H:i') : '-' }}</td>
                                            <td>
                                                @php
                                                    $approvalColors = [1 => 'warning', 2 => 'success', 3 => 'danger'];
                                                @endphp
                                                <span class="badge bg-{{ $approvalColors[$approval->status] }}">
                                                    {{ $approval->status_name }}
                                                </span>
                                            </td>
                                            <td>{{ $approval->remarks ?? $approval->rejection_reason ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</main>

<!-- Submit for Approval Modal -->
<div class="modal fade" id="submitApprovalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('quotation.submit-approval', $quotation->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Submit for Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit this quotation for approval?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('quotation.approve', $quotation->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('quotation.reject', $quotation->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Revision Modal -->
<div class="modal fade" id="revisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('quotation.create-revision', $quotation->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create Revision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will create a new revision (Rev. {{ $quotation->revision + 1 }}) of this quotation.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Revision</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('quotation.change-status', $quotation->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Status *</label>
                        <select class="form-select" name="status" required>
                            <option value="5">Closed</option>
                            <option value="6">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Change Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

