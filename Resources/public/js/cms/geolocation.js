var geocoder;
var map;
var infowindow = new google.maps.InfoWindow();
var marker;
var input;

function setPosition (latitude, longitude) {
    codeLatLng(latitude, longitude);
}

function success(position) {
    var latitude  = position.coords.latitude;
    var longitude = position.coords.longitude;
    codeLatLng(latitude, longitude);
};

function error() {
    console.log("Unable to retrieve your location");
};

function getPosition() {
    navigator.geolocation.getCurrentPosition(success, error);
}

function getNewPosition(input) {
    //console.log("test2")
    input = document.getElementById("searchTextField").value;
    newgeocoder.geocode({'address': input}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            var latitude = results[0].geometry.location.jb;
            var longitude = results[0].geometry.location.kb;
            codeLatLng(latitude, longitude);
        }
        else {
            alert("Geocoder failed due to: " + status);
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
    input = (document.getElementById('searchTextField'));
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);

    var marker = new google.maps.Marker({
        map: map
    });
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        input.className = '';
        var place = autocomplete.getPlace();
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

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
        getNewPosition(input);
    });
    autocomplete.setTypes([]);
}

function codeLatLng(latitude, longitude) {
    var latlng = new google.maps.LatLng(latitude, longitude);
    var contentInfowindow;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        infowindow.close();
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                map.setZoom(14);
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
                results
                contentInfowindow = results[0].formatted_address;
                infowindow.setContent(contentInfowindow);
                infowindow.open(map, marker);
                //console.log(results)
            } else {
                alert('No results found');
            }
        } else {
            alert('Geocoder failed due to: ' + status);
        }
    });
    $("#scrclub_cmsbundle_posttype_latitude").val(latitude);
    $("#scrclub_cmsbundle_posttype_longitude").val(longitude);
}

