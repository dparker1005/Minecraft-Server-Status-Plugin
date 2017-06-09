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
global $wpdb;
$wpdb->mc_settings = $wpdb->prefix . 'mc_settings';

echo "<link rel='stylesheet' type='text/css' href='../wp-content/plugins/minecraft-server-status/css/mc_admin.css' />";
echo "<h1>Minecraft Server Status</h1>";
echo "<div id='mc-display-settings-div'>";
echo "<h2 id='mc-display-settings-header'>Display Settings</h2>";

//Table holds display settings options with checkboxes
echo "<form name='form' method='post'><table id='mc-display-settings-table'><tr><th>Information</th><th>Show?</th></tr>";

//populates checkboxes by adding 'checked' to html if the option_value
//for a particular option is true in wpdb
function mc_wpdb_check($table, $sql_option_name){
	global $wpdb;
	$checked_query = $wpdb->get_results("SELECT option_value FROM $table WHERE option_name='$sql_option_name'");
	if($checked_query[0]->option_value==1){
		echo " checked";
	}
}

//adding checkboxes, using mc_wpdb_check() as it goes to populate checkboxes
echo "<tr><td>Players Online</td><td><input type='checkbox' name='players_online'";
mc_wpdb_check($wpdb->mc_settings, 'players_online');
echo "></td></tr>";

echo "<tr><td>Maximum Players</td><td><input type='checkbox' name='max_players'";
mc_wpdb_check($wpdb->mc_settings, 'max_players');
echo "></td></tr>";

echo "<tr><td>Server Ping</td><td><input type='checkbox' name='server_ping'";
mc_wpdb_check($wpdb->mc_settings, 'server_ping');
echo "></td></tr>";

echo "<tr><td>Minecraft Version</td><td><input type='checkbox' name='minecraft_version'";
mc_wpdb_check($wpdb->mc_settings, 'minecraft_version');
echo "></td></tr></table>";

//submit button goes to the isset($_POST['update']) down below
echo "<input type='submit' value='Update' name='update'/></form>";

//function updates wpdb with values that are currently in checkboxes. Called
//from the isset($_POST['update']) below for each individual checkbox
function mc_wpdb_update($table, $checkbox_name, $sql_option_name){
	global $wpdb;
	if(isset($_POST[$checkbox_name])){
		$query = "UPDATE $table SET option_value='1' WHERE option_name='$sql_option_name'";
		//echo $query;
		$wpdb->get_results($query);
	}
	else{
		$query = "UPDATE $table SET option_value='0' WHERE option_name='$sql_option_name'";
		//echo $query;
		$wpdb->get_results($query);
	}
}

if(isset($_POST['update'])) { 
	mc_wpdb_update($wpdb->mc_settings, 'players_online', 'players_online');
	mc_wpdb_update($wpdb->mc_settings, 'max_players', 'max_players');
	mc_wpdb_update($wpdb->mc_settings, 'server_ping', 'server_ping');
	mc_wpdb_update($wpdb->mc_settings, 'minecraft_version', 'minecraft_version');
	
	//refreshes page to fix visual bug, changed checkbox kept old value
	//after running previous functions until page was refreshed, even
	//though wpdb was changed
	echo "<meta http-equiv='refresh' content='0'>";
}

echo "</div>";
?>
