<?php
/* 
Template Name: Stream
*/

//custom hooks below here...

// Just an example.
remove_action('genesis_loop', 'genesis_do_loop');
/**
 * Example function that replaces the default loop with a custom loop querying 'PostType' CPT.
 * Remove this function (along with the remove action hook) to show default page content.
 * Or feel free to update the $args to make it work for you.
*/
add_action('genesis_post_content', 'do_featured_stream');

function do_featured_stream() {
	?><object type="application/x-shockwave-flash" height="378" width="620" id="live_embed_player_flash" data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=darkest_mage" bgcolor="#000000">
	<param name="allowFullScreen" value="true" />
	<param name="allowScriptAccess" value="always" />
	<param name="allowNetworking" value="all" />
	<param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf" />
	<param name="flashvars" value="hostname=www.twitch.tv&channel=darkest_mage&auto_play=true&start_volume=25" />
	</object>
	<a href="http://www.twitch.tv/darkest_mage" class="trk" style="padding:2px 0px 4px; display:block; width:345px; font-weight:normal; font-size:10px; text-decoration:underline; text-align:center;">Watch live video from Michael Jacob&#x27;s Modo on www.twitch.tv</a>
	<?php;
}


genesis(); // <- everything important: make sure to include this. 
?>