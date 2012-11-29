	function loadYelpResults(){
	$.getJSON("yelpResults.json", function(data) {
			$listings = data['businesses'];
			
		$("#div-my-tab2").append("<table>");
			var urlList = "";

	    
		$.each($listings, function(i, item) {
				$("#div-my-tab2").append("<tr>");
				$("#div-my-tab2").append("<td id=1>"+item.name+"</td>");
				$("#div-my-tab2").append("<br>");

				$("#div-my-tab2").append("<td id=4>");
				$("#div-my-tab2").append("<a href= \""+item.url+"\" target =\"_blank\" >"+item.name+"</a>");
				$("#div-my-tab2").append("</td>");
									
				$("#div-my-tab2").append("<td id=5>");
				if(typeof(item.image_url) === 'undefined') {
					$("#div-my-tab2").append("<img src=\"http://cache0601.mekusharim.co.il/data/Dynamic/47/23634/11817055_files_100100.jpg\"/>");
				} else {
					$("#div-my-tab2").append("<img src=\""+item.image_url+"\"/>");
				}			
				$("#div-my-tab2").append("</td>");
				
				
		    $("#div-my-tab2").append("</tr>");
				$("#div-my-tab2").append("<br />");
				$("#div-my-tab2").append("<br />");
				$("#div-my-tab2").append("<br />");
				
		});
			
		$("#div-my-tab2").append("</table>");

	    });
	}