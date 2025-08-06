@extends('/layouts/master-layout')



@section('content')
<div class="container mt-4">

 

 
    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                               
                                <th>Site Name</th>
                                <th>City/District</th>
                                <th>Status</th>
                                <th>Send Bill	</th>
                                <th>Report</th>
                                <th >whatsapp</th>
                            </tr>
                        </thead>
                           <form id="assginContact" method="POST" action="{{ route('contact_assignments.store') }}">
            @csrf
                        <tbody>
                            @foreach($contacts as $key => $contact)
                                <tr>
                                    <td>{{ $contact->id }}
                                    <input type="hidden" name="counter[{{$key}}]" value="{{$key}}">
                                    </td>
                                   
                                    <td>{{ $contact->firstname }}</td>
                                   <td>{{ $contact->lastname }}</td>
                                   <td><input type="checkbox" name="send_email[{{$key}}]" value="{{$contact->id}}"></td>
                                   <td><input type="checkbox" name="send_report[{{$key}}]" value="{{$contact->id}}"></td>
                                   <td><input type="checkbox" name="whatsapp[{{$key}}]" value="{{$contact->id}}"></td>
                                   
                                    	
                                </tr>
                            @endforeach
                            <tr>
                                    <td align="center" colspan='6'>
                                    <input type="text" name="site_id" value="{{$_GET['site_id']}}">
                                    <input type="text" name="customer_id" value="{{$_GET['customer_id']}}">
                                    <button class="btn btn-success " type="submit">Assign Contact</button>
                                    </td></tr>
                                    </form>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No site masters found</h5>
                    <p class="text-muted">Get started by adding your first site master.</p>
                    <a href="{{ route('site-masters.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Site
                    </a>
                </div>
            @endif
        </div>
    </div>


</div>
@endsection