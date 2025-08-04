@extends('/layouts/master-layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Create Customer Site</h5>
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
                        <label for="site_customer_code" class="form-label">Customer Site Code</label>
                        <input type="text" name="site_customer_code" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="site_master_id" class="form-label">Site</label>
                        
                        <select name="site_master_id" class="form-select" required>
                        @foreach($site_masters as $k => $val)
                        <option value="{{$val->id}}">{{$val->site_name}} - {{$val->city}}</option>
                        @endforeach  
                        </select>
                    
                    </div>

                    <div class="col-md-6">
                        <label for="site_customer_name" class="form-label">Customer Site Name</label>
                        <input type="text" name="site_customer_name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customer_id" class="form-select">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="address" class="form-label">Address Line 1</label>
                        <input type="text" name="address" class="form-control" id="YourPlaces">
                    </div>


                    <div class="col-md-4">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" id="YourCity" >
                    </div>

                    <div class="col-md-4">
                        <label for="state" class="form-label">State</label>
                        <input type="text" name="state" class="form-control" id="YourState" >
                    </div>

                    <div class="col-md-4">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" id="YourCountry">
                        <input type="hidden" id="YourCountryCode" name="CountryCode"/> 
                        <input type="hidden" id="YourPinCode" name="pincode" placeholder="Your Pin Code" />
                        <input type="hidden" name="lat" class="form-control" placeholder="e.g., 19.123456" id="YourLat">
                        <input type="hidden" name="long" class="form-control" placeholder="e.g., 72.123456" id="YourLong">
                    </div>
                 
                    <div class="col-md-6">
                        <label for="state" class="form-label">PinCode</label>
                       <input type="text" id="YourPinCode" name="pincode" class="form-control"  placeholder="Your Pin Code" />
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

               
                <div class="mt-4 text-end">
                    <a href="{{ route('site-masters.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Create Site</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
if((value1.types[0]) == 'administrative_area_level_1')
{
var prev_long_name_state = value1.long_name;  
//alert(prev_long_name_state + '__prev_long_name_state');
$('#YourState').val(prev_long_name_state);
}
if((value1.types[0]) == 'country')
{
var prev_long_name_country = value1.long_name;  
//alert(" ##"+value1.short_name);
//alert(prev_long_name_country + '__prev_long_name_country');
$('#YourCountry').val(prev_long_name_country);
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

