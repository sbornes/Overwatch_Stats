<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "overwatch_stats";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

/*
	CREATE DATABASE IF NOT EXISTS overwatch_stats;

	CREATE TABLE IF NOT EXISTS player_info_overall_stats (
		battletag VARCHAR(32) PRIMARY KEY,
		level INT,
		level_tier VARCHAR(128),
		level_stars VARCHAR(128),
		rank INT,
		rank_icon VARCHAR(128),
		rank_name VARCHAR(16),
		avatar VARCHAR(128),
		win_rate FLOAT(2),
		last_update DATETIME
	);

	CREATE TABLE IF NOT EXISTS player_info_game_stats (
		battletag VARCHAR(32) PRIMARY KEY,
		melee_final_blows INT DEFAULT NULL,
        solo_kills INT DEFAULT NULL,
        objective_kills INT DEFAULT NULL,
        final_blows INT DEFAULT NULL,
        damage_done INT DEFAULT NULL,
        eliminations INT DEFAULT NULL,
        environmental_kills INT DEFAULT NULL,
        multikills INT DEFAULT NULL,
        healing_done INT DEFAULT NULL,
        recon_assists INT DEFAULT NULL,
        teleporter_pads_destroyed INT DEFAULT NULL,
        eliminations_most_in_game INT DEFAULT NULL,
        final_blows_most_in_game INT DEFAULT NULL,
        damage_done_most_in_game INT DEFAULT NULL,
        healing_done_most_in_game INT DEFAULT NULL,
        defensive_assists_most_in_game INT DEFAULT NULL,
        offensive_assists_most_in_game INT DEFAULT NULL,
        objective_kills_most_in_game INT DEFAULT NULL,
        objective_time_most_in_game TIME DEFAULT NULL, 
        multikill_best INT DEFAULT NULL,
        solo_kills_most_in_game INT DEFAULT NULL,
        time_spent_on_fire_most_in_game TIME DEFAULT NULL,
        melee_final_blows_average FLOAT DEFAULT NULL,
        time_spent_on_fire_average TIME DEFAULT NULL,
        solo_kills_average FLOAT DEFAULT NULL,
        objective_time_average TIME DEFAULT NULL,
        objective_kills_average FLOAT DEFAULT NULL,
        healing_done_average INT DEFAULT NULL,
        final_blows_average FLOAT DEFAULT NULL,
        deaths_average FLOAT DEFAULT NULL,
        damage_done_average INT DEFAULT NULL,
        eliminations_average FLOAT DEFAULT NULL,
        deaths INT DEFAULT NULL,
        environmental_deaths INT DEFAULT NULL,
        cards INT DEFAULT NULL,
        medals INT,
        medals_gold INT DEFAULT NULL,
        medals_silver INT DEFAULT NULL,
        medals_bronze INT DEFAULT NULL,
        games_played INT DEFAULT NULL,
        games_won INT DEFAULT NULL,
        time_spent_on_fire TIME DEFAULT NULL,
        objective_time TIME DEFAULT NULL, 
        time_played INT DEFAULT NULL,
        melee_final_blows_most_in_game INT DEFAULT NULL,
        shield_generator_destroyed_most_in_game INT DEFAULT NULL,
        turrets_destroyed_most_in_game INT DEFAULT NULL,
        environmental_kills_most_in_game INT DEFAULT NULL,
        teleporter_pads_destroyed_most_in_game INT DEFAULT NULL,
        kill_streak_best INT DEFAULT NULL,
        shield_generator_destroyed INT DEFAULT NULL,
        turrets_destroyed INT DEFAULT NULL,
        games_tied INT DEFAULT NULL,
        games_lost INT DEFAULT NULL,
        recon_assists_most_in_game INT DEFAULT NULL,
        offensive_assists INT DEFAULT NULL,
        defensive_assists INT DEFAULT NULL,
        last_update DATETIME DEFAULT NULL
	);

	CREATE TABLE IF NOT EXISTS competitive_history (
		battletag VARCHAR(32), 
		rank_date DATE,
		wins INT,
		losses INT,
		ties INT,
		rank INT,
        rank_diff INT,
		PRIMARY KEY (battletag, rank_date)
	);

	CREATE TABLE IF NOT EXISTS competitive_hero_playtime (
		battletag VARCHAR(32) PRIMARY KEY, 
		reaper FLOAT,
		tracer FLOAT,
		mercy FLOAT,
		hanzo FLOAT,
		torbjorn FLOAT,
		reinhardt FLOAT,
		pharah FLOAT,
		winston FLOAT,
		widowmaker FLOAT,
		bastion FLOAT,
		symmetra FLOAT,
		zenyatta FLOAT,
		genji FLOAT,
		roadhog FLOAT,
		mccree FLOAT,
		junkrat FLOAT,
		zarya FLOAT,
		soldier76 FLOAT,
		lucio FLOAT,
		dva FLOAT,
		mei FLOAT,
		ana FLOAT,
		sombra FLOAT,
		orisa FLOAT
	);

	1. single table - compettive-stats 

*/


?>