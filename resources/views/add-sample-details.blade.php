@extends('/layouts/master-layout1')
@section('content')
<style>
.selwidth{
    width: fit-content; 
    width: 180px !important
}
</style>
          
        <div class="container mt-4">
    <h4>Standard Table with One Row</h4>
    <div>
    <div class="row">
          <div class="col-3"> Site : {{$sample[0]->customer_id}}
          </div>
          <div class="col-3"> Lot No: {{$sample[0]->lot_no}}
          </div>
          <div class="col-3"> Date : {{$sample[0]->sample_date}}
          </div>
          <div class="col-3"> POD : {{$sample[0]->pod_no}}
          </div>
    </div>
    </div>
      <form method="POST" action="{{ route('save-sample-details') }}" id="sampleForm"  enctype="multipart/form-data">
      @csrf
      
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th ></th>
                    <th  >Devices</th>
                    <th >Type of Sample</th>
                    <th >Nature of Sample</th>
                    <th >Running Hrs </th>
                    <th >Sub Asy. No.	</th>
                    <th >Sub Asy. Hrs.  </th>
                    <th >Sampling Dt</th>
                    <th >Brand of Oil</th>
                    <th >Grade</th>
                    <th >Lube Hrs (Oil Running Hrs.)</th>
                    <th >Top Up Volume (Ltr)</th>
                    <th >Sump Capacity</th>
                    <th >Sampling From</th>
                    <th >Report Expected</th>
                    <th >Qty</th>
                    <th >Type Of Bottle</th>
                    <th >Problem</th>
                    <th >Comments</th>
                    <th >Customer Note</th>
                    <th >Severity</th>
                    <th >Oil Drained</th>
                    <th >Image Attachment</th>
                    <th >FTR Attachment</th>
                    <th >Invoice</th>
                </tr>
            </thead>
            <tbody>
            @for($i =0;$i<$sample[0]->no_of_samples;$i++)
           
                <tr style="width: 1500px;">
                    <td><i class="bi bi-plus"></i></td>
                    <td style="width:200px">
                    <select name="device_id[]" class="form-select selwidth">
                    @foreach($devices as $device)
                      <option value="{{$device->id}}">{{$device->machine_number." ". $device->machine_code }}</option>      
                    @endforeach
                    </select>
                    </td>
                    <td width='50' >
                    <select name="sample_type[]" class="form-select selwidth">
                    <option value="">Select Sample Type</option>
                    @foreach($sample_types as $st)
                      <option value="{{$st->id}}">{{$st->sample_type_name }}</option>      
                    @endforeach
                    </select>
                    </td>
                    <td >
                     <select name="nature_of_Sample[]" class="form-select selwidth" >
                     <option value="">Select Nature Of Sample</option>
                        @foreach($sample_natures as $sn)
                      <option value="{{$sn->id}}">{{$sn->sample_nature_name }}</option>      
                    @endforeach
                    </select>
                    </td>
                    <td ><input type="text" name="running_hrs[]" > </td>
                    <td ><input type="text" name="sub_asy_no[]" > </td>
                    <td ><input type="text" name="sub_asy_hrs[]" > </td>
                    <td >
                    <input type="text" name="sampling_date[]" style="width:60px">
                    </td>
                    <td >
                    
                    <select name="brand_of_oil[]" class="form-select selwidth" >
                    @foreach(config('constants.BRAND_OF_OIL') as $k => $val)
                        <option value="{{$k}}">{{$val}}</option>
                        @endforeach 
                    </select>
                    </td>
                    <td >
                    <input type="text" name="grade[]" >
                    </td>
                    <td >
                    <input type="text" name="lube_oil_running_hrs[]" >
                    </td>
                    <td >
                    <input type="text" name="top_up_volume[]" >
                    </td>
                    <td >
                    <input type="text" name="sump_capacity[]" >
                    </td>
                    <td >
                     <input type="text" name="sampling_from[]" >
                    </td>
                    <td ><input type="text" name="report_expected_date[]" ></td>
                    
                    <td >
                    <input type="text" name="qty[]" >
                    </td>
                    <td >
                    <select name="type_of_bottle[]" class="form-control" >
                    <option value="">Select BottleType</option>
                    @foreach($bottle_types as $bt)
                      <option value="{{$bt->id}}">{{$bt->bottle_name }}</option>      
                    @endforeach
                    </select>
                    </td>
                    <td >
                    <input type="text" name="problem[]" style="width:150px">
                    </td>
                    <td >
                    <input type="text" name="comments[]" style="width:150px">
                    </td>
                    <td >
                    <input type="text" name="customer_note[]" style="width:150px">
                    </td>
                    <td >
                    <select name="severity[]" class="form-control" >
                    @foreach(config('constants.SEVERITY') as $k => $val)
                        <option value="{{$k}}">{{$val}}</option>
                        @endforeach 
                    </select>
                    </td>
                    <td >
                    <select name="oil_drained[]" class="form-control" >
                    <option value="Y">Yes</option>
                    <option value="N">No</option>
                    </select>
                    </td>
                    <td >
                    <input class="form-control image" type="file" name="image[]" value="Upload Image">
                     
                    </td>
                    <td >
                    <input class="form-control fir"  type="file" name="fir[]" value="Upload Image">
                    </td>
                    <td ><input type="text" name="invoice[]" style="width:150px"></td>
                    <td ><button type="button">Save</button></td>
                </tr>
            @endfor  
            <tr>
             <td>
             <input type="hidden" name="sample_id" value="{{$sample[0]->id}}">
             <button type="submit" class="btn btn-primary">Save</button></td>
             </form>
                </tr>  
            </tbody>
        </table>
    </div>
</div>



@stop