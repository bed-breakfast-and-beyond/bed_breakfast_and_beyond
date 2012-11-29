<?php

    $jsonString = $_REQUEST['resultJSON'];
    //$jsonString = str_replace("\\\"", "\"", $jsonString);
    $currentListing = $_REQUEST['selected'];
    //$currentListing = str_replace("\\\"", "\"", $currentListing);

    
?>

<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<script type="text/javascript" language="javascript src="json2.js"></script>
<script language="javascript" type="text/javascript" charset="utf-8">
    //Global variables
    //var resultString = <?php json_decode($jsonString); ?>;
    //var selectedString = "<?php echo $current; ?>";
    var resultJSON = JSON.parse("<?php echo $jsonString; ?>");
    var selected = JSON.parse("<?php echo $currentListing; ?>");
</script>

<?php
    $page = file_get_contents('./comparator.html', false);
    echo $page;
?>