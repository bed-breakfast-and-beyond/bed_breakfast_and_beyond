/*
 * Loads local restaurants from yelpResults.json file stored on the root directory of the web server
 */	
 
function loadYelpResults(){
    $.getJSON("yelpResults.json", function(data) {
		$listings = data['businesses'];
        
        //Create a table
        var uList = $('<table></table>');
        uList.css('padding','0px');
        
        //Populate local restaurant listing from the JSON string recieved by the Yelp API
		$.each($listings, function(i, item) {
        //Rating must be 3 or above to be added.
        if(item.rating > 3){
            var singleListing = $('<tr></tr>');
            singleListing.css('height','auto');
            var listingContainer = document.createElement('div');
            //Loads a default image if there is no image, otherwise loads the image given by the JSON
            if(typeof(item.image_url) === 'undefined') {
                listingContainer.innerHTML += "<img style=\"float:left;\" src=\"http://cache0601.mekusharim.co.il/data/Dynamic/47/23634/11817055_files_100100.jpg\"/>";
            } else {
                listingContainer.innerHTML += "<img style=\"float:left;\" src=\""+item.image_url+"\"/>";
            }
            //Display the name and the rating
            listingContainer.innerHTML  += '<a href= \"'+item.url+ '\" target =\"_blank\" ><h2 style=\"margin:0;\">'
                                        +item.name+'</h2></a>';
            listingContainer.innerHTML += '<br><img src=\"' + item.rating_img_url_small + '\" />';
            
            
            //Add listing to table
            singleListing.append( $('<td></td>').append(listingContainer) );
            uList.append(singleListing);
        }

		});
        //Add table to website
		$("#div-my-tab2").append(uList);

    });
}