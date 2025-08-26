@extends('/layouts/master-layout')
@section('content')
<form method="POST" action="{{ route('save-sample-details') }}" id="sampleForm"  enctype="multipart/form-data">
      @csrf
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Devices</th>
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
                    <th>Report Expected</th>
                    <th>Qty</th>
                    <th>Type Of Bottle</th>
                    <th>Problem</th>
                    <th>Comments</th>
                    <th>Customer Note</th>
                    <th>Severity</th>
                    <th>Oil Drained</th>
                    <th>Image Attachment</th>
                    <th>FTR Attachment</th>
                    <th>Invoice</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @for($i =0;$i<$sample[0]->no_of_samples;$i++)
                <tr style="width: 1500px;" id="tr{{ $i+1 }}">
                    <td><i class="bi bi-plus"></i></td>
                    <td style="width:200px">
                        <select name="device_id[{{ $i+1 }}]" class="form-select selwidth equipment" data-id="{{ $i+1 }}">
                            @foreach($equipments as $device)
                            <option value="{{$device->id}}">{{$device->machine_number." ". $device->customer_site_equiment_name }}</option> 
                            @endforeach
                            <option value="New">New</option>
                        </select>
                    </td>
                    <td width='50'>
                        <select name="sample_type[{{ $i+1 }}]" class="form-select selwidth">
                            <option value="">Select Sample Type</option>
                            @foreach($sample_types as $k => $v)
                            <option value="{{$k}}">{{$v }}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="nature_of_Sample[{{ $i+1 }}]" class="form-select selwidth">
                            <option value="">Select Nature Of Sample</option>
                            @foreach($sample_natures as $k => $v)
                            <option value="{{$k}}">{{$v }}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="running_hrs[{{ $i+1 }}]"></td>
                    <td><input type="text" name="sub_asy_no[{{ $i+1 }}]"></td>
                    <td><input type="text" name="sub_asy_hrs[{{ $i+1 }}]"></td>
                    <td>
                        <input type="text" name="sampling_date[{{ $i+1 }}]" style="width:60px">
                    </td>
                    <td>
                        <select name="brand_of_oil[{{ $i+1 }}]" class="form-select selwidth">
                            @foreach(config('constants.BRAND_OF_OIL') as $k => $val)
                            <option value="{{$k}}">{{$val}}</option>
                            @endforeach 
                        </select>
                    </td>
                    <td>
                        <input type="text" name="grade[{{ $i+1 }}]">
                    </td>
                    <td>
                        <input type="text" name="lube_oil_running_hrs[{{ $i+1 }}]">
                    </td>
                    <td>
                        <input type="text" name="top_up_volume[{{ $i+1 }}]">
                    </td>
                    <td>
                        <input type="text" name="sump_capacity[{{ $i+1 }}]">
                    </td>
                    <td>
                        <input type="text" name="sampling_from[{{ $i+1 }}]">
                    </td>
                    <td><input type="text" name="report_expected_date[{{ $i+1 }}]"></td>
                    <td>
                        <input type="text" name="qty[{{ $i+1 }}]">
                    </td>
                    <td>
                        <select name="type_of_bottle[{{ $i+1 }}]" class="form-control">
                            <option value="">Select BottleType</option>
                            @foreach($bottle_types as $k => $v)
                            <option value="{{$k}}">{{$v }}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="problem[{{ $i+1 }}]" style="width:150px">
                    </td>
                    <td>
                        <input type="text" name="comments[{{ $i+1 }}]" style="width:150px">
                    </td>
                    <td>
                        <input type="text" name="customer_note[{{ $i+1 }}]" style="width:150px">
                    </td>
                    <td>
                        <select name="severity[{{ $i+1 }}]" class="form-control">
                            @foreach(config('constants.SEVERITY') as $k => $val)
                            <option value="{{$k}}">{{$val}}</option>
                            @endforeach 
                        </select>
                    </td>
                    <td>
                        <select name="oil_drained[{{ $i+1 }}]" class="form-control">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control image" type="file" name="image[{{ $i+1 }}]" value="Upload Image">
                    </td>
                    <td>
                        <input class="form-control fir" type="file" name="fir[{{ $i+1 }}]" value="Upload Image">
                    </td>
                    <td><input type="text" name="invoice[{{ $i+1 }}]" style="width:150px"></td>
                    <td><button type="button">Save</button></td>
                </tr>
                @endfor 
                <tr class="save-row">
                    <td>
                        <input type="hidden" name="sample_id" value="{{$sample[0]->id}}">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </td>
                </tr> 
            </tbody>
        </table>
    </div>
</form>
<style>
.hidden-make-td, .hidden-new-make-td, .hidden-new-model-td {
    display: none;
}
</style>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Ensure the code runs after the document is fully loaded
    $(document).ready(function() {
        // A counter to ensure each new row has a unique ID, starting from the last existing row
        let rowCounter = parseInt('{{ $sample[0]->no_of_samples ?? 0 }}', 10);
        let clickedId = $(this).attr("id");
        // Listen for a 'change' event on any element with the class 'equipment' inside the table body
        $('tbody').on('change', '.equipment', function() {
            // Check if the selected value is 'New'
            if ($(this).val() === 'New') {
                // Find the closest parent row (tr) of the changed select box
                const currentRow = $(this).closest('tr');
                // Increment the counter for the new row
                rowCounter++;
                const newRowId = `tr${rowCounter}`;
                
                // Define the HTML for the new table row
                const newRowHtml = `
                    <tr style="width: 1500px;" id="${newRowId}">
                        <td><i class="bi bi-plus"></i></td>
                        <td style="width:200px">
                            <select name="make_model_id['+clickedId+']" class="form-select selwidth dynamic-field" data-id="${rowCounter}">
                                <option value="">Select MakeModel</option>
                                @foreach($make_models as $mm)
                                <option value="{{$mm->id}}">{{$mm->make." ". $mm->model }}</option> 
                                @endforeach
                                <option value="New">New</option>
                            </select>
                        </td>
                        <td class="hidden-make-td">
                            <select name="make_name['+clickedId+]" class="form-select selwidth dynamic-field" data-id="${rowCounter}">
                                <option value="">Select Make</option>
                                @foreach($makes as $k => $make)
                                <option value="{{$make}}">{{$make }}</option> 
                                @endforeach
                                <option value="New">New</option>
                            </select>
                        </td>
                        <td class="hidden-new-make-td">
                            <input type="text" name="new_make_name['+clickedId+]" placeholder="New Make Name">
                        </td>
                        <td class="hidden-new-model-td">
                            <input type="text" name="new_model_name['+clickedId+]" placeholder="New Model Name">
                        </td>
                        <td><input type="text" name="New_EquipmentName['+clickedId+]" placeholder="New Equipment Name"></td>
                        <td width='50'> 
                            <input type="text" name="serial_number['+clickedId+]" placeholder="Serial Number">
                        </td>
                        <td>
                            <input type="text" name="customer_site_equiment_name['+clickedId+]" placeholder="Customer Site Equiment Name">
                        </td>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                `;

                // Insert the new row HTML immediately after the current row
                currentRow.after(newRowHtml);
            }
        });
        
        // New event listener for the dynamic fields within the table body
        $('tbody').on('change', '.dynamic-field', function() {
            const dropdown = $(this);
            const parentRow = dropdown.closest('tr');
            
            // Check if the make_model_id dropdown was changed to 'New'
            if (dropdown.attr('name') === 'make_model_id[]' && dropdown.val() === 'New') {
                parentRow.find('.hidden-make-td').show();
                parentRow.find('.hidden-new-model-td').show();
            } else if (dropdown.attr('name') === 'make_model_id[]') {
                parentRow.find('.hidden-make-td').hide();
                parentRow.find('.hidden-new-model-td').hide();
            }
            
            // Check if the make_name dropdown was changed to 'New'
            if (dropdown.attr('name') === 'make_name[]' && dropdown.val() === 'New') {
                parentRow.find('.hidden-new-make-td').show();
            } else if (dropdown.attr('name') === 'make_name[]') {
                parentRow.find('.hidden-new-make-td').hide();
            }
        });
    });
</script>
@endsection