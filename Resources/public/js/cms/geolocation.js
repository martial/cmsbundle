

    var geocoder;
    var map;
    var infowindow = new google.maps.InfoWindow();
    var marker;

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

    function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(46.00,2.00);
        var mapOptions = {
            zoom: 6,
            center: latlng,
            mapTypeId: 'roadmap'
        }
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    }

    function codeLatLng(latitude, longitude) {
        var latlng = new google.maps.LatLng(latitude, longitude);

        console.log(latlng);

        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    map.setZoom(14);
                    marker = new google.maps.Marker({
                        position: latlng,
                        map: map
                    });
                    results
                    infowindow.setContent(results[1].formatted_address);
                    infowindow.open(map, marker);
                } else {
                    alert('No results found');
                }
            } else {
                alert('Geocoder failed due to: ' + status);
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
