<?php
	require_once 'api/parser.php';
	include 'api/playerinfo.php';

	$battletag = $_POST['battletag']; 
	$platform = $_POST['platform'];
	$region = $_POST['region'];

	$B_BASE_URL = "https://playoverwatch.com/en-us/";
	$B_PAGE_URL = $B_BASE_URL . "career/{$platform}{$region}/{$battletag}";
	$B_HEROES_URL = $B_BASE_URL . "heroes";
	$B_HERO_URL = $B_HEROES_URL . "/{hero}";

	$AVAILABLE_REGIONS = ["/eu", "/us", "/kr"];


  	if (isset($_POST['battletag'])) {
  		
        $stats['heroes']['playtime']['competitive'] = ow_parse_all_heroes($B_PAGE_URL, "competitive", $battletag);
        include 'playtime.php';
        //echo '<pre>' . var_export($stats, true) . '</pre>';
    }

?>