var geocoder;
var map;
function displayMap(address, name) {
    geocoder = new google.maps.Geocoder();
    var mapOptions = {
      zoom: 8,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
        
        var center = address;
        geocoder.geocode( { 'address': center}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
          } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
                
        var addresses = [
        address
        ];
                
        var infowindow = new google.maps.InfoWindow();
        var marker,i;
        for (i = 0; i < addresses.length; i++) {
                (function(address) {
                geocoder.geocode( {'address': address}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                                 marker = new google.maps.Marker({
                                        map: map,
                                        position: results[0].geometry.location
                                });

                        } 
                        else{
                                alert("Geocode was not successful for the following reason: " + status);
                }
                                google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(name);
                        infowindow.open(map, marker);
                        });
    });
                })(addresses[i]);
  }
 
        
}
