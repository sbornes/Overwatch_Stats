<div class="competitive-rank">
	<img src="<?php echo $overall_stats->rank_icon ?>" >
	<div class="rank-info">
		<div class="rank-info-rank">
			<div class="rank-info-rank-name"> <?php echo $overall_stats->rank_name ?> </div>
			<div class="rank-info-rank-elo"> <?php echo $overall_stats->rank ?> </div>
			
			<span class="rank-info-rank-stats">

				<div class="rank-info-rank-winrate">
					<?php echo $overall_stats->win_rate ?>% WR
				</div>

				<span style="color: #8fbc8f">
					<?php echo $game_stats->games_won ?>
				</span>
				<span style="color: #f1f1f1">-</span>
				<span style="color: #f08080">
					<?php echo $game_stats->games_lost ?>
				</span>
				<span style="color: #f1f1f1">-</span>
				<span style="color: #9daac8">
					<?php echo $game_stats->games_tied ?>
				</span>
			</span>		
		</div>
	</div>
</div>