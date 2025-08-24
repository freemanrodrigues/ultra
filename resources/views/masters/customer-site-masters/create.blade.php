@extends('/layouts/master-layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Assign Customer Site</h5>
        </div>
@if ($errors->any())
    <div class="bg-danger border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Whoops!</strong>
        <span class="block sm:inline">There were some problems with your input.</span>
        <ul class="mt-3 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <div class="card-body">
            <form action="{{ route('customer-site-masters.store') }}" method="POST" id="siteForm" >
                @csrf
                <div class="row g-3">
                <div class="col-md-6">
                        
                        @if(!empty(request('customer_id')))
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->customer_name }}
                                </option>
                            @endforeach
                        </select>
                        @else
                        <label for="id_customer" class="form-label">Customer</label>
                        <input type="hidden" id="customer_id" name="customer_id"> 
                        <input type="text" class="form-control search"  name="search" id="id_customer" data-txt_id="customer_id"
                       value="{{ request('search') }}" placeholder="Search by code or name..."  autocomplete="off">
                        <div id="myDropdown_customer_id" class="myDropdown"></div>
                         @endif
                    </div>
                    <div class="col-md-6">
                        <label for="site_master_id" class="form-label">Site</label>
                        <!--
                        <select name="site_master_id" id="site_master_id" class="form-select" required>
                        @foreach($site_masters as $k => $val)
                        <option value="{{$val->id}}">{{$val->site_name}} - {{$val->city}}</option>
                        @endforeach  
                        </select>
                        -->
                        <input type="hidden" id="site_master_id" name="site_master_id"> 
                        <input type="text" class="form-control search"  name="search"  id="id_site_master" data-txt_id="site_master_id"
                       value="{{ request('search') }}" placeholder="Search Site Name..."  autocomplete="off">
                        
                        <div id="myDropdown_site_master_id" class="myDropdown"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="site_customer_name" class="form-label">Customer Site Name</label>
                        <input type="text" name="site_customer_name" id="site_customer_name"  class="form-control">
                    </div>

                    
                    <div class="col-md-6">
                        <label for="site_customer_code" class="form-label">Customer Site Code</label>
                        <input type="text" name="site_customer_code" id="site_customer_code" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label for="YourPlaces" class="form-label">Address Line 1</label>
                        <input type="text" name="address" class="form-control" id="YourPlaces">
                    </div>


                    <div class="col-md-4">
                        <label for="YourCity" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" id="YourCity" >
                    </div>

                    <div class="col-md-4">
                            <label for="YourState" class="form-label">State</label>
                        <select class="form-select @error('state') is-invalid @enderror" 
                                id="YourState" name="state" required>
                            <option value="">Select State</option>
                            @foreach($states as $k => $state)
                                <option value="{{ $k }}" {{ (old('state') ?? optional($select_customer)->state ) == $k ? 'selected' : '' }}> {{ $state }} </option>
                            @endforeach
                        </select> 
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="YourCountry" class="form-label">Country</label>
                        <select class="form-select @error('country') is-invalid @enderror" 
                                id="YourCountry" name="country" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $k => $country)
                                <option value="{{ $k }}" {{ (old('country') ?? optional($select_customer)->country ) == $k ? 'selected' : '' }}> {{ $country }} </option>
                            @endforeach
                        </select> 
                        
                        <input type="hidden" id="YourCountryCode" name="CountryCode"/> 
                        <input type="hidden" name="lat" class="form-control"  id="YourLat">
                        <input type="hidden" name="long" class="form-control" id="YourLong">
                    </div>
                 
                    <div class="col-md-6">
                        <label for="YourPinCode" class="form-label">PinCode</label>
                       <input type="text" id="YourPinCode" name="pincode" class="form-control"  placeholder="Your Pin Code" />
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

               
                <div class="mt-4 text-end">
                    <a href="{{ route('site-masters.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Create Customer Site</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/js/customer/function_autosuggest33.js?{{date('mmss')}}"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyCnetAvQ6zC7jmWmA3iGwVWmLhjthohRFk"></script>
<script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyCXFJ-lc7cHHcEklG2_oIhTnPKTWsLwHEU"></script>
<script>
google.maps.event.addDomListener(window, 'load', function () 
{
//var places = new google.maps.places.Autocomplete(document.getElementById('YourPlaces'));
var places = new google.maps.places.Autocomplete((document.getElementById('YourPlaces')), {types:['geocode'], });

google.maps.event.addListener(places, 'place_changed', function () 
{
//console.log(places.getPlace());
var getaddress    = places.getPlace();              //alert("Get Addreess"+getaddress.address_components[0].long_name);



jQuery('#YourLat').val(getaddress.geometry.location.lat());
jQuery('#YourLong').val(getaddress.geometry.location.lng());


var whole_address = getaddress.address_components; // 
//console.log(whole_address);
//alert(whole_address + 'whole_address');   
//alert(lat_obj + 'IP');   
$('#YourCity').val('');
$('#YourState').val('');
$('#YourCountry').val('');
$('#YourPinCode').val('');
$.each(whole_address, function(key1, value1) 
{
//alert(key1+ " "+JSON.stringify(value1));
//console.log('tables: ' + JSON.stringify(value1));
//alert(value1.long_name);
//alert(value1.types[0]);
if((value1.types[0]) == 'locality')
{

var prev_long_name_city = value1.long_name;  
//alert(prev_long_name_city + '__prev_long_name_city');
$('#YourCity').val(prev_long_name_city);
}

if((value1.types[0]) == 'administrative_area_level_1') {
    var prev_long_name_state = value1.long_name;  

   // $('#YourState').val(prev_long_name_state);
      $("#YourState option").filter(function() {
            return $(this).text().trim() === prev_long_name_state;
        }).prop("selected", true).trigger("change");
}
if((value1.types[0]) == 'country') {
    var prev_long_name_country = value1.long_name;  

    //$('#YourCountry').val(prev_long_name_country);
    $("#YourCountry option").filter(function() {
            return $(this).text().trim() === prev_long_name_country;
        }).prop("selected", true).trigger("change");
    $('#YourCountryCode').val(value1.short_name);
}
if((value1.types[0]) == 'postal_code')
{
var prev_long_name_pincode = value1.long_name;  
//alert(prev_long_name_pincode + '__prev_long_name_pincode');
$('#YourPinCode').val(prev_long_name_pincode);
}
}); 
});
});
</script>
@endsection

