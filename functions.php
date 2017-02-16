<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

define( 'WP_MEMORY_LIMIT', '64M' );

load_child_theme_textdomain( 'executive', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'executive' ) );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', __( 'Executive Theme', 'executive' ) );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/executive' );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'executive_add_viewport_meta_tag' );
function executive_add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/* Remove Genesis Footer */
remove_action('genesis_footer', 'genesis_do_footer');
remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

/* Add custom post types */
function custom_post_event() {
	$labels = array(
		'name'               => _x( 'Events', 'post type general name' ),
		'singular_name'      => _x( 'Event', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'event' ),
		'add_new_item'       => __( 'Add New Event' ),
		'edit_item'          => __( 'Edit Event' ),
		'new_item'           => __( 'New Event' ),
		'all_items'          => __( 'All Events' ),
		'view_item'          => __( 'View Events' ),
		'search_items'       => __( 'Search Events' ),
		'not_found'          => __( 'No events found' ),
		'not_found_in_trash' => __( 'No events found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Events'
	);
	$supports = array(
		'title',
		'editor',
		'author',
		'thumbnail',
		'excerpt',
		'comments',
		'custom-fields',
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Upcoming events and recaps of past events',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => $supports,
		'has_archive'   => true,
		'show_in_menu' => true,
		'show_in_admin_bar' => true
	);
	register_post_type( 'event', $args );	
}
add_action( 'init', 'custom_post_event' );

function create_event_taxonomies() {
	$labels = array(
		'name'              => _x( 'Upcoming', 'taxonomy general name' ),
		'search_items'      => __( 'Search Upcoming Events' ),
		'all_items'         => __( 'All Upcoming Events' ),
		'edit_item'         => __( 'Edit Upcoming Event' ),
		'update_item'       => __( 'Update Upcoming Event' ),
		'add_new_item'      => __( 'Add New Upcoming Event' ),
		'menu_name'         => __( 'Upcoming Events' ),
	);

	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'upcoming events' ),
	);

	register_taxonomy( 'upcoming event', array( 'event' ), $args );
}

// Adds capability to choose post templates for custom post types
function my_cpt_post_types( $post_types ) {
    $post_types[] = 'event';
    return $post_types;
}
add_filter( 'cpt_post_types', 'my_cpt_post_types' );

/** Custom navigation support */
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 ) ;

add_action( 'genesis_before', 'genesis_header_markup_open', 5 );
add_action( 'genesis_before', 'genesis_do_header' );
add_action( 'genesis_before', 'genesis_header_markup_close', 15 );

/** Login info before header **/
function display_login_logout_page() {
	if (is_page()) {
		if ( ! is_user_logged_in() )
			echo '<a href="' . esc_url( wp_login_url( $atts['redirect'] ) ) . '" id="login">' . __( 'Log in', 'genesis' ) . '</a>';
		else
			echo '<a href="' . esc_url( wp_logout_url( $atts['redirect'] ) ) . '" id="logout">' . __( 'Log out', 'genesis' ) . '</a>';
	}
};
add_action('genesis_before', 'display_login_logout_page');

function display_login_logout_home() {
	if (is_home()) {
		if ( ! is_user_logged_in() )
			echo '<a href="' . esc_url( wp_login_url( $atts['redirect'] ) ) . '" id="login">' . __( 'Log in', 'genesis' ) . '</a>';
		else
			echo '<a href="' . esc_url( wp_logout_url( $atts['redirect'] ) ) . '" id="logout">' . __( 'Log out', 'genesis' ) . '</a>';
	}
};
add_action('genesis_before', 'display_login_logout_home');

/** Add support for custom background */
add_theme_support( 'custom-background' );

/* Sets Content Width */
$content_width = apply_filters( 'content_width', 680, 680, 760 ); 

/** Create additional color style options 
add_theme_support( 'genesis-style-selector', array(
	'executive-brown' 	=>	__( 'Brown', 'executive' ),
	'executive-green' 	=>	__( 'Green', 'executive' ),
	'executive-orange' 	=>	__( 'Orange', 'executive' ),
	'executive-purple' 	=>	__( 'Purple', 'executive' ),
	'executive-red' 	=>	__( 'Red', 'executive' ),
	'executive-teal' 	=>	__( 'Teal', 'executive' ),
) );
*/

/** Unregister layout settings */
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content' );

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar-alt' );

/** Add new image sizes */
add_image_size( 'user_portrait', 100, 100, TRUE);
add_image_size( 'staff_portrait', 200, 200, TRUE);
add_image_size( 'small', 380, 340, TRUE );
add_image_size( 'medium', 740, 340, TRUE );
add_image_size( 'large', 1140, 340, TRUE );

/** Remove the site description */
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

/** Relocate the post info */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
	if (!is_page()) {
	    $post_info = '
	    <div class=\'date-info\'>' .
		    ' [post_date format="F j, Y" before="<span class=\'date\'>" after="</span>"] ' .
		    __('by', 'executive' ) . ' [post_author_posts_link] [post_edit]
	    </div>';
	    return $post_info;
	}
}

/** Change the default comment callback */
add_filter( 'genesis_comment_list_args', 'executive_comment_list_args' );
function executive_comment_list_args( $args ) {
	$args['callback'] = 'executive_comment_callback';
	
	return $args;
}

/** Customize the comment section */
function executive_comment_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

		<?php do_action( 'genesis_before_comment' ); ?>
		
		<div class="comment-header">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?>
				<?php printf( '<cite class="fn">%s</cite> <span class="says">%s:</span>', get_comment_author_link(), apply_filters( 'comment_author_says_text', __( 'says', 'executive' ) ) ); ?>
				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( '%1$s ' . __('at', 'executive' ) . ' %2$s', get_comment_date(), get_comment_time() ); ?></a>
				<?php edit_comment_link( __( 'Edit', 'executive' ), g_ent( '&bull; ' ), '' ); ?>
				</div><!-- end .comment-meta -->
		 	</div><!-- end .comment-author -->			
		</div><!-- end .comment-header -->	

		<div class="comment-content">
			<?php if ($comment->comment_approved == '0') : ?>
				<p class="alert"><?php echo apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'executive' ) ); ?></p>
			<?php endif; ?>

			<?php comment_text(); ?>
		</div><!-- end .comment-content -->

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>

		<?php do_action( 'genesis_after_comment' );

	/** No ending </li> tag because of comment threading */

}

/** Create portfolio custom post type */
add_action( 'init', 'executive_portfolio_post_type' );
function executive_portfolio_post_type() {
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'executive' ),
				'singular_name' => __( 'Portfolio', 'executive' ),
			),
			'exclude_from_search' => true,
			'has_archive' => true,
			'hierarchical' => true,
			'menu_icon' => get_stylesheet_directory_uri() . '/images/icons/portfolio.png',
			'public' => true,
			'rewrite' => array( 'slug' => 'portfolio' ),
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'genesis-seo' ),
		)
	);
}

/** Change the number of portfolio items to be displayed (props Bill Erickson) */
add_action( 'pre_get_posts', 'executive_portfolio_items' );
function executive_portfolio_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

/** Add support for 3-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 4);

/** Register widget areas **/
genesis_register_sidebar( array(
	'id'			=> 'home-slider',
	'name'			=> __( 'Home - Slider', 'executive' ),
	'description'	=> __( 'This is the slider section on the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-top',
	'name'			=> __( 'Home - Top', 'executive' ),
	'description'	=> __( 'This is the top section of the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-cta',
	'name'			=> __( 'Home - Call To Action', 'executive' ),
	'description'	=> __( 'This is the call to action section on the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle',
	'name'			=> __( 'Home - Middle', 'executive' ),
	'description'	=> __( 'This is the middle section of the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'header_widget_glitch',
	'name'			=> __( 'Header', 'glitch' ),
	'description'	=> __( 'Header Area', 'glitch' ),
) );
/** Register Google Font  **/

add_action( 'genesis_meta', 'wpb_add_google_fonts', 0);

function wpb_add_google_fonts() {
}

/* Removes the site title and site description which will conflict with the left header */
remove_action( 'genesis_site_title', 'genesis_seo_site_title' ); /* look into how this affects SEO */
 
remove_action( 'genesis_site_description', 'genesis_seo_site_description');
remove_action( 'genesis_post_content', 'genesis_do_post_content' );//Remove genesis content handler
add_action( 'genesis_post_content', 'glitch_do_post_content' );

/*Shows content in singular posts, won't display content on home page or articles page*/
function glitch_do_post_content() {

	global $post;

	if ( is_singular() ) {
		the_content();

		if ( is_single() && 'open' == get_option( 'default_ping_status' ) && post_type_supports( $post->post_type, 'trackbacks' ) ) {
			echo '<!--';
			trackback_rdf();
			echo '-->' . "\n";
		}

		if ( is_page() && apply_filters( 'genesis_edit_post_link', true ) )
			edit_post_link( __( '(Edit)', 'genesis' ), '', '' );
	}
	elseif ( 'excerpts' == genesis_get_option( 'content_archive' ) ) {
		the_excerpt();
	}
	elseif ( is_home() || is_category() ) {
		//Display Nothing
	}
	else {
		if ( genesis_get_option( 'content_archive_limit' ) )
			the_content_limit( (int) genesis_get_option( 'content_archive_limit' ), __( '[Read more...]', 'genesis' ) );
		else
			the_content( __( '[Read more...]', 'genesis' ) );
	}

	wp_link_pages( array( 'before' => '<p class="pages">' . __( 'Pages:', 'genesis' ), 'after' => '</p>' ) );
}

/*Modify the post meta data*/
remove_filter( 'genesis_post_meta', 'do_shortcode', 20 );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
add_action( 'genesis_before_post_content', 'glitch_post_meta' );

function glitch_post_meta() {

	global $post;

	if ( 'page' == get_post_type( $post->ID ) )
		return;
	
	if ( is_single() )
		return;
		
	$post_meta = the_category( '', 'single', $post->ID );
	if ( is_home() || is_category() ) {
 		printf( '<div class="main-meta">%s</div>', apply_filters( 'genesis_post_meta', $post_meta ) );
		}
 	else {
		printf( '<div class="post-meta">%s</div>', apply_filters( 'genesis_post_meta', $post_meta ) );
		}
	}

remove_action( 'genesis_comments', 'genesis_do_comments' );
add_action( 'genesis_comments', 'glitch_do_comments' );
/**
 * Echo Genesis default comment structure, edited by Elan Majkrzak 6/16/2013
 *
 * @since 1.1.2
 *
 * @uses genesis_get_option()
 *
 * @global stdClass $post Post object
 * @global WP_Query $wp_query
 * @return null Returns early if on a page with Genesis pages comments off, or a post and Genesis posts comments off.
 */
function glitch_do_comments() {

	global $post, $wp_query;

	/** Bail if comments are off for this post type */
	if ( ( is_page() && ! genesis_get_option( 'comments_pages' ) ) || ( is_single() && ! genesis_get_option( 'comments_posts' ) ) )
		return;

	if ( have_comments() && ! empty( $wp_query->comments_by_type['comment'] ) ) {
		?>
		<div id="comments">
			<ol class="comment-list">
				<?php do_action( 'genesis_list_comments' ); ?>
			</ol>
			<div class="navigation">
				<div class="alignleft"><?php previous_comments_link() ?></div>
				<div class="alignright"><?php next_comments_link() ?></div>
			</div>
		</div><!--end #comments-->
		<?php
	}
	/** No comments so far */
	else {
		?>
		<div id="comments">
			<?php
			/** Comments are open, but there are no comments */
			if ( 'open' == $post->comment_status )
				echo apply_filters( 'genesis_no_comments_text', '' );
			else /** Comments are closed */
				echo apply_filters( 'genesis_comments_closed_text', '' );
			?>
		</div><!--end #comments-->
		<?php
	}

}

/*Handle Comment list, if there are any comments*/
remove_action( 'genesis_list_comments', 'genesis_default_list_comments' );
add_action( 'genesis_list_comments', 'glitch_default_list_comments' );

function glitch_comment_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

		<?php do_action( 'genesis_before_comment' ); ?>

		<div class="comment-header">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?>
				<?php printf( __( '<cite class="fn">%s</cite> <span class="says">%s:</span>', 'genesis' ), get_comment_author_link(), apply_filters( 'comment_author_says_text', __( 'says', 'genesis' ) ) ); ?>
		 	</div><!-- end .comment-author -->

			<div class="comment-meta commentmetadata">
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'genesis' ), get_comment_date(), get_comment_time() ); ?></a>
				<?php edit_comment_link( __( '(Edit)', 'genesis' ), '' ); ?>
			</div><!-- end .comment-meta -->
		</div>

		<div class="comment-content">
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<p class="alert"><?php echo apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'genesis' ) ); ?></p>
			<?php endif; ?>

			<?php comment_text(); ?>
		</div><!-- end .comment-content -->

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>

		<?php do_action( 'genesis_after_comment' );

	/** No ending </li> tag because of comment threading */

}

function glitch_default_list_comments() {

	$args = array(
		'type'			=> 'comment',
		'avatar_size'	=> 48,
		'callback'		=> 'glitch_comment_callback',
	);

	$args = apply_filters( 'genesis_comment_list_args', $args );

	wp_list_comments( $args );

}

remove_filter( 'comment_form_defaults', 'genesis_comment_form_args' );
add_filter( 'comment_form_defaults', 'glitch_comment_form_args' );
/**
 * Filters the default comment form arguments, used by <code>comment_form()</code>
 * Modified by Elan Majkrzak 6/16/2013
 *
 * @since 1.8.0
 *
 * @global string $user_identity Display name of the user
 * @global integer $id Post ID to generate the form for
 *
 * @param array $defaults Comment form defaults
 *
 * @return array Filterable array
 */
function glitch_comment_form_args( $defaults ) {

	global $user_identity, $id;

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? ' aria-required="true"' : '' );

	$author = '<p class="comment-form-author">' .
	          '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1"' . $aria_req . ' />' .
	          '<label for="author">' . __( 'Name', 'genesis' ) . '</label> ' .
	          ( $req ? '<span class="required">*</span>' : '' ) .
	          '</p><!-- #form-section-author .form-section -->';

	$email = '<p class="comment-form-email">' .
	         '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
	         '<label for="email">' . __( 'Email', 'genesis' ) . '</label> ' .
	         ( $req ? '<span class="required">*</span>' : '' ) .
	         '</p><!-- #form-section-email .form-section -->';

	$url = '<p class="comment-form-url">' .
	       '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
	       '<label for="url">' . __( 'Website', 'genesis' ) . '</label>' .
	       '</p><!-- #form-section-url .form-section -->';

	$comment_field = '<p class="comment-form-comment">' .
	                 '<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>' .
	                 '</p><!-- #form-section-comment .form-section -->';

	$args = array(
		'fields'               => array(
			'author' => $author,
			'email'  => $email,
			'url'    => $url,
		),
		'comment_field'        => $comment_field,
		'title_reply'          => '',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
	);

	/** Merge $args with $defaults */
	$args = wp_parse_args( $args, $defaults );

	/** Return filterable array of $args, along with other optional variables */
	return apply_filters( 'genesis_comment_form_args', $args, $user_identity, $id, $commenter, $req, $aria_req );

}

/* Modify Div output names for post info */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
add_action( 'genesis_after_post_title', 'glitch_post_info' );

function glitch_post_info() {

	global $post;

	if ( 'page' == get_post_type( $post->ID ) )
		return;

	$post_info = '[post_date] ' . __( 'by', 'genesis' ) . ' [post_author_posts_link] [post_comments] [post_edit]';
	if ( is_home() || is_category() ) {
		printf( '<div class="main-info">%s</div>', apply_filters( 'genesis_post_info', $post_info ) );
	}
	else {
		printf( '<div class="post-info">%s</div>', apply_filters( 'genesis_post_info', $post_info ) );
	}

}

/* Modify div output names for post title */
remove_action( 'genesis_post_title', 'genesis_do_post_title' );
add_action( 'genesis_post_title', 'glitch_do_post_title' );

function glitch_do_post_title() {

	$title = apply_filters( 'genesis_post_title_text', get_the_title() );

	if ( 0 == strlen( $title ) )
		return;

	if ( is_singular() )
		$title = sprintf( '<h1 class="entry-title">%s</h1>', $title );
	elseif ( apply_filters( 'genesis_link_post_title', true ) )
		$title = sprintf( '<h2 class="main-title"><a href="%s" title="%s" rel="bookmark">%s</a></h2>', get_permalink(), the_title_attribute( 'echo=0' ), apply_filters( 'genesis_post_title_text', $title ) );
	else
		$title = sprintf( '<h2 class="filtered-title">%s</h2>', $title );

	echo apply_filters( 'genesis_post_title_output', "$title \n" );

}

/* START USER PROFILE STUFF */
/* Change User Contact method profile fields */

function modify_user_fields($profile_fields) {

	// Add new fields
	$profile_fields['twitter'] = 'Twitter Username';
	$profile_fields['facebook'] = 'Facebook URL';
	$profile_fields['twitch'] = 'Twitch Channel Name (NOT the url)';
	$profile_fields['title'] = 'Title';
	
	// Remove fields we don't care about
	unset($profile_fields['aim']);
	unset($profile_fields['jabber']);
	unset($profile_fields['yim']);

	return $profile_fields;
}
add_filter('user_contactmethods', 'modify_user_fields');

function my_show_extra_profile_fields( $user ) { 
	$isLive = get_the_author_meta( 'isLive', $user->ID);
	?>
	<table class="form-table">

		<tr>
			<th><label for="isLive">Livestream</label></th>
			<td>
				<span class="description">Is your stream currently live?</span><br />
				<input type="radio" name="isLive" id="isLive" value="Live" <?php if ($isLive == 'Live') { ?>checked="checked"<?php } ?>/>Live<br />
				<input type="radio" name="isLive" id="isLive" value="Offline" <?php if ($isLive == 'Offline') { ?>checked="checked"<?php } ?>/>Offline<br />
			</td>
		</tr>

	</table>
<?php 
	
	$esports = get_the_author_meta( 'esports', $user->ID);
		
	if (is_admin()) {
		
		?>
		<table class="form-table">

		<tr>
			<th><label for="esports">eSports Teams</label></th>
			<td>
				<input type="text" name="esports" id="esports" value="<?php echo $esports ?>"/><br />
			</td>
		</tr>

	</table>
	<?php
	} else {
		?>
		<input type="hidden" name="esports" id="esports" value="<?php echo $esports ?>"/><br />
		<?php
	}
}


function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. */
	update_usermeta( $user_id, 'isLive', $_POST['isLive'] );
	update_usermeta( $user_id, 'esports', $_POST['esports'] );
}

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

/* Remove color options at top */
add_action('admin_head', 'admin_del_color_options');
function admin_del_color_options() {
	global $_wp_admin_css_colors;
	$_wp_admin_css_colors = 0;
}

/* Remove personal options section (doesn't actually remove, but hides it from view) */
function hide_personal_options(){
	echo '<script type="text/javascript">jQuery(document).ready(function($) { $("form#your-profile > h3:first").hide(); $("form#your-profile > table:first").hide(); $("form#your-profile").show(); });</script>';
}
add_action('admin_head','hide_personal_options');

/* END USER PROFILE STUFF */

/* START SIDEBAR STUFF */

/* Remove Portfolio Menu - removes for all users, including admin */
add_action('admin_menu','remove_portfolio');
function remove_portfolio() {
	remove_menu_page('edit.php?post_type=portfolio');
}

/* 
 Remove dashboard link for subscribers 
 Note - Only removes sidebar dashboard link, does not remove link from top bar
 */
add_action('admin_menu','remove_dashboard');
function remove_dashboard() {
	if(is_user_logged_in() && current_user_can('subscriber')) {
		remove_menu_page('index.php');
		global $menu;
		unset($menu['Dashboard']);
	}
}

/* END SIDEBAR STUFF */

/* START TOOLBAR STUFF */

/* Remove Logo menu - affects everyone */
add_action('admin_bar_menu', 'remove_logo', 999);
function remove_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node('wp-logo');
}

/* 
 Remove drop-down from Glitch Gaming menu for subscribers 
 Removes access to the dashboard (still accessible by direct link
 */

add_action('admin_bar_menu', 'modify_menu', 999);
function modify_menu($wp_admin_bar) {
	$wp_admin_bar->remove_node('appearance');
	if(is_user_logged_in() && current_user_can('subscriber')) {
		$wp_admin_bar->remove_node('dashboard');
		$wp_admin_bar->remove_node('site-name');
		$new_node = array(
			'id' => 'site-name',
			'title' => 'Glitch Gaming',
			'href' => 'http://glitch.mn'
		);
		$wp_admin_bar->add_node($new_node);
	}
}

/* END TOOLBAR STUFF */

/* TEST CODE - DO NOT SEND TO PRODUCTION */
/* Commenting out so we don't have it on live
 *
// use 'wp_before_admin_bar_render' hook to also get nodes produced by plugins.
add_action( 'wp_before_admin_bar_render', 'add_all_node_ids_to_toolbar' );

function add_all_node_ids_to_toolbar() {

	global $wp_admin_bar;
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	if ( $all_toolbar_nodes ) {

		// add a top-level Toolbar item called "Node Id's" to the Toolbar
		$args = array(
			'id'    => 'node_ids',
			'title' => 'Node ID\'s'
		);
		$wp_admin_bar->add_node( $args );

		// add all current parent node id's to the top-level node.
		foreach ( $all_toolbar_nodes as $node  ) {
			if ( isset($node->parent) && $node->parent ) {

				$args = array(
					'id'     => 'node_id_'.$node->id, // prefix id with "node_id_" to make it a unique id
					'title'  => $node->id,
					'parent' => 'node_ids'
					// 'href' => $node->href,
				);
				// add parent node to node "node_ids"
				$wp_admin_bar->add_node($args);
			}
		}

		// add all current Toolbar items to their parent node or to the top-level node
		foreach ( $all_toolbar_nodes as $node ) {

			$args = array(
				'id'      => 'node_id_'.$node->id, // prefix id with "node_id_" to make it a unique id
				'title'   => $node->id,
				// 'href' => $node->href,
			);

			if ( isset($node->parent) && $node->parent ) {
				$args['parent'] = 'node_id_'.$node->parent;
			} else {
				$args['parent'] = 'node_ids';
			}

			$wp_admin_bar->add_node($args);
		}
	}
}
 */
/* END TEST CODE */

/**
 * Woo Commerce theme stuff
 * Need to unhook woo commerce wrappers, and add glitch wrappers
 * Not really sure if this is ideal yet...
 */
add_theme_support( 'genesis-connect-woocommerce' );

// Custom sidebar
register_sidebar(array( 'name' => 'Woocommerce',

'id' => 'woocommerce',

'before_widget' => '<aside id="%1$s">',

'after_widget' => '</aside>',

'before_title' => '<h3>',

'after_title' => '<span></span></h3>', ));
 
 
include_once('woowidgets/widget-woocommerce_random_products.php');
function woocommerce_register_widgets_CUSTOM() {
register_widget('WooCommerce_Random_Widgets_CUSTOM');
}
add_action('widgets_init', 'woocommerce_register_widgets_CUSTOM');

function excerpt($limit) {
       $excerpt = explode(' ', get_the_content(), $limit);
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
    }

define('WOOCOMMERCE_USE_CSS', false);
add_theme_support( 'genesis-connect-woocommerce' );

wp_enqueue_style('woocommerce_css', get_stylesheet_directory_uri() . '/woocss/css/woocommerce.css');
wp_enqueue_style('prettyPhoto_css', get_stylesheet_directory_uri() . '/woocss/css/prettyPhoto.css');

?>