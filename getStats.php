<?php
	require_once 'api/parser.php';
  include_once 'api/playerinfo.php';

  $battletag = $_POST['battletag']; 
  $platform = $_POST['platform'];
  $region = $_POST['region'];

  $B_BASE_URL = "https://playoverwatch.com/en-us/";
  $B_PAGE_URL = $B_BASE_URL . "career/{$platform}{$region}/{$battletag}";
  $B_HEROES_URL = $B_BASE_URL . "heroes";
  $B_HERO_URL = $B_HEROES_URL . "/{hero}";

  $AVAILABLE_REGIONS = ["/eu", "/us", "/kr"];

	if (isset($_POST['battletag'])) {
      

      $stats['stats']['competitive'] = ow_parse_stats($B_PAGE_URL, "competitive", $battletag);
      //include 'stats.php';

      header('Content-Type: application/json');
      echo json_encode($stats);

      //echo '<pre>' . var_export($stats, true) . '</pre>';
  }

?>