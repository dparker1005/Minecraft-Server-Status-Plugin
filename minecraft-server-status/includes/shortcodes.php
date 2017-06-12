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
function mc_server_status($atts){

	global $wpdb;
	$wpdb->mc_settings = $wpdb->prefix . 'mc_settings';

	//grab the server name from the shortcode call
	$server = $atts['server'];
	?>
	<style>
		.mc_green{
			color:#3aaf2f;
		}

		.mc_hostname{
			margin:5px;
		}

		.mc_red{
			color:#aa0b0b;
		}

		.mc_server_div{
			background-color:#cecece;
			width:300px;
			text-align:center;
			margin: 10px;
			border: 5px solid black;
		}

		.mc_yellow{
			color:#d8d83c;
		}
	
		p{
			margin:5px;
		}
	</style>
	
	<?php
	//get server data from minecraft API call
	$raw_server_data = file_get_contents('https://mcapi.ca/query/'.$server.'/info');
	//decode server data with json 
	$server_data = json_decode($raw_server_data, true);?>
	<div class='mc_server_div'>
		<h3 class='mc_hostname'>
			<?php
				echo $server_data['hostname'];
			?>
		</h3>
		
		<?php
		//if server is online
		if($server_data['status'] == 1){
		?>
			<p class='mc_green'>Online</p>
			<?php 
				$players_online = $server_data['players']['online'];
				$players_max = $server_data['players']['max'];
			
				$show_players_online = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='players_online'");
				$show_max_players = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='max_players'");
		
				//if show online players and max players
				if($show_players_online[0]->option_value==1 and $show_max_players[0]->option_value==1){
					//if server is full
					if($players_online >= $players_max){
			?>
						<p class='mc_red'>
					<?php
					}
					//if server is almost full
					elseif($players_online >= $players_max*.9){
					?>
						<p class='mc_yellow'>
					<?php
					}
					//if there is space on server
					else{
					?>
						<p class='mc_green'>
					<?php
					}
			
					echo $players_online."/".$players_max;
					?>
					 Players Online</p>
				<?php
				}
				//if show only online players
				elseif($show_players_online[0]->option_value==1){
				?>
					<p><?php echo $players_online; ?> Players Online</p>
				<?php
				}
				//if show only max players
				elseif($show_max_players[0]->option_value==1){
				?>
					<p><?php echo $players_max; ?> Player Maximum</p>
				<?php
				}
				$show_ping = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='server_ping'");
				//if show ping
				if($show_ping[0]->option_value==1){
				?>
					<p>Ping: <?php echo $server_data['ping']; ?></p>
					<?php
				}
				$show_version = $wpdb->get_results("SELECT option_value FROM ".$wpdb->mc_settings." WHERE option_name='minecraft_version'");	
				//if show version
				if($show_version[0]->option_value==1){
				?>
					<p>Minecraft version <?php echo $server_data['version']; ?></p>
				<?php
				}
		
			}
		//if server is offline
		else{
			?>
			<p class='mc_red'>Offline</p>
		<?php
		} ?>
	</div>
<?php
}
add_shortcode('mc_server_status', 'mc_server_status');
?>
