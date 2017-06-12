<?php
/**
 * @package minecraft-server-status
 * @version 1.1.1
 */
/*
Plugin Name: Minecraft Server Status
Description: Shows information about a minecraft server
Author: David Parker
*/

function mc_db_update(){
	global $wpdb;
	$wpdb->mc_settings = $wpdb->prefix . 'mc_settings';
	$db_version = get_option('mc_db_version', 0);
	
	//if needs to run initial setup
	if(empty($db_version)){
		//create a new table and rows for different minecraft plugin settings.
		//Default all settings to true
		$sqlQuery = "CREATE TABLE ".$wpdb->mc_settings."(
		option_id tinyint NOT NULL PRIMARY KEY AUTO_INCREMENT,
		option_name char(50) NOT NULL,
		option_value BOOLEAN NOT NULL DEFAULT TRUE)";
		echo "<script>console.log( 'Debug Objects: ' );</script>";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sqlQuery);
		dbDelta("INSERT INTO ".$wpdb->mc_settings." (option_name) VALUES ('players_online')");
		dbDelta("INSERT INTO ".$wpdb->mc_settings." (option_name) VALUES ('max_players')");
		dbDelta("INSERT INTO ".$wpdb->mc_settings." (option_name) VALUES ('server_ping')");
		dbDelta("INSERT INTO ".$wpdb->mc_settings." (option_name) VALUES ('minecraft_version')");
		
		//Insert version info into wp_options so this doesn't run again
		$db_version = '1.0';
		update_option('mc_db_version', $db_version, 'no');
	}
}
add_action('init', 'mc_db_update', 0);
?>
