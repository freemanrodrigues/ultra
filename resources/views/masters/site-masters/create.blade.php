@extends('/layouts/master-layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Create New Site</h5>
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
            <form action="{{ route('site-masters.store') }}" method="POST" id="siteForm" >
                @csrf
                <div class="row g-3">
              
                    <div class="col-md-12">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" name="site_name" id="site_name" class="form-control" required>
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
                                <option value="{{ $k }}" {{ (old('state') ) == $k ? 'selected' : '' }}> {{ $state }} </option>
                            @endforeach
                        </select> 
                    </div>

                    <div class="col-md-4">
                        <label for="YourCountry" class="form-label">Country</label>
                        <select class="form-select @error('country') is-invalid @enderror" 
                                id="YourCountry" name="country" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $k => $country)
                                <option value="{{ $k }}" {{ (old('country')) == $k ? 'selected' : '' }}> {{ $country }} </option>
                            @endforeach
                        </select> 
                        <input type="hidden" id="YourCountryCode" name="CountryCode"/> 
                        <input type="hidden" id="YourPinCode" name="pincode" placeholder="Your Pin Code" />
                        <input type="hidden" name="lat" class="form-control"  id="YourLat">
                        <input type="hidden" name="long" class="form-control" id="YourLong">
                    </div>

                    

                    <div class="col-md-12">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" id="status">
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
//$('#YourPinCode').val('');
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
//$('#YourState').val(prev_long_name_state);
 $("#YourState option").filter(function() {
            return $(this).text().trim() === prev_long_name_state;
        }).prop("selected", true).trigger("change");
}
if((value1.types[0]) == 'country')
{
var prev_long_name_country = value1.long_name;  
//alert(" ##"+value1.short_name);
//alert(prev_long_name_country + '__prev_long_name_country');
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

