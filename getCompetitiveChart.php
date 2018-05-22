<?php

require 'api/sql_config.php'; 
require "debug/ChromePhp.php";

$battletag = $_POST['battletag']; 

$json = [];

if ($result = $conn->query("SELECT * FROM competitive_history WHERE battletag = '{$battletag}'")) {

    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
       // printf ("%s (%s)\n", $row["Name"], $row["CountryCode"]);
    	$json[] = $row;
    }

    /* free result set */
    $result->free();
}

ChromePhp::log($json);

echo json_encode($json);

?>