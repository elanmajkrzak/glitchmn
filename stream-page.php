<?php
/* 
Template Name: Stream
*/

get_header();

function do_stream_page_title() {
	?><h1 class="page-title">
		Stream
	</h1>
	<?php
}

function stream_switch_script() {
	wp_enqueue_script( 'do_stream_switch', site_url() . '/web-scripts/do_stream_switch.js' );
}

add_action( 'wp_enqueue_scripts', 'stream_switch_script' );
?><div id="stream-scripts" >
		<script type="text/javascript">
		function do_stream_switch(stream) {
			var $ = function(id) {
				return document.getElementById(id);
			}
			
			$("featured-stream").innerHTML="<object type=\"application/x-shockwave-flash\" height=\"512\" width=\"800\" id=\"live_embed_player_flash\" data=\"http://www.twitch.tv/widgets/live_embed_player.swf?channel=" + stream +"\"  bgcolor=\"#000000\"> " +
			"<param name=\"allowFullScreen\" value=\"true\" />" +
			"<param name=\"allowScriptAccess\" value=\"sameDomain\" />" +
			"<param name=\"allowNetworking\" value=\"all\" />" +
			"<param name=\"movie\" value=\"http://www.twitch.tv/widgets/live_embed_player.swf\" />" +
			"<param name=\"flashvars\" value=\"hostname=www.twitch.tv&channel=" + stream + "&auto_play=true&start_volume=0\" />" +
			"</object>" +
		"<div id=\"featured-chat\">" +
			"<iframe frameborder=\"0\" " +
			"scrolling=\"no\" " +
			"id=\"chat_embed\" " +
			"src=\"http://www.twitch.tv/chat/embed?channel=" + stream + "&popout_chat=true\" "+ 
			"height=\"512\" " +
			"width=\"300\"> " +
			"</iframe>" +
		"</div>";
		}
		</script>
</div>
<?php

$user_ids = array();
$live_users = array();
$live_titles = array();
$live_games = array();
$offline_users = array();

function do_featured_stream() {
	global $user_ids;
	global $live_users;
	global $live_titles;
	global $live_games;
	global $offline_users;
	
	$stream = 'glitchmn'; //default to glitch
	
	$args = array(
		'meta_key' => 'twitch',
		'meta_value' => ' ',
		'meta_compare' => '!='
	);
	$users = get_users($args);
	
	for ($i = 0; $i < count($users); $i++) {
		if ($users[$i]->isLive == 'Live') {
			$live_users[] = $users[$i];
		} else {
			$offline_users[] = $users[$i];
		}
	}
	
	for ($i = 0; $i < count($live_users); $i++) {
		$search_stream = $live_users[$i]-> twitch;
		
		$json_file = @file_get_contents("https://api.twitch.tv/kraken/streams/$search_stream", 0, 	null, null);
		$json_array = json_decode($json_file, true);
			
		if (isset($json_array['stream'])) {
			$live_titles[] = $json_array['stream']['channel']['status'];
			$live_games[] = $json_array['stream']['game'];
			
			if ($json_array['stream']['viewers'] > $top_viewers) {
				$channelTitle = $json_array['stream']['channel']['name'];
				$title = $json_array['stream']['channel']['status'];
				$game = $json_array['stream']['game'];
				$stream = $search_stream;
				$top_viewers = $json_array['stream']['viewers'];
			}
		}
	}
	
	if ($live_users[0] == "1") {
		$glitch_user_info = get_userdata(1);
		$stream = $glitch_user_info-> twitch;
		$title = $live_titles[0];
	}
		
	?>
	<div id="featured-stream">
			<object type="application/x-shockwave-flash" height="512" width="800" id="live_embed_player_flash" data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=<?php echo $stream ?>" bgcolor="#000000">
			<param name="allowFullScreen" value="true" />
			<param name="allowScriptAccess" value="sameDomain" />
			<param name="allowNetworking" value="all" />
			<param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf" />
			<param name="flashvars" value="hostname=www.twitch.tv&channel=<?php echo $stream ?>&auto_play=true&start_volume=0" />
			</object>
		<div id="featured-chat">
			<iframe frameborder="0" 
			scrolling="no" 
			id="chat_embed" 
			src="http://www.twitch.tv/chat/embed?channel=<?php echo $stream ?>&popout_chat=true" 
			height="512" 
			width="300">
			</iframe>
		</div>
	</div>
	<?php;
}

function do_live_stream_list() {
	global $live_users;
	global $live_titles;
	global $live_games;
	
	$num_live_users = count($live_users);
	
	if ($num_live_users != 0) {
		?>
		<h2 class="live-list-title">Live Streams</h2>
		<div id="live-stream-list">
		<?php
		for ($i = 0; $i <  $num_live_users; $i++) {
			$cur_user = get_userdata($live_users[$i]);
			$display_name = $live_users[$i]->user_firstname . " " . $live_users[$i]->user_lastname;
			$cur_twitch = $live_users[$i] -> twitch;

			?>
			<div class="live-stream-list-item" onclick="do_stream_switch('<?php echo$cur_twitch?>')">
				<div class="post-bg-size-xsmall" style="background-image: url('<?php echo get_wp_user_avatar_src($live_users[$i]->ID, 1140) ?>')">
					<p class="live-stream-list-item-name"><?php echo $display_name ?> playing <?php echo $live_games[$i] ?></p>
					<p class="live-stream-list-item-title"><?php echo $live_titles[$i] ?></p>
				</div>
			</div>
			<?php
		}
		?>
		</div>
		<?php
	}
	
}

function do_offline_stream_list() {	
	global $offline_users;
	
	$num_offline_users = count($offline_users);
	
	if ($num_offline_users != 0) {
		?>
		<h2 class="offline-list-title">Offline Streams</h2>
		<div id="offline-stream-list">
		<script type="text/javascript">
		function user_profile(url) {
			window.open(url);
			return;
		}
		</script>
		<?php
		for ($i = 0; $i <  $num_offline_users; $i++) {
			$cur_user = get_userdata($offline_users[$i]);
			$display_name = $offline_users[$i]->user_firstname . " " . $offline_users[$i]->user_lastname;
			?>
			<div class="offline-stream-list-item" >
				<div class="post-bg-size-xsmall" 
				onclick="user_profile('<?php echo get_author_posts_url($offline_users[$i]->ID)?>')" 
				style="background-image: url('<?php echo get_wp_user_avatar_src($offline_users[$i]->ID, 1140) ?>')">
					<p class="offline-stream-list-item-name"><?php echo $display_name ?></p>
				</div>
			</div>
			<?php
		}
		?>
		</div>
		<?php
	}
}

do_stream_page_title();
do_featured_stream();
do_live_stream_list();
do_offline_stream_list();

?>
</div><!--close wrap div-->
<?php
get_footer();
?>