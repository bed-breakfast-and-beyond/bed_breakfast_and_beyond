function Comparator(comparator, listings){

	this.comp = comparator;
	this.listingResults = listings;

	
	//Allows the comparator to scroll with the user so it is always visable to the user.
	$(window).on('scroll', function () {
		var scrollTop = $(window).scrollTop(),
			elementOffset = $(this.listingResults).offset().top,
			distance = (elementOffset - scrollTop);
			
		//var comp = document.getElementById('comparator');
		if(distance < 0 ){
			this.comp.style.position = 'fixed';
			this.comp.style.width = '30%';
		}
		else{
			this.comp.style.position = 'absolute';
			this.comp.style.width = '40%';
		}
	});
		
	this.showComparator = function (){
		//var comp = document.getElementById('comparator');
		this.comp.style.display = 'block';
		//document.getElementById('div-my-tabl').style.marginLeft = '50%';
		this.listingResults.style.marginLeft = '50%';
	}

	this.hideComparator = function (){
		//var comp = document.getElementById('comparator');
		this.comp.style.display = 'none';
		//document.getElementById('div-my-tabl').style.marginLeft = '0px';
		this.listingResults.style.marginLeft = '0px';
	}

	//Add listing to comparator
	this.addToCompare = function (currentElement, searchresults){
		//var comp = document.getElementById('comparator');
		var index = parseInt(currentElement.getAttribute("index"));
		
		
		//Removes currently stored listing in Comparator
		if($('#comparator').length >= 1){
			var checkboxes = document.getElementsByClassName('checkitem');
			//Unchecks all other checkboxes except the current checkbox
			for(var i = 0; i < checkboxes.length; i+=1)
			{
				if(i!=index){
					checkboxes[i].childNodes[0].checked = false;
				}
			}
			
			this.comp.innerHTML = '';
		}
		//If user checks a listing, then display the listing in comparator.
		if(currentElement.checked){
			
			var listing = searchresults[index];
			
			//var comp = document.getElementById('comparator');
			var image = document.createElement('img');
			image.setAttribute('src',listing.image);
			image.setAttribute('style','float:left;margin:10');
			this.comp.appendChild(image);
			var text = document.createElement('p');
			text.innerHTML = '<div><h3><a href=\"http://'+ listing.url + '\" target=\"blank\">'+listing.name + '</a></h3>' + '<br>'+ listing.room+ '<br>' + listing.currency + '</div>';
			
			this.comp.appendChild(text);
			this.showComparator();
		}
		else{
			//If user checks the compare box off, then hide comparator
			this.hideComparator();
		}
	}

}

function enterComparator(listings, currentListing, form){
    var serializeListings = JSON.stringify(listings, null, 2);
    var serializeCurrentListing = JSON.stringify(listings.searchresult[currentListing], null, 2);
    
    form.setAttribute("method","post");
    form.setAttribute("action","comparator.php");
    form.setAttribute("target","_blank");
    
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type","hidden");
    hiddenField.setAttribute("name","resultJSON");
    hiddenField.setAttribute("value",serializeListings);
    
    var hiddenField2 = document.createElement("input");
    hiddenField2.setAttribute("type","hidden");
    hiddenField2.setAttribute("name","selected");
    hiddenField2.setAttribute("value",serializeCurrentListing);
    
    form.appendChild(hiddenField);
    form.appendChild(hiddenField2);
    
    form.submit();
}