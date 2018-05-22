<!-- http://eriku.github.io/horizontal-chart/ -->

<?php include 'api/playerinfo.php'; ?>
<?php include 'api/sql_config.php'; ?>
<section>
	<h1 class="top_heroes">Top Heroes</h1>

	<ul class="chart">
		<?php 
			$sql = "SELECT reaper, tracer, mercy, hanzo, torbjorn, reinhardt, pharah, winston, 
	    					widowmaker, bastion, symmetra, zenyatta, genji, roadhog, mccree, junkrat, 
	    					zarya, soldier76, lucio, dva, mei, ana, sombra, orisa, doomfist, moira 
	    			FROM competitive_hero_playtime WHERE battletag = '{$battletag}'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
    			/*while($row = mysqli_fetch_assoc($result))
				{
				    foreach($row as $key => $value)
				    {
				        echo "$key=$value <br>";
				    }
				}*/

				/* sorting from https://stackoverflow.com/questions/5497482/how-to-sort-an-associative-array-in-php */
				$row=mysqli_fetch_assoc($result);
				arsort($row);
				$keys = array_keys ($row);

				for ($i = 0; $i < count($row); $i++) {
					
					if($i == 3)
						echo '<div id="playtime-collapse" class="collapse">';

					echo '
					<div class="bar_container">
					<img class="hero_icon" src="https://blzgdapipro-a.akamaihd.net/game/heroes/small/'.$hero_data_div_ids[$keys[$i]].'.png">
					<li>
					<div class="bar_text">
						<span class="bar '.$keys[$i].'" data-number="'.$row[$keys[$i]].'"></span>
						<span class="number">'.ucfirst($keys[$i]).'</span>
						<span class="playtime">'.convertTime($row[$keys[$i]]).'</span>
					</div>
					</li>
					</div>';
				}
				echo '</div>';
			}
		?>
		<div style="text-align: center;">
			<button id="collapse-btn" type="button" class="btn btn-success" data-toggle="collapse" data-target="#playtime-collapse" style="margin-top: 20px;">
		      <span class="glyphicon glyphicon-collapse-down"></span> See all heroes 
		    </button>
		</div>

	</ul>
</section>

<script src="js/jquery.horizBarChart.min.js"></script>
<script> 

$('.chart').horizBarChart(); 

</script>

<script>
$(document).ready(function(){
  $("#playtime-collapse").on("hide.bs.collapse", function(){
    $("#collapse-btn").html('<span class="glyphicon glyphicon-collapse-down"></span> See all heroes');
  });
  $("#playtime-collapse").on("show.bs.collapse", function(){
    $("#collapse-btn").html('<span class="glyphicon glyphicon-collapse-up"></span> Collapse List');
  });



});
</script>

<?php





function convertTime($dec)
{
    // start by converting to seconds
    $seconds = ($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    if($hours == 0 && $minutes == 0 && $seconds == 0)
    	return "--";

    if($hours > 0)
    	return floor($hours).' '.($hours == 1 ? 'hour' : 'hours');

    if($minutes > 0)
    	return floor($minutes).' '.($hours == 1 ? 'minute' : 'minutes');

    if($seconds > 0)
    	return floor($seconds).' '.($hours == 1 ? 'second' : 'seconds');

    return lz($hours).":".lz($minutes).":".lz($seconds);
}

// lz = leading zero
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}
?>