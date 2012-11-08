	function displayMap(listings, location){
	
		var geocoder = new GClientGeocoder();
		var map = new GMap2(document.getElementById("map_canvas"));
		var i;
		
		var postcodes = new Array();
		for(i=0; i < listings.length; i++){
			postcodes[i] = listings[i].address;
		}
		
		geocoder.getLatLng(location , function (point) {
		map.setCenter(point, 9)}
		);

		for (i = 0; i < postcodes.length; i++) {
			geocoder.getLatLng(postcodes[i], function (point) {         
				if (point) {
					map.addOverlay(new GMarker(point));
				}
			});
		}
		
	}

