//Suggested location
function displaySuggested(results, container){
	var locations = document.createElement('div');
	locations.setAttribute('style','display:inline;');
	locations.innerHTML = 'Places nearby '+ resultJSON.place + ':<br>';
	for(var loc in results){
		var link = document.createElement('a');
		link.setAttribute('location',results[loc]);
		link.innerHTML = results[loc]+'     ';
		link.setAttribute('onclick','performSuggestedSearch(this)');
		link.setAttribute('href','#');
		link.setAttribute('style', 'margin:10px;');
		locations.appendChild(link);
	}
	
	
	container.appendChild(document.createElement('br'));
	container.appendChild( locations );
}

function performSuggestedSearch(location){
	var form = document.createElement('form');
	form.setAttribute('method', 'post');
	form.setAttribute('action', './scrap.php');
	
	var place = document.createElement('input');
	var startdate = document.createElement('input');
	var enddate = document.createElement('input');
	var guest = document.createElement('input');
	var room = document.createElement('input');
	
	place.setAttribute('name','place');
	startdate.setAttribute('name','startdate');
	enddate.setAttribute('name','enddate');
	guest.setAttribute('name','guest');
	room.setAttribute('name','room');
	
	place.setAttribute('type','hidden');
	startdate.setAttribute('type','hidden');
	enddate.setAttribute('type','hidden');
	guest.setAttribute('type','hidden');
	room.setAttribute('type','hidden');
	
	place.setAttribute('value',location.getAttribute('location'));
	startdate.setAttribute('value',resultJSON.startdate);
	enddate.setAttribute('value',resultJSON.enddate);
	guest.setAttribute('value',resultJSON.guest);
	room.setAttribute('value',resultJSON.room);
	
	form.appendChild(place);
	form.appendChild(startdate);
	form.appendChild(enddate);
	form.appendChild(guest);
	form.appendChild(room);
	
	document.body.appendChild(form);
	form.submit();
	//alert(location.getAttribute('location'));
}