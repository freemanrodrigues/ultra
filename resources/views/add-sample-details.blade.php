@extends('/layouts/master-layout')
@section('content')
<style>
td {
  white-space: nowrap;
  padding: 0 10px; /* Adjust 10px to your desired spacing */
}
.selwidth{
  width: auto;  
    min-width: 200px; 
}
</style>
<form method="POST" action="{{ route('save-sample-details') }}" id="sampleForm"  enctype="multipart/form-data">
      @csrf
    <div class="table-responsive">
    <table class="table table-bordered table-striped">

    <tr>
        <th>Date</th><td>{{ \Carbon\Carbon::parse($sample->sample_date)->format('d-m-Y') }}</td>
        <th>Lot.No</th><td>{{$sample->id}}</td>
        <th>Sample Count</th><td>{{$sample->no_of_samples}}</td>
        <th>Customer</th><td>@if($sample->customer) {{$sample->customer->customer_name}}  @endif </td>
        <th>Site</th><td>{{$sample->customer_site_masters->city}}</td>
    </tr>    
</table>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Equipment</th>
                    <th>Type of Sample</th>
                    <th>Nature of Sample</th>
                    <th>Running Hrs </th>
                    <th>Sub Asy. No. </th>
                    <th>Sub Asy. Hrs. </th>
                    <th>Sampling Dt</th>
                    <th>Brand of Oil</th>
                    <th>Grade</th>
                    <th>Lube Hrs (Oil Running Hrs.)</th>
                    <th>Top Up Volume (Ltr)</th>
                    <th>Sump Capacity</th>
                    <th>Sampling From</th>
                  
                    <th>Qty</th>
                    <th>Type Of Bottle</th>
                     <th>Customer Note</th>
             
                    <th>Oil Drained</th>
      
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @for($i =0;$i<$sample->no_of_samples;$i++)
                <tr style="width: 1500px;" id="tr{{ $i+1 }}">
                    <td><i class="bi bi-plus"></i>
                    <input type="hidden" name="sd_id[{{ $i+1 }}]" value = "@if(isset($sample_details[$i]['id'])){{$sample_details[$i]['id']?? ''}} @endif">
                    </td>
                    <td style="width:200px">
                        <select name="device_id[{{ $i+1 }}]" class="form-select selwidth equipment" data-id="{{ $i+1 }}" data-customer_id="{{ $sample->customer_id }}" data-customer_site_id="{{ $sample->customer_site_id }}"required>
                        <option value="">Select</option>
                            @foreach($equipments as $device)
                            <option value="{{$device->id}}" @if(isset( $sample_details[$i]['equipment_assignments_id']) && $sample_details[$i]['equipment_assignments_id'] == $device->id) selected @endif>{{$device->machine_number." ". $device->customer_site_equiment_name }}</option> 
                            @endforeach
                            <option value="New">New</option>
                        </select>
                    </td>
                    <td width='50'>
                        <select name="sample_type[{{ $i+1 }}]" class="form-select selwidth">
                            <option value="">Select Sample Type</option>
                            @foreach($sample_types as $k => $v)
                            <option value="{{$k}}" @if(isset($sample_details[$i]['type_of_sample']) && $sample_details[$i]['type_of_sample'] == $k ) selected @endif>{{$v }}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="nature_of_Sample[{{ $i+1 }}]" class="form-select selwidth">
                            <option value="">Select Nature Of Sample</option>
                            @foreach($sample_natures as $k => $v)
                            <option value="{{$k}}" @if(isset($sample_details[$i]['nature_of_sample']) && $sample_details[$i]['nature_of_sample'] == $k ) selected @endif>{{$v }}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="running_hrs[{{ $i+1 }}]" value="{{$sample_details[$i]['running_hrs']?? '' }}"></td>
                    <td><input type="text" name="sub_asy_no[{{ $i+1 }}]" value="{{$sample_details[$i]['sub_asy_no']?? '' }}"></td>
                    <td><input type="text" name="sub_asy_hrs[{{ $i+1 }}]" value="{{$sample_details[$i]['sub_asy_hrs']?? '' }}"></td>
                    <td>
                        <input type="date" name="sampling_date[{{ $i+1 }}]" value="{{ !empty($sample_details[$i]['sampling_date']) ? \Carbon\Carbon::parse($sample_details[$i]['sampling_date'])->format('Y-m-d') : '' }}" style="width:110px" >
                        
                    </td>
                    <td>
                        <select name="brand_of_oil[{{ $i+1 }}]" class="form-select selwidth">
                            @foreach(config('constants.BRAND_OF_OIL') as $k => $val)
                            <option value="{{$k}}"  @if(isset($sample_details[$i]['brand_of_oil']) && $sample_details[$i]['brand_of_oil'] == $k ) selected @endif>{{$val}}</option>
                            @endforeach 
                        </select>
                    </td>
                    <td>
                        <input type="text" name="grade[{{ $i+1 }}]" value="{{$sample_details[$i]['grade']?? '' }}">
                    </td>
                    <td>
                        <input type="text" name="lube_oil_running_hrs[{{ $i+1 }}]"  value="{{$sample_details[$i]['lube_oil_running_hrs']?? '' }}">
                    </td>
                    <td>
                        <input type="text" name="top_up_volume[{{ $i+1 }}]"  value="{{$sample_details[$i]['top_up_volume']?? '' }}">
                    </td>
                    <td>
                        <input type="text" name="sump_capacity[{{ $i+1 }}]"  value="{{$sample_details[$i]['sump_capacity']?? '' }}">
                    </td>
                    <td>
                        <input type="text" name="sampling_from[{{ $i+1 }}]"  value="{{$sample_details[$i]['sampling_from']?? '' }}">
                    </td>
                    <td>
                        <input type="text" name="qty[{{ $i+1 }}]"  value="{{$sample_details[$i]['qty']?? '' }}">
                    </td>
                    <td>
                        <select name="type_of_bottle[{{ $i+1 }}]" class="form-control selwidth">
                            <option value="">Select BottleType</option>
                            @foreach($bottle_types as $k => $v)
                            <option value="{{$k}}" @if(isset($sample_details[$i]['bottle_types_id']) && $sample_details[$i]['bottle_types_id'] == $k ) selected @endif>{{$v }}</option> 
                            @endforeach
                        </select>
                    </td>
                     <td></td>
                      <td>
                        <select name="oil_drained[{{ $i+1 }}]" class="form-control selwidth">
                            <option value="Y" @if(isset($sample_details[$i]['severity']) && $sample_details[$i]['severity'] == 'Y' ) selected @endif>Yes</option>
                            <option value="N" @if(isset($sample_details[$i]['severity']) && $sample_details[$i]['severity'] == 'N' ) selected @endif>No</option>
                        </select>
                    </td>
                     <td></td>
                   <td><button type="button">Save</button></td>
                   @if(isset($sample_details[$i]['id']) && $sample_details[$i]['id']) 
                   <td><a href="{{route('test-assigned', $sample_details[$i]['id'] )}}">Test</a></td> @endif
                </tr>
                @endfor 
                <tr class="save-row">
                    <td>
                        <input type="hidden" name="sample_id" value="{{$sample->id}}">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </td>
                </tr> 
            </tbody>
        </table>
    </div>
</form>

<div class="modal fade" id="newEquipmentModal" tabindex="-1" aria-labelledby="newEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newEquipmentModalLabel">Add New Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newEquipmentForm">
                    <div class="row">
                        <div class="col-6">
                            <label for="makeModelId" class="form-label">MakeModel</label>
                        </div>
                        <div class="col-6">
                            <select name="make_model_id" class="form-select selwidth" id="makeModelId">
                                <option value="">Select MakeModel</option>
                                @foreach($make_models as $mm)
                                    <option value="{{$mm->id}}">{{$mm->make." ". $mm->model }}</option>
                                @endforeach
                                <option value="New">New</option>
                            </select>
                        </div>
                    </div>
                        <div class="row" id="MakeRow" style="display:none;">
                        <div class="col-6">
                            <label for="make" class="form-label">Make</label>
                        </div>
                        <div class="col-6">
                    <select name="make" class="form-select selwidth" id="make">
                                <option value="">Select Make</option>
                                @foreach($makes as $make)
                                    <option value="{{$make}}">{{$make}}</option>
                                @endforeach
                                <option value="New">New</option>
                            </select>
                        </div>
                    </div>
                    <!-- Hidden row for new Make -->
                    <div class="row mt-2" id="newMakeRow" style="display:none;">
                        <div class="col-6">
                            <label for="new_make_name" class="form-label">Add Make</label>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="new_make_name" name="new_make_name">
                        </div>
                    </div>

                    <!-- Hidden row for new Model -->
                    <div class="row mt-2" id="newModelRow" style="display:none;">
                        <div class="col-6">
                            <label for="new_model_name" class="form-label">Add Model</label>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="new_model_name" name="new_model_name">
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-6">
                            <label for="equipment_name" class="form-label">Equipment Name</label>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="equipment_name" name="equipment_name" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <label for="serial_number" class="form-label">Serial Number</label>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="serial_number" name="serial_number" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <label for="customer_site_equipment_name" class="form-label">Customer Site Equipment Name</label>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" id="customer_site_equipment_name" name="customer_site_equipment_name" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewEquipment">Save</button>
            </div>
        </div>
    </div>
</div>
<style>
.hidden-make-td, .hidden-new-make-td, .hidden-new-model-td {
    display: none;
}
</style>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let currentDropdown = null; // Stores the dropdown that opened the modal

    // Initial state on modal open: hide the conditional rows.
    $('#newEquipmentModal').on('show.bs.modal', function () {
        $('#MakeRow').hide();
        $('#newMakeRow').hide();
        $('#newModelRow').hide();
    });

    // 1. Listen for the 'New' option selection on the main equipment dropdowns
    // Use event delegation to handle dynamically added rows
    $('.table').on('change', '.equipment', function() {
        if ($(this).val() === 'New') {
            currentDropdown = $(this);
            $('#newEquipmentModal').modal('show');
        }
    });

    // 2. Handle the MakeModel dropdown inside the modal
    $('#makeModelId').on('change', function() {
   
        if ($(this).val() === 'New') {
            // Show Make and Add Model rows when 'New' is selected
            $('#MakeRow').show();
            $('#newModelRow').show();
        } else {
            // Hide them otherwise
            $('#MakeRow').hide();
            $('#newModelRow').hide();
            $('#newMakeRow').hide(); // Also hide 'Add Make'
        }
    });

    // 3. Handle the Make dropdown inside the modal
    $('#makeSelect').on('change', function() {
    
        if ($(this).val() === 'New') {
            // Show the 'Add Make' row when 'New' is selected
            $('#newMakeRow').show();
        } else {
            // Hide it otherwise
            $('#newMakeRow').hide();
        }
    });
    
    $('#make').on('change', function() {
     
        if ($(this).val() === 'New') {
            // Show the 'Add Make' row when 'New' is selected
            $('#newMakeRow').show();
        } else {
            // Hide it otherwise
            $('#newMakeRow').hide();
        }
    });


    // 4. Handle the save button click
    $('#saveNewEquipment').on('click', function(e) {
        e.preventDefault();

        const postData = {
            _token: '{{ csrf_token() }}', // Laravel CSRF token
            make_model_id: $('#makeModelId').val(),
         //   make_id: $('#makeSelect').val(), // Correct ID for Make select
            make_id: $('#make').val(), // Correct ID for Make select
            new_make_name: $('#new_make_name').val(),
            new_model_name: $('#new_model_name').val(),
            equipment_name: $('#equipment_name').val(),
            serial_number: $('#serial_number').val(),
            customer_site_equipment_name: $('#customer_site_equipment_name').val(),
            customer_id: currentDropdown.data('customer_id'),
            customer_site_id: currentDropdown.data('customer_site_id')
        };
        
        // Basic validation
        if (!postData.equipment_name || !postData.serial_number || !postData.customer_site_equipment_name) {
            alert('Please fill in all required fields.');
            return;
        }

        // Send AJAX request
        $.ajax({
            url: '/ajax/save-equipment-n-more', 
            type: 'POST',
            data: postData,
            success: function(response) {
                if (response.success) {
                    // Append the new option to the parent dropdown
                    currentDropdown.append($('<option>', {
                        value: response.equipment.id,
                        text: response.equipment.name + ' ' + response.equipment.customer_site_equiment_name,
                        selected: true
                    }));

                    // Close the modal and reset the form
                    $('#newEquipmentModal').modal('hide');
                    $('#newEquipmentForm')[0].reset();
                    alert('Equipment added successfully!');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred. Please try again.');
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    });
});
</script> 
@endsection