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

//Set up wpdb
include( plugin_dir_path( __FILE__ ) . 'includes/db_setup.php');

//Set up minecraft server status shortcode
include( plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php');

//Set up minecraft admin page
include( plugin_dir_path( __FILE__ ) . 'adminpages/mc_admin_menu.php');
?>
