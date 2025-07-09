@extends('/layouts/master-layout')
@section('content')


<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sample Receipt</h5>
            <div>
                <button class="btn btn-sm btn-primary me-2"><i class="bi bi-plus-lg"></i></button>
                <button class="btn btn-sm btn-secondary me-2"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
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
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteSample({{ $sample->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                            <td>{{ date('d-M-Y',strtotime($sample->sample_date)) }}</td>
                            <td>{{ $sample->lot_no }}</td>
                            <td>{{ date('H:i:s',strtotime($sample->sample_date)) }}</td>
                            <td>{{ $sample->courier_id }}</td>
                            <td>{{ $sample->customer_id }}</td>
                            <td>{{ $sample->company_id }}</td>
                            <td>{{ $sample->pod_no }}</td>
                            <td>{{ $sample->no_of_samples }}</td>
                            <td>{{ $sample->cus_site_contact_person_id }}</td>
                            <td>{{ $sample->site_company }}</td>
                            <td>{{ $sample->remarks }}</td>
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
