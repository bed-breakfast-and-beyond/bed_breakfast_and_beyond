/*
 * Opens a new window and enters the Comparator mode
 * @param listings The JSON object for the scrapped data from aggregated sites
 * @param currentListing The current JSON object which represents the listing the user wants to compare
 * @param form The form used to submit the JSON strings to.
 */
function enterComparator(listings, currentListing, form){
    var serializeListings = JSON.stringify(listings);
    var serializeCurrentListing = JSON.stringify(listings.searchresult[currentListing]);
    
    
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