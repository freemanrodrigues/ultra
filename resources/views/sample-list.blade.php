@extends('/layouts/master-layout')
@section('content')
<style>
    /* Consistent styling with customer list page */
    .po-page {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        max-height: 100vh;
        overflow: hidden;
    }
    
    .po-page h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .po-page .form-control,
    .po-page .form-select,
    .po-page .btn {
        font-size: 12px !important;
        font-weight: 500;
    }
    
    /* Bold, larger headers (14px, weight 700) for better visibility */
    .po-page .table th {
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
        background-color: #3b82f6;
        color: white;
        border-bottom: 2px solid #1d4ed8;
        height: 2.2rem;
    }
    
    .po-page .table td {
        font-size: 13px !important;
        padding: 0.2rem 0.5rem !important;
        vertical-align: middle;
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
    
    /* Compact spacing */
    .compact-form {
        margin-bottom: 0.5rem;
    }
    
    .card-body {
        padding: 0.5rem !important;
    }
    
    /* Small action buttons */
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
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .po-page h1 {
            font-size: 1.1rem;
        }
        
        .page-header {
            padding: 0.4rem 0.75rem;
        }
    }
</style>


<div class="container-fluid po-page table-responsive">
    <div class="card shadow-sm">

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-file-invoice me-2"></i> Sample Receipt
                <small class="d-block d-md-inline ms-md-2 opacity-75">({{ $samples->count() }} total)</small>
            </h1>
        </div>
    </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover table-bordered mb-0 text-nowrap">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Dt of Rcpt</th>
                        <th>Lot No.</th>
                        <th>Time</th>
                        <th>Courier Name</th>
                        <th>Customer</th>
                        <th>Company</th>
                        <th>POD No</th>
                        <th>No. of Samples</th>
                        <th>Site Contact</th>
                        <th>Site Company</th>
                        <th>Remarks</th>
                        <th>WO No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($samples as $index => $sample)
                        <tr>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="location.href='{{ route('sample-details', $sample->id) }}'">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </td>
                            <td>{{ date('d-M-Y',strtotime($sample->sample_date)) }}</td>
                            <td>{{ $sample->lot_no }}</td>
                            <td>{{ date('H:i:s',strtotime($sample->sample_date)) }}</td>
                            <td>@if(!empty($courier_mst[$sample->courier_id])){{ $courier_mst[$sample->courier_id] }} @endif</td>
                            <td>@if(!empty($customer_mst[$sample->customer_id])){{ $customer_mst[$sample->customer_id] }}@endif</td>
                            <td>@if(!empty($company_mst[$sample->company_id])){{ $company_mst[$sample->company_id] }}@endif  {{ $sample->customer->company->company_name ?? 'N/A' }}</td>
                            <td>{{ $sample->pod_no }}</td>
                            <td>{{ $sample->no_of_samples }}</td>
                            <td>
                            @if ($sample->customer_site_masters && $sample->customer_site_masters->contactAssignments->isNotEmpty())
      {{ $sample->customer_site_masters->contactAssignments->first()->contactMaster->firstname ?? '' }}
      {{ $sample->customer_site_masters->contactAssignments->first()->contactMaster->lastname ?? '' }}
  @else
      N/A
  @endif
                             </td>
                            <td> {{ $sample->customer_site_masters->siteMaster->site_name ?? 'N/A' }}</td>
                            <td>{{ $sample->additional_info }}</td>
                            <td>{{ $sample->work_order }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
{{--
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span>Showing {{ $samples->firstItem() }} to {{ $samples->lastItem() }} of {{ $samples->total() }} entries</span>
            <div>
                {{ $samples->links('pagination::bootstrap-5') }}
            </div>
        </div>
--}}        
    </div>
</div>
@endsection
