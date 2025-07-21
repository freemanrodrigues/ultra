@extends('/layouts/master-layout')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Site Machine Details</h2>
        <a href="{{ route('site-master-device.create') }}" class="btn btn-primary">Add New Machine</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($siteMachineDetails->isEmpty())
        <div class="alert alert-info" role="alert">
            No site machine details found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Model ID</th>
                        <th>Site Master ID</th>
                        <th>Machine Number</th>
                        <th>Machine Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siteMachineDetails as $detail)
                        <tr>
                            <td>{{ $detail->id }}</td>
                            <td>{{ $detail->model_id }}</td>
                            <td>{{ $detail->site_master_id }}</td>
                            <td>{{ $detail->machine_number }}</td>
                            <td>{{ $detail->machine_code }}</td>
                            <td>
                                <a href="{{ route('site-master-device.edit', $detail->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('site-master-device.destroy', $detail->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this machine detail?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection