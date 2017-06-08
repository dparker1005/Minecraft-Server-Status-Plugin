<?php
/**
 * @package minecraft-server-status
 * @version 1.0
 */
/*
Plugin Name: Minecraft Server Status
Description: Shows information about a minecraft server
Author: David Parker
*/
function mc_server_status($atts){

	$server = $atts['server'];
	$to_return = "";
	$to_return = $to_return."<link rel='stylesheet' type='text/css' href='../wp-content/plugins/minecraft-server-status/minecraft-server-status.css' />";
	$raw_server_data = file_get_contents('https://mcapi.ca/query/'.$server.'/info');
	//echo $raw_server_data;
	$server_data = json_decode($raw_server_data, true);
	$to_return = $to_return."<div class='mc_server_div'>";
	$to_return = $to_return."<h3 class='mc_hostname'>".$server_data['hostname']."</h3>";
	if($server_data['status'] == 1){
		$to_return = $to_return."<p class='mc_green'>Online</p>"; 
		$players_online = $server_data['players']['online'];
		$players_max = $server_data['players']['max'];
		if($players_online >= $players_max){
			$to_return = $to_return."<p class='mc_red'> ";
		}
		elseif($players_online >= $players_max*.9){
			$to_return = $to_return."<p class='mc_yellow'> ";
		}
		else{
			$to_return = $to_return."<p class='mc_green'> ";
		}
		
		$to_return = $to_return.$players_online."/".$players_max." players online</p>";
		$to_return = $to_return."<p>Ping: ".$server_data['ping']."</p>";
		$to_return = $to_return."<p>Minecraft version ".$server_data['version']."</p>";
	}
	else{
		$to_return = $to_return."<p class='mc_red'>Offline</p>"; 
	}
	$to_return = $to_return."</div>";
	return $to_return;
}
add_shortcode('mc_server_status', 'mc_server_status');

?>
