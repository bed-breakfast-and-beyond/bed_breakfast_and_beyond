<?php

    $jsonString = $_REQUEST['resultJSON'];
    $currentListing = $_REQUEST['selected'];

    
?>

<script type="text/javascript" language="javascript src="json.js"></script>
<script language="javascript" type="text/javascript">
    //Global variables
    var resultJSON = JSON.parse("<?php echo $jsonString; ?>");
    var selected = JSON.parse("<?php echo $current; ?>");
</script>

<?php
    $page = file_get_contents('./comparator.html', false);
    echo $page;
?>