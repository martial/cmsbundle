

var geocoder = new google.maps.Geocoder();
var map;
var infowindow = new google.maps.InfoWindow();
var marker;
var input;

function setPositionByFormattedAddress () {


    //geocoder = new google.maps.Geocoder();

    //map = new google.maps.Map(document.getElementById("map-canvas"));

    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });

}

function setPosition (latitude, longitude) {

    //autoComplete();
   // codeLatLng(latitude, longitude);

    //console.log("set it ")

    var address = document.getElementById("scrclub_cmsbundle_posttype_formatted_address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);

            if (results[0].geometry.viewport) {
                map.fitBounds(results[0].geometry.viewport);
            } else {
                map.setCenter(results[0].geometry.location);
                map.setZoom(17);
            }

            console.log(results[0]);


            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });

            marker.setPosition(results[0].geometry.location);
            marker.setVisible(true);
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });

}

function success(position) {
    var latitude  = position.coords.latitude;
    var longitude = position.coords.longitude;
    codeLatLng(latitude, longitude);
};

function error() {
    //console.log("Unable to retrieve your location");
};

function getPosition() {
    navigator.geolocation.getCurrentPosition(success, error);
}

function getNewPosition(input) {

    input = document.getElementById("scrclub_cmsbundle_posttype_formatted_address").value;


    newgeocoder.geocode({'address': input}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();
            codeLatLng(latitude, longitude);
        }
        else {
            //alert("Geocoder failed due to: " + status);
        }
        setPosition(latitude, longitude);
    });
}

function initialize() {
    geocoder = new google.maps.Geocoder();
    newgeocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(48.6,9.1);
    var mapOptions = {
        zoom: 3,
        center: latlng,
        mapTypeId: 'roadmap'
    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    autoComplete();
}

function autoComplete() {

    input = (document.getElementById('scrclub_cmsbundle_posttype_formatted_address'));

    var autocomplete = new google.maps.places.Autocomplete(input);

    $('#scrclub_cmsbundle_posttype_formatted_address').keydown(function (e) {
        if (e.which == 13 )
            return false;

        //e.preventDefault();
    });


    autocomplete.bindTo('bounds', map);

    var marker = new google.maps.Marker({
        map: map
    });




    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        input.className = '';
        var place = autocomplete.getPlace();

        //console.log(place);

        if (!place.geometry) {
            input.className = 'notfound';
            return;
        }

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        // add lat and long to field

        place["latitude"] = place.geometry.location.lat();
        place["longitude"] = place.geometry.location.lng();

        var st = JSON.stringify(place);
        $.post(gmapUrl,{
            data:st

        },function(data){

            console.log(data);


        });


        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
       // console.log(place);
        //getNewPosition(input);

       // console.log("get location");
        //console.log(place.geometry.location);

        $("#scrclub_cmsbundle_posttype_latitude").val(place.geometry.location.lat());
        $("#scrclub_cmsbundle_posttype_longitude").val( place.geometry.location.lng());

        //setPosition(place.geometry.location.lat(), place.geometry.location.lng());

        return false;
    });
    autocomplete.setTypes([]);
}


function codeLatLng(latitude, longitude) {

   // console.log("set position", latitude, longitude)

    var latlng = new google.maps.LatLng(latitude, longitude);
    var contentInfowindow;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        infowindow.close();
        if (status == google.maps.GeocoderStatus.OK) {

            //console.log(results)

            if (results[1]) {

                if (results[1].geometry.viewport) {
                    map.fitBounds(results[1].geometry.viewport);
                } else {
                    map.setCenter(results[1].geometry.location);
                    map.setZoom(17);
                }
                var marker = new google.maps.Marker({
                    map: map
                });

                marker.setPosition(results[1].geometry.location);
                marker.setVisible(true);

                /*
                map.setZoom(14);
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });*/




                //contentInfowindow = results[0].formatted_address;
                //infowindow.setContent(contentInfowindow);
                //infowindow.open(map, marker);
                //console.log(results);
               // $("#scrclub_cmsbundle_posttype_formatted_address").val(results[0]["formatted_address"]);
            } else {
                alert('No results found');
            }
        } else {
           // alert('Geocoder failed due to: ' + status);
        }
    });


    $("#scrclub_cmsbundle_posttype_latitude").val(latitude);
    $("#scrclub_cmsbundle_posttype_longitude").val(longitude);


}
//administrative_area_level_1
//administrative_area_level_2
// locality
//street_address

//point_of_interest