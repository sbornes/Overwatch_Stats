<?php 

require 'api/sql_config.php'; 
$battletag = $_POST['battletag']; 
$timezone = $_POST['tz']; 


$overall_stats = $conn->query("SELECT * FROM player_info_overall_stats WHERE battletag = '{$battletag}'")->fetch_object();
$game_stats = $conn->query("SELECT * FROM player_info_game_stats WHERE battletag = '{$battletag}'")->fetch_object();

$date = new DateTime($overall_stats->last_update);
$date->setTimezone(new DateTimeZone($timezone));

if($_POST['first_time'] == "true") {
	echo '
		<div class="alert alert-info alert-dismissable">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
		    <strong>Info!</strong> Thanks for visiting us for the first time! Vist us more and watch your stats grow!.
		</div>
	';
}
?>




<div class='player-card'>
	<div class="row no-pad">
	  <div class="col-md-6">
	  	<div class='main-info-box'>
	  		
	  		<div class="player-info">
	  			<img src="<?php echo $overall_stats->avatar ?>" >
	  			<div class="player-name"><?php echo str_replace("-","#", $battletag) ?></div>
	  			<div class="player-update">
	  				<button id="update-btn" value="<?php $battletag ?>" type="button" class="btn btn-primary update">Update</button>
	  				<h4 class="last-update">Last Updated: <br>
	  					<?php echo $date->format('Y-m-d H:i:s'); ?>	
	  				</h4>
	  			</div>
	  			
	  		</div>
	  	</div>
	  </div>
	  <div class="col-md-6 fix">
	 	 <div class='competitive-info'>

			<div class="player-level">
  				<div class="player-level-icon" style="background-image: url('<?php echo $overall_stats->level_tier ?>')" >
  					
  					<div class="player-level-center"><?php echo $overall_stats->level ?></div>

  					<div class="player-level-stars" style="background-image: url('<?php echo $overall_stats->level_stars ?>')" ></div>

  				</div>

  				<?php if(isset($overall_stats->rank_icon)) include('stats_competitive_rank.php'); ?>

  				
  			</div>



	 	 </div>
	  </div>
	</div>
</div>

<div style="clear: both;"></div> 

<script src="js/main.js"></script>