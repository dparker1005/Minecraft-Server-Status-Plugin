<?php
/**
 * @package minecraft-server-status
 * @version 1.1
 */
/*
Plugin Name: Minecraft Server Status
Description: Shows information about a minecraft server
Author: David Parker
*/
function mc_server_status($atts){

	global $wpdb;
	$wpdb->mc_settings = $wpdb->prefix . 'mc_settings';

	//grab the server name from the shortcode call
	$server = $atts['server'];
	
	$to_return = "";
	$to_return = $to_return."<link rel='stylesheet' type='text/css' href='../wp-content/plugins/minecraft-server-status/css/mc_frontend.css' />";
	
	//get server data from minecraft API call
	$raw_server_data = file_get_contents('https://mcapi.ca/query/'.$server.'/info');
	//decode server data with json
	$server_data = json_decode($raw_server_data, true);
	$to_return = $to_return."<div class='mc_server_div'>";
	$to_return = $to_return."<h3 class='mc_hostname'>".$server_data['hostname']."</h3>";
	
	//if server is online
	if($server_data['status'] == 1){
		$to_return = $to_return."<p class='mc_green'>Online</p>"; 
		
		$players_online = $server_data['players']['online'];
		$players_max = $server_data['players']['max'];
		
		$show_players_online = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='players_online'");
		$show_max_players = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='max_players'");
		
		//if show online players and max players
		if($show_players_online[0]->option_value==1 and $show_max_players[0]->option_value==1){
			//if server is full
			if($players_online >= $players_max){
				$to_return = $to_return."<p class='mc_red'> ";
			}
			//if server is almost full
			elseif($players_online >= $players_max*.9){
				$to_return = $to_return."<p class='mc_yellow'> ";
			}
			//if there is space on server
			else{
				$to_return = $to_return."<p class='mc_green'> ";
			}
	
			$to_return = $to_return.$players_online."/".$players_max." Players Online</p>";
		}
		//if show only online players
		elseif($show_players_online[0]->option_value==1){
			$to_return = $to_return."<p>".$players_online." Players Online</p>";
		}
		//if show only max players
		elseif($show_max_players[0]->option_value==1){
			$to_return = $to_return."<p>".$players_max." Player Maximum</p>";
		}
		$show_ping = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='server_ping'");
		//if show ping
		if($show_ping[0]->option_value==1){
			$to_return = $to_return."<p>Ping: ".$server_data['ping']."</p>";
		}
		$show_version = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='minecraft_version'");
		//if show version
		if($show_version[0]->option_value==1){
			$to_return = $to_return."<p>Minecraft version ".$server_data['version']."</p>";
		}
		
	}
	//if server is offline
	else{
		$to_return = $to_return."<p class='mc_red'>Offline</p>"; 
	}
	$to_return = $to_return."</div>";
	return $to_return;
}
add_shortcode('mc_server_status', 'mc_server_status');
?>
