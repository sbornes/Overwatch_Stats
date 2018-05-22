<?php
ini_set('memory_limit', '1024M');
require "simple_html_dom.php";

require "/../debug/ChromePhp.php";



function ow_parse_stats($url, $mode='quickplay', $battletag) {

	require "playerinfo.php";
	require "sql_config.php";
	
	$feedUrl = $url;
	//Create a DOM object
	$html = new simple_html_dom();
	// Load HTML from a string
	if (@$html->load_file($feedUrl) === false) {
		$response['response']['status'] = "error";
		$response['response']['message'] = "Battlenet ID does not exist.";
	    return $response;
	}

	$data = $html->find(".//div[@id='{$mode}']", 0);
	$data = @$data->find(".//ul/h6[@class='u-align-center']", 0)->plaintext;
	if(trim($data) === "We don't have any data for this account in this mode yet.") {
		$response['response']['status'] = "error";
		$response['response']['message'] = trim($data);
		return $response;
	}


	if($html->find(".//div[@class='competitive-rank']", 0) == null) {
		$response['response']['status'] = "error";
		$response['response']['message'] = "This account does not have any competitive data.";
		return $response;
	}

	$response['response']['first_time'] = false;

	// deafult stats
	$stats['game_stats']['melee_final_blows'] = 0;
	$stats['game_stats']['solo_kills'] = 0;
	$stats['game_stats']['objective_kills'] = 0;
	$stats['game_stats']['final_blows'] = 0;
	$stats['game_stats']['damage_done'] = 0;
	$stats['game_stats']['eliminations'] = 0;
	$stats['game_stats']['environmental_kills'] = 0;
	$stats['game_stats']['multikills'] = 0;
	$stats['game_stats']['healing_done'] = 0;
	$stats['game_stats']['recon_assists'] = 0;
	$stats['game_stats']['teleporter_pads_destroyed'] = 0;
	$stats['game_stats']['eliminations_most_in_game'] = 0;
	$stats['game_stats']['final_blows_most_in_game'] = 0;
	$stats['game_stats']['damage_done_most_in_game'] = 0;
	$stats['game_stats']['healing_done_most_in_game'] = 0;
	$stats['game_stats']['defensive_assists_most_in_game'] = 0;
	$stats['game_stats']['offensive_assists_most_in_game'] = 0;
	$stats['game_stats']['objective_kills_most_in_game'] = 0;
	$stats['game_stats']['objective_time_most_in_game'] = 0; 
	$stats['game_stats']['multikill_best'] = 0;
	$stats['game_stats']['solo_kills_most_in_game'] = 0;
	$stats['game_stats']['time_spent_on_fire_most_in_game'] = 0;
	$stats['game_stats']['melee_final_blows_average'] = 0;
	$stats['game_stats']['time_spent_on_fire_average'] = 0;
	$stats['game_stats']['solo_kills_average'] = 0;
	$stats['game_stats']['objective_time_average'] = 0;
	$stats['game_stats']['objective_kills_average'] = 0;
	$stats['game_stats']['healing_done_average'] = 0;
	$stats['game_stats']['final_blows_average'] = 0;
	$stats['game_stats']['deaths_average'] = 0;
	$stats['game_stats']['damage_done_average'] = 0;
	$stats['game_stats']['eliminations_average'] = 0;
	$stats['game_stats']['deaths'] = 0;
	$stats['game_stats']['environmental_deaths'] = 0;
	$stats['game_stats']['cards'] = 0;
	$stats['game_stats']['medals'] = 0;
	$stats['game_stats']['medals_gold'] = 0;
	$stats['game_stats']['medals_silver'] = 0;
	$stats['game_stats']['medals_bronze'] = 0;
	$stats['game_stats']['games_played'] = 0;
	$stats['game_stats']['games_won'] = 0;
	$stats['game_stats']['time_spent_on_fire'] = 0;
	$stats['game_stats']['objective_time'] = 0; 
	$stats['game_stats']['time_played'] = 0;
	$stats['game_stats']['melee_final_blows_most_in_game'] = 0;
	$stats['game_stats']['shield_generator_destroyed_most_in_game'] = 0;
	$stats['game_stats']['turrets_destroyed_most_in_game'] = 0;
	$stats['game_stats']['environmental_kills_most_in_game'] = 0;
	$stats['game_stats']['teleporter_pads_destroyed_most_in_game'] = 0;
	$stats['game_stats']['kill_streak_best'] = 0;
	$stats['game_stats']['shield_generator_destroyed'] = 0;
	$stats['game_stats']['turrets_destroyed'] = 0;
	$stats['game_stats']['games_tied'] = 0;
	$stats['game_stats']['games_lost'] = 0;
	$stats['game_stats']['recon_assists_most_in_game'] = 0;
	$stats['game_stats']['offensive_assists'] = 0;
	$stats['game_stats']['defensive_assists'] = 0;

	$mast_head = $html->find(".//div[@class='masthead-player']", 0);
	$prestige = $html->find(".//div[@class='player-level']", 0);

	/* Get player competetive rank */
	$rank = $html->find(".//div[@class='competitive-rank']", 0);
	$elo = $rank->find(".//div", 0)->plaintext;

	$stats['overall_stats']['rank'] = $elo;
	ChromePhp::log($stats['overall_stats']['rank']);

	/* Get player competetive rank icon */
	$elo_icon = $rank->getElementByTagName("img")->getAttribute("src");

	$stats['overall_stats']['rank_icon'] = $elo_icon;
	ChromePhp::log($stats['overall_stats']['rank_icon']);
	
	
	/* Get player comptetive rank name */
	$i = 0;
	if($elo_icon) {
		$rank_key = array_keys($tier_data_img_src);
		$rank_value = array_values($tier_data_img_src);
		for ($i; $i < count($rank_key); $i++) {
			if (strpos($elo_icon, $rank_key[$i]) !== false) {
			    break;
			}
		}
	}

	$stats['overall_stats']['rank_name'] = $rank_value[$i];
	/* Get player level */

	$level = $prestige->find(".//div", 0)->plaintext;

	$stats['overall_stats']['level'] = $level;
	/* Get player level rank */

	$tier = $prestige->getAttribute("style");

	if(preg_match('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $tier, $matches)) {
	    $image_url = $matches[0];
	}

	$stats['overall_stats']['level_tier'] = $image_url;

	/* Get player level stars */

	$tier_stars = $html->find(".//div[@class='player-rank']", 0)->getAttribute("style");
	if(preg_match('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $tier_stars, $matches)) {
	    $image_url = $matches[0];
	}

	$stats['overall_stats']['level_stars'] = $image_url;


	/* Get player avatar */

	$stats['overall_stats']['avatar'] = $mast_head->find(".//img[@class='player-portrait']", 0)->getAttribute("src");

	$stat_groups = [];
	if($mode=='competitive') {
        $stat_groups = $html->find(".//div[@id='competitive']", 0)->find("//div[@data-category-id='0x02E00000FFFFFFFF']", 0);
	} elseif ($mode=='quickplay') {
		$stat_groups = $html->find(".//div[@id='quickplay']", 0)->find("//div[@data-category-id='0x02E00000FFFFFFFF']", 0);
	}

	foreach($stat_groups->find('tr') as $row) {
		if($row->find('td',0) && $row->find('td',1))
		{
		    $td_name = $row->find('td',0)->plaintext;
		    $td_value = $row->find('td',1)->plaintext;
		
		    $td_name = strtolower($td_name);
		    $td_value = strtolower($td_value);

		    $td_name = str_replace(' - ', '_', $td_name);
		    $td_name = str_replace(' ', '_', $td_name);

		    if(strpos($td_value, ",") !== false)
				$td_value = str_replace(',', '', $td_value);	    

		    $stats['game_stats'][$td_name] = $td_value;
		}
	}

	/* Manual Stats */

	/*$damage_doneV = intval(str_replace(",","",$stats['game_stats']['damage_done']));
	$damage_done_averageV = intval(str_replace(",","",$stats['game_stats']['damage_done_average']));

	$games_played = floor($damage_doneV / $damage_done_averageV);


	$stats['overall_stats']['wins'] = $stats['game_stats']['games_won'];
	$stats['overall_stats']['losses'] = $games_played - $stats['game_stats']['games_won'];
	$stats['overall_stats']['games_played'] = $games_played;*/

	$wr = round(($stats['game_stats']['games_won'] / ($stats['game_stats']['games_played'] - $stats['game_stats']['games_tied'])) * 100, 2);
	$stats['overall_stats']['win_rate'] = $wr;

	// convert playtime into hours
	$stats['game_stats']['time_played'] = extractTime($stats['game_stats']['time_played']);

	$html->clear();
	unset($html);

	/* store data into mySQL */
	$sql = "SELECT * FROM player_info_overall_stats WHERE battletag = '{$battletag}'";

	$temp = $stats['overall_stats'];
	$temp2 = $stats['game_stats'];

	ChromePhp::log($temp);
	ChromePhp::log($temp2);

	$date = date("Y-m-d H:i:s");

	if ($conn->query($sql)->num_rows > 0) {

		/* check and update competitive history */
		$date = date("Y-m-d");

		$sql = "SELECT rank_date, rank FROM competitive_history WHERE battletag = '{$battletag}' ORDER BY rank_date DESC LIMIT 1";
		/*$result = $conn->query($sql);
		if(empty($result)) {
			ChromePhp::log($conn->error);
			echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
		}
		else {
			$sql2 = "SELECT games_won, games_lost, games_tied FROM player_info_game_stats WHERE battletag = '{$battletag}'";
			$result2 = $conn->query($sql2);
			if(empty($result2)) {
				//ChromePhp::log($conn->error);
				echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
			}else {	
				$row2 = $result2->fetch_object();

				$games_won_old = $row2->games_won;
				$games_lost_old = $row2->games_lost;
				$games_tied_old = $row2->games_tied;

				$games_won_new = $stats['game_stats']['games_won'] - $games_won_old;
				$games_lost_new = $stats['game_stats']['games_lost'] - $games_lost_old;
				$games_tied_new = $stats['game_stats']['games_tied'] - $games_tied_old;

				
			    // output data of each row
			    $row = $result->fetch_object();

			    $tempDate = $row->rank_date;
			    $tempRank = $row->rank;

			    $date = date("Y-m-d");

			    if($tempDate == $date) {
			    	//ChromePhp::log("TEMP DATE == DATE, UPDATE | tempdate: ". $tempDate. "| date: ". $date);
			    	if($tempRank != $temp['rank']) {
			    		$sql = "UPDATE competitive_history SET 	wins = wins + {$games_won_new},
			    												losses = losses + {$games_lost_new},
			    												ties =  ties + {$games_tied_new},
			    												rank = {$stats['overall_stats']['rank']}
			    												WHERE battletag = '{$battletag}' AND rank_date = '{$date}'";

			    		if($conn->query($sql) == false) {
							ChromePhp::log($conn->error);
							echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
						}
			    	}
			    } else {
			    	ChromePhp::log("TEMP DATE != DATE, INSERT | tempdate: ". $tempDate. "| date: ". $date);
			    	$sql = "INSERT INTO competitive_history (battletag, rank_date, wins, losses, ties, rank)
			    			VALUES ('{$battletag}', '{$date}', {$games_won_new}, {$games_lost_new}, {$games_tied_new}, {$stats['overall_stats']['rank']})";
			    	
			    	if($conn->query($sql) == false) {
						ChromePhp::log($conn->error);
						echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
					}
			    } 
			}
		}*/


		if($result = $conn->query($sql)){
			if($result->num_rows > 0) {
				$row = $result->fetch_object();

			    $tempDate = $row->rank_date;
			    $tempRank = $row->rank;

			    $sql2 = "SELECT games_won, games_lost, games_tied FROM player_info_game_stats WHERE battletag = '{$battletag}'";
				$result2 = $conn->query($sql2);

				$row2 = $result2->fetch_object();

				$games_won_old = $row2->games_won;
				$games_lost_old = $row2->games_lost;
				$games_tied_old = $row2->games_tied;

				$games_won_new = $stats['game_stats']['games_won'] - $games_won_old;
				$games_lost_new = $stats['game_stats']['games_lost'] - $games_lost_old;
				$games_tied_new = $stats['game_stats']['games_tied'] - $games_tied_old;
				$rank_diff = $temp['rank'] - $tempRank;
				
				
		    	if($tempDate == $date) {
		    		ChromePhp::log("TEMP DATE == DATE, UPDATE | tempdate: ". $tempDate. "| date: ". $date);
		    		$sql = "UPDATE competitive_history SET 	wins = wins + {$games_won_new},
		    												losses = losses + {$games_lost_new},
		    												ties =  ties + {$games_tied_new},
		    												rank = {$stats['overall_stats']['rank']},
		    												rank_diff = rank_diff + {$rank_diff}
		    												WHERE battletag = '{$battletag}' AND rank_date = '{$date}'";

		    		if($conn->query($sql) == false) {
						ChromePhp::log($conn->error);
						echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
					}
		    	} else {
		    		ChromePhp::log("TEMP DATE != DATE, INSERT | tempdate: ". $tempDate. "| date: ". $date);
			    	$sql = "INSERT INTO competitive_history (battletag, rank_date, wins, losses, ties, rank, rank_diff)
			    			VALUES ('{$battletag}', '{$date}', {$games_won_new}, {$games_lost_new}, {$games_tied_new}, {$stats['overall_stats']['rank']}, {$rank_diff})";
			    	
			    	if($conn->query($sql) == false) {
						ChromePhp::log($conn->error);
						echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
					}
		    	}
			    

			    /*if($tempRank != $temp['rank']) {
			    	if($tempDate == $date) {
			    		ChromePhp::log("TEMP DATE == DATE, UPDATE | tempdate: ". $tempDate. "| date: ". $date);
			    		$sql = "UPDATE competitive_history SET 	wins = wins + {$games_won_new},
			    												losses = losses + {$games_lost_new},
			    												ties =  ties + {$games_tied_new},
			    												rank = {$stats['overall_stats']['rank']},
			    												rank_diff = rank_diff + {$rank_diff}
			    												WHERE battletag = '{$battletag}' AND rank_date = '{$date}'";

			    		if($conn->query($sql) == false) {
							ChromePhp::log($conn->error);
							echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
						}
			    	} else {
			    		ChromePhp::log("TEMP DATE != DATE, INSERT | tempdate: ". $tempDate. "| date: ". $date);
				    	$sql = "INSERT INTO competitive_history (battletag, rank_date, wins, losses, ties, rank, rank_diff)
				    			VALUES ('{$battletag}', '{$date}', {$games_won_new}, {$games_lost_new}, {$games_tied_new}, {$stats['overall_stats']['rank']}, {$rank_diff})";
				    	
				    	if($conn->query($sql) == false) {
							ChromePhp::log($conn->error);
							echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
						}
			    	}
			    } else {
			    	if($tempDate != $date) {
			    		$sql = "INSERT INTO competitive_history (battletag, rank_date, wins, losses, ties, rank, rank_diff)
				    			VALUES ('{$battletag}', '{$date}', 0, 0, 0, {$stats['overall_stats']['rank']}, 0)";
				    	
				    	if($conn->query($sql) == false) {
							ChromePhp::log($conn->error);
							echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
						}
			    	}
			    }*/
			}
		}
		

		/* Update overall_stats */
		
		$date = date("Y-m-d H:i:s");

		$sql = "UPDATE player_info_overall_stats SET 	level 										= {$temp['level']},
														level_tier 									= '{$temp['level_tier']}',
														level_stars									= '{$temp['level_stars']}',
														rank 										= {$temp['rank']},
														rank_icon 									= '{$temp['rank_icon']}',
														rank_name 									= '{$temp['rank_name']}',
														avatar 										= '{$temp['avatar']}',
														win_rate 									= {$temp['win_rate']},
														last_update 								= '{$date}'
														WHERE battletag 							= '{$battletag}'";
	    if($conn->query($sql) == false) {
			ChromePhp::log($conn->error);
			echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
		}

		/* Update game_stats */

		$sql  = "UPDATE player_info_game_stats SET      melee_final_blows 							= {$temp2['melee_final_blows']},
		                                                solo_kills 									= {$temp2['solo_kills']},
		                                                objective_kills 							= {$temp2['objective_kills']},
		                                                final_blows 								= {$temp2['final_blows']},
		                                                damage_done 								= {$temp2['damage_done']},
		                                                eliminations 								= {$temp2['eliminations']},
		                                                environmental_kills 						= {$temp2['environmental_kills']},
		                                                multikills 									= {$temp2['multikills']},
		                                                healing_done 								= {$temp2['healing_done']},
		                                                recon_assists 								= {$temp2['recon_assists']},
		                                                teleporter_pads_destroyed 					= {$temp2['teleporter_pads_destroyed']},
		                                                eliminations_most_in_game 					= {$temp2['eliminations_most_in_game']},
		                                                final_blows_most_in_game 					= {$temp2['final_blows_most_in_game']},
		                                                damage_done_most_in_game 					= {$temp2['damage_done_most_in_game']},
		                                                healing_done_most_in_game 					= {$temp2['healing_done_most_in_game']},
		                                                defensive_assists_most_in_game 				= {$temp2['defensive_assists_most_in_game']},
		                                                offensive_assists_most_in_game 				= {$temp2['offensive_assists_most_in_game']},
		                                                objective_kills_most_in_game 				= {$temp2['objective_kills_most_in_game']},
		                                                objective_time_most_in_game 				= '{$temp2['objective_time_most_in_game']}', 
		                                                multikill_best 								= {$temp2['multikill_best']},
		                                                solo_kills_most_in_game 					= {$temp2['solo_kills_most_in_game']},
		                                                time_spent_on_fire_most_in_game 			= '{$temp2['time_spent_on_fire_most_in_game']}',
		                                                melee_final_blows_average 					= {$temp2['melee_final_blows_average']},
		                                                time_spent_on_fire_average 					= '{$temp2['time_spent_on_fire_average']}',
		                                                solo_kills_average 							= {$temp2['solo_kills_average']},
		                                                objective_time_average						= '{$temp2['objective_time_average']}',
		                                                objective_kills_average 					= {$temp2['objective_kills_average']},
		                                                healing_done_average 						= {$temp2['healing_done_average']},
		                                                final_blows_average 						= {$temp2['final_blows_average']},
		                                                deaths_average 								= {$temp2['deaths_average']},
		                                                damage_done_average 						= {$temp2['damage_done_average']},
		                                                eliminations_average 						= {$temp2['eliminations_average']},
		                                                deaths 										= {$temp2['deaths']},
		                                                environmental_deaths 						= {$temp2['environmental_deaths']},
		                                                cards 										= {$temp2['cards']},
		                                                medals 										= {$temp2['medals']},
		                                                medals_gold 								= {$temp2['medals_gold']},
		                                                medals_silver 								= {$temp2['medals_silver']},
		                                                medals_bronze 								= {$temp2['medals_bronze']},
		                                                games_played 								= {$temp2['games_played']},
		                                                games_won 									= {$temp2['games_won']},
		                                                time_spent_on_fire 							= '{$temp2['time_spent_on_fire']}',
		                                                objective_time 								= '{$temp2['objective_time']}', 
		                                                time_played 								= '{$temp2['time_played']}',
		                                                melee_final_blows_most_in_game 				= {$temp2['melee_final_blows_most_in_game']},
		                                                shield_generator_destroyed_most_in_game 	= {$temp2['shield_generator_destroyed_most_in_game']},
		                                                turrets_destroyed_most_in_game 				= {$temp2['turrets_destroyed_most_in_game']},
		                                                environmental_kills_most_in_game 			= {$temp2['environmental_kills_most_in_game']},
		                                                teleporter_pads_destroyed_most_in_game 		= {$temp2['teleporter_pads_destroyed_most_in_game']},
		                                                kill_streak_best 							= {$temp2['kill_streak_best']},
		                                                shield_generator_destroyed 					= {$temp2['shield_generator_destroyed']},
		                                                turrets_destroyed 							= {$temp2['turrets_destroyed']},
		                                                games_tied 									= {$temp2['games_tied']},
		                                                games_lost 									= {$temp2['games_lost']},
		                                                recon_assists_most_in_game 					= {$temp2['recon_assists_most_in_game']},
		                                                offensive_assists 							= {$temp2['offensive_assists']},
		                                                defensive_assists 							= {$temp2['defensive_assists']},
		                                                last_update 								= '{$date}'
		                                                WHERE battletag 							= '{$battletag}'";

		if($conn->query($sql) == false) {
			ChromePhp::log($conn->error);
			echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
		}
	} else {
		/*				Overall Stats					*/

	    $sql = "INSERT INTO player_info_overall_stats (battletag, level, level_tier, level_stars, rank, rank_icon, rank_name, avatar, win_rate, last_update)
				VALUES ('{$battletag}', {$temp['level']}, '{$temp['level_tier']}', 
						'{$temp['level_stars']}', {$temp['rank']}, '{$temp['rank_icon']}', 
						'{$temp['rank_name']}', '{$temp['avatar']}', '{$temp['win_rate']}', '{$date}')";

		if($conn->query($sql) == false) {
			ChromePhp::log($conn->error);
			echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
		}

		/*				Game Stats					*/

		$sql = "INSERT INTO player_info_game_stats (battletag,
		melee_final_blows,
        solo_kills,
        objective_kills,
        final_blows,
        damage_done,
        eliminations,
        environmental_kills,
        multikills,
        healing_done,
        recon_assists,
        teleporter_pads_destroyed,
        eliminations_most_in_game,
        final_blows_most_in_game,
        damage_done_most_in_game,
        healing_done_most_in_game,
        defensive_assists_most_in_game,
        offensive_assists_most_in_game,
        objective_kills_most_in_game,
        objective_time_most_in_game, 
        multikill_best,
        solo_kills_most_in_game,
        time_spent_on_fire_most_in_game,
        melee_final_blows_average,
        time_spent_on_fire_average,
        solo_kills_average,
        objective_time_average,
        objective_kills_average,
        healing_done_average,
        final_blows_average,
        deaths_average,
        damage_done_average,
        eliminations_average,
        deaths,
        environmental_deaths,
        cards,
        medals,
        medals_gold,
        medals_silver,
        medals_bronze,
        games_played,
        games_won,
        time_spent_on_fire,
        objective_time, 
        time_played,
        melee_final_blows_most_in_game,
        shield_generator_destroyed_most_in_game,
        turrets_destroyed_most_in_game,
        environmental_kills_most_in_game,
        teleporter_pads_destroyed_most_in_game,
        kill_streak_best,
        shield_generator_destroyed,
        turrets_destroyed,
        games_tied,
        games_lost,
        recon_assists_most_in_game,
        offensive_assists,
        defensive_assists,
        last_update )
				VALUES ('{$battletag}', 
						{$temp2['melee_final_blows']},
						{$temp2['solo_kills']},
						{$temp2['objective_kills']},
						{$temp2['final_blows']},
						{$temp2['damage_done']},
						{$temp2['eliminations']},
						{$temp2['environmental_kills']},
						{$temp2['multikills']},
						{$temp2['healing_done']},
						{$temp2['recon_assists']},
						{$temp2['teleporter_pads_destroyed']},
						{$temp2['eliminations_most_in_game']},
						{$temp2['final_blows_most_in_game']},
						{$temp2['damage_done_most_in_game']},
						{$temp2['healing_done_most_in_game']},
						{$temp2['defensive_assists_most_in_game']},
						{$temp2['offensive_assists_most_in_game']},
						{$temp2['objective_kills_most_in_game']},
						'{$temp2['objective_time_most_in_game']}', 
						{$temp2['multikill_best']},
						{$temp2['solo_kills_most_in_game']},
						'{$temp2['time_spent_on_fire_most_in_game']}',
						{$temp2['melee_final_blows_average']},
						'{$temp2['time_spent_on_fire_average']}',
						{$temp2['solo_kills_average']},
						'{$temp2['objective_time_average']}',
						{$temp2['objective_kills_average']},
						{$temp2['healing_done_average']},
						{$temp2['final_blows_average']},
						{$temp2['deaths_average']},
						{$temp2['damage_done_average']},
						{$temp2['eliminations_average']},
						{$temp2['deaths']},
						{$temp2['environmental_deaths']},
						{$temp2['cards']},
						{$temp2['medals']},
						{$temp2['medals_gold']},
						{$temp2['medals_silver']},
						{$temp2['medals_bronze']},
						{$temp2['games_played']},
						{$temp2['games_won']},
						'{$temp2['time_spent_on_fire']}',
						'{$temp2['objective_time']}', 
						'{$temp2['time_played']}',
						{$temp2['melee_final_blows_most_in_game']},
						{$temp2['shield_generator_destroyed_most_in_game']},
						{$temp2['turrets_destroyed_most_in_game']},
						{$temp2['environmental_kills_most_in_game']},
						{$temp2['teleporter_pads_destroyed_most_in_game']},
						{$temp2['kill_streak_best']},
						{$temp2['shield_generator_destroyed']},
						{$temp2['turrets_destroyed']},
						{$temp2['games_tied']},
						{$temp2['games_lost']},
						{$temp2['recon_assists_most_in_game']},
						{$temp2['offensive_assists']},
						{$temp2['defensive_assists']},
						'{$date}')";

		if($conn->query($sql) == false) {
			ChromePhp::log($conn->error);
			echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
		}

		/*				Competitive History					*/

		$date = date("Y-m-d");
		$sql = "INSERT INTO competitive_history (battletag, wins, losses, ties, rank, rank_date)
				VALUES ('{$battletag}', 0, 0, 0, {$temp['rank']}, '{$date}')";

		if($conn->query($sql) == false) {
			ChromePhp::log($conn->error);
			echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
		}
		
		$response['response']['first_time'] = true;
	}

	$response['response']['status'] = "success";
	$response['response']['message'] = "OK";

	return $response;
	//return $stats;
}

function ow_parse_all_heroes($url, $mode='quickplay', $battletag) {

	require "playerinfo.php";
	require "sql_config.php";

	$feedUrl = $url;
	//Create a DOM object
	$html = new simple_html_dom();
	// Load HTML from a string
	if (@$html->load_file($feedUrl) === false) {
	    return null;
	}

	if($mode == "competitive") {
		$data = $html->find(".//div[@data-mode='competitive']", 0);
	} else {
		$data = $html->find(".//div[@data-mode='quickplay']", 0);
	}

	$_hero_info = $data->find(".//div[@data-group-id='comparisons']", 0);
	

	foreach($_hero_info->find(".//div[@class='bar-text']") as $row) {
		$hero = $row->find(".//div[@class='title']", 0)->plaintext;
		$played = $row->find(".//div[@class='description']", 0)->plaintext;

		$played = strtolower($played);

		if($played === '--') {
			$played = 0;
		} else {
			$played = extractTime($played);
		}

		$hero = filter_var($hero, FILTER_SANITIZE_STRING);
		$hero = strtolower($hero);

		switch($hero) {
			case "soldier: 76":
				$hero = "soldier76";
				break;
			case strpos($hero, "torbj") !== false: //cheap fix
				$hero = "torbjorn";
				break;
			case strpos($hero, "cio") !== false:  //cheap fix
				$hero = "lucio";
				break;	
			case "d.va": 
				$hero = "dva";
				break;	

			default:
				break;
		}
	
		$stats[$hero] = $played;
	
	}

	$sql = "SELECT * FROM {$mode}_hero_playtime WHERE battletag = '{$battletag}'";

	if ($conn->query($sql)->num_rows > 0) {
		foreach($ow_heroes as $hero_name)
		{
			$sql = "UPDATE {$mode}_hero_playtime SET {$hero_name} = {$stats[$hero_name]} WHERE battletag = '{$battletag}'";

			if($conn->query($sql) == false)
				echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
		}
	} else {
	    $sql = "INSERT INTO {$mode}_hero_playtime (battletag, reaper, tracer, mercy, hanzo, torbjorn, reinhardt, pharah, winston, 
	    								widowmaker, bastion, symmetra, zenyatta, genji, roadhog, mccree, junkrat, 
	    								zarya, soldier76, lucio, dva, mei, ana, sombra, orisa)
				VALUES ( '{$battletag}', '{$stats['reaper']}', '{$stats['tracer']}', '{$stats['mercy']}', 
						'{$stats['hanzo']}', '{$stats['torbjorn']}', '{$stats['reinhardt']}', 
						'{$stats['pharah']}', '{$stats['winston']}', '{$stats['widowmaker']}', 
						'{$stats['bastion']}', '{$stats['symmetra']}', '{$stats['zenyatta']}',
						'{$stats['genji']}', '{$stats['roadhog']}', '{$stats['mccree']}',
						'{$stats['junkrat']}', '{$stats['zarya']}', '{$stats['soldier76']}',
						'{$stats['lucio']}', '{$stats['dva']}', '{$stats['mei']}',
						'{$stats['ana']}', '{$stats['sombra']}', '{$stats['orisa']}' )";

		if($conn->query($sql) == false)
			echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
	}

	$html->clear();
	unset($html);

	return $stats;
}

function ow_parse_hero_data($url, $mode='quickplay', $battletag) {

	require "playerinfo.php";

	$feedUrl = $url;
	//Create a DOM object
	$html = new simple_html_dom();
	// Load HTML from a string
	if (@$html->load_file($feedUrl) === false) {
	    return null;
	}
	$data = $html->find(".//div[@id='{$mode}']", 0);
	
	$test = @$data->find(".//ul/h6[@class='u-align-center']", 0)->plaintext;
	if(trim($test) === "We don't have any data for this account in this mode yet.") {
		$stats['response']['status'] = "error";
		$stats['response']['message'] = trim($data);
		return $stats;
	}

	$data = $data->find(".//section[@class='career-stats-section']", 0);
	
	foreach($hero_data_div_ids as $hero_name => $hero_value) {
		$stat_groups = null;

		foreach($data->find(".//div[@data-group-id='stats']") as $row) {
			$stat_groups = $row->parentNode()->find(".//div[@data-category-id='{$hero_value}']", 0);
			if($stat_groups != null) {
				break;
			}
		}

		if($stat_groups!=null) {
			$hbtitle = $stat_groups->find(".//span[@class='stat-title']");
			if ($hbtitle == null) {
				$hbtitle = $stat_groups->find(".//h5[@class='stat-title']");
			}
			foreach ($hbtitle as $title) {
				//echo $title->parent()->parent()->parent()->parent();
				$data_title = $title->plaintext;
				$data_title = trim($title);
				$data_title = strtolower($data_title);
				$data_title = str_replace(' ', '_', $data_title);
				
				foreach($title->parent()->parent()->parent()->parent()->find('.//tbody/tr') as $row) {
					if($row->find('td',0) && $row->find('td',1))
					{
					    $td_name = $row->find('td',0)->plaintext;
					    $td_value = $row->find('td',1)->plaintext;
					
					    $td_name = strtolower($td_name);
					    $td_value = strtolower($td_value);

					    $td_name = str_replace(' - ', '_', $td_name);
					    $td_name = str_replace(' ', '_', $td_name);
					    
					    $td_name = filter_var($td_name, FILTER_SANITIZE_STRING);

					    $stats['hero_stats'][$hero_name][$data_title][$td_name] = $td_value; 
					}
				}
			}
		}
	}
	//echo '<pre>' . var_export($stats, true) . '</pre>';

	$html->clear();
	unset($html);

	return $stats;
}

function extractTime($time) {
	if($time == '--')
		return 0;

	$get_float = int_or_string($time);
	if($get_float != $time)
		return $get_float;

	if(stripos(strtolower($time), 'hour') !== false) {
		$val = explode(" ", $time);
		$val = $val[0];
		$val = floatval($val);
		return $val;
	}

	if(stripos(strtolower($time), 'minute') !== false) {
		$val = explode(" ", $time);
		$val = $val[0];
		$val = floatval($val);
		$val = $val / 60;
		return $val;
	}

	if(stripos(strtolower($time), 'second') !== false) {
		$val = explode(" ", $time);
		$val = $val[0];
		$val = floatval($val);
		$val = ($val / 60 / 60);
		return $val;
	}

	if(stripos(strtolower($time), '%') !== false) {
		$val = explode(" ", $time);
		$val = $val[0];
		$val = floatval($val);
		$val = ($val / 100);
		return $val;
	}

	$_value = explode(':' , $time);

	if(count($_value) > 0) {
		if(count($_value) == 1) {
			$mins = $_value[0];
			$secs = $_value[1];

			$mins += $secs / 60;
			$hours = $mins / 60;

			return $hours;
		} elseif (count($_value) == 2) {
			$hours = $_value[0];
			$mins = $_value[1];
			$secs = $_value[2];

			$mins += ($secs / 60);
			$hours += ($mins / 60);
			return $hours;
		}
	}
	
	return $time;
}

function int_or_string($value) {
	$new_value = str_replace(',', '', $value);

	try {
		return floatval($new_value);
	} catch (Exception $e) {
		return $value;
	}
}

function get(&$value, $default = null)
{
    return isset($value) ? $value : $default;
}

?>