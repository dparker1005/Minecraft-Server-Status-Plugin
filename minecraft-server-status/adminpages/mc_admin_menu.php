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
function mc_admin_menu(){
	//Set up sidebar menu
	add_menu_page('Minecraft Server',
				  'Minecraft Server',
				  'manage_options',
				  'mc_server',
				  'mc_admin_page');  
}
add_action('admin_menu', 'mc_admin_menu');


function mc_admin_page(){
	//Link to php file for actual page
	require_once dirname(__FILE__) . "/mc_preferences_page.php";
}

?>
