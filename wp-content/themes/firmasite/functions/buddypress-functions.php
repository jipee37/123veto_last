<?php

/**
 * Functions of BuddyPress's Legacy theme
 *
 * @package BuddyPress
 * @subpackage BP_Theme_Compat
 * @since BuddyPress (1.7)
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/** Theme Setup ***************************************************************/

if ( !class_exists( 'BP_Legacy' ) ) :

/**
 * Loads BuddyPress Legacy Theme functionality
 *
 * This is not a real theme by WordPress standards, and is instead used as the
 * fallback for any WordPress theme that does not have BuddyPress templates in it.
 *
 * To make your custom theme BuddyPress compatible and customize the templates, you
 * can copy these files into your theme without needing to merge anything
 * together; BuddyPress should safely handle the rest.
 *
 * See @link BP_Theme_Compat() for more.
 *
 * @since BuddyPress (1.7)
 *
 * @package BuddyPress
 * @subpackage BP_Theme_Compat
 */
class BP_Legacy extends BP_Theme_Compat {

	/** Functions *************************************************************/

	/**
	 * The main BuddyPress (Legacy) Loader
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @uses BP_Legacy::setup_globals()
	 * @uses BP_Legacy::setup_actions()
	 */
	public function __construct() {
		parent::start();
	}

	/**
	 * Component global variables
	 *
	 * You'll want to customize the values in here, so they match whatever your
	 * needs are.
	 *
	 * @since BuddyPress (1.7)
	 * @access private
	 */
	protected function setup_globals() {
		$bp            = buddypress();
		$this->id      = 'legacy';
		$this->name    = 'firmasite';
		$this->version = bp_get_version();
		$this->dir     = trailingslashit( $bp->themes_dir . '/bp-legacy' );
		$this->url     = trailingslashit( $bp->themes_url . '/bp-legacy' );
	}

	/**
	 * Setup the theme hooks
	 *
	 * @since BuddyPress (1.7)
	 * @access private
	 *
	 * @uses add_filter() To add various filters
	 * @uses add_action() To add various actions
	 */
	protected function setup_actions() {

		// Template Output
		add_filter( 'bp_get_activity_action_pre_meta', array( $this, 'secondary_avatars' ), 10, 2 );

		// Filter BuddyPress template hierarchy and look for page templates
		add_filter( 'bp_get_buddypress_template', array( $this, 'theme_compat_page_templates' ), 10, 1 );

		/** Scripts ***********************************************************/

		//add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_styles'   ) ); // Enqueue theme CSS
		add_action( 'bp_enqueue_scripts', array( $this, 'enqueue_scripts'  ) ); // Enqueue theme JS
		add_filter( 'bp_enqueue_scripts', array( $this, 'localize_scripts' ) ); // Enqueue theme script localization
		add_action( 'bp_head',            array( $this, 'head_scripts'     ) ); // Output some extra JS in the <head>

		/** Body no-js Class **************************************************/

		add_filter( 'body_class', array( $this, 'add_nojs_body_class' ), 20, 1 );

		/** Buttons ***********************************************************/

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			// Register buttons for the relevant component templates
			// Friends button
			if ( bp_is_active( 'friends' ) )
				add_action( 'bp_member_header_actions',    'bp_add_friend_button',           5 );

			// Activity button
			if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() )
				add_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );

			// Messages button
			if ( bp_is_active( 'messages' ) )
				add_action( 'bp_member_header_actions',    'bp_send_private_message_button', 20 );

			// Group buttons
			if ( bp_is_active( 'groups' ) ) {
				add_action( 'bp_group_header_actions',          'bp_group_join_button',               5 );
				add_action( 'bp_group_header_actions',          'bp_group_new_topic_button',         20 );
				add_action( 'bp_directory_groups_actions',      'bp_group_join_button'                  );
				add_action( 'bp_groups_directory_group_filter', 'bp_legacy_theme_group_create_nav', 999 );
			}

			// Blog button
			if ( bp_is_active( 'blogs' ) ) {
				add_action( 'bp_directory_blogs_actions',    'bp_blogs_visit_blog_button'           );
				add_action( 'bp_blogs_directory_blog_types', 'bp_legacy_theme_blog_create_nav', 999 );
			}
		}

		/** Notices ***********************************************************/

		// Only hook the 'sitewide_notices' overlay if the Sitewide
		// Notices widget is not in use (to avoid duplicate content).
		if ( bp_is_active( 'messages' ) && ! is_active_widget( false, false, 'bp_messages_sitewide_notices_widget', true ) ) {
			add_action( 'wp_footer', array( $this, 'sitewide_notices' ), 9999 );
		}

		/** Ajax **************************************************************/

		$actions = array(

			// Directory filters
			'blogs_filter'    => 'bp_legacy_theme_object_template_loader',
			'forums_filter'   => 'bp_legacy_theme_object_template_loader',
			'groups_filter'   => 'bp_legacy_theme_object_template_loader',
			'members_filter'  => 'bp_legacy_theme_object_template_loader',
			'messages_filter' => 'bp_legacy_theme_messages_template_loader',
			'invite_filter'   => 'bp_legacy_theme_invite_template_loader',
			'requests_filter' => 'bp_legacy_theme_requests_template_loader',

			// Friends
			'accept_friendship' => 'bp_legacy_theme_ajax_accept_friendship',
			'addremove_friend'  => 'bp_legacy_theme_ajax_addremove_friend',
			'reject_friendship' => 'bp_legacy_theme_ajax_reject_friendship',

			// Activity
			'activity_get_older_updates'  => 'bp_legacy_theme_activity_template_loader',
			'activity_mark_fav'           => 'bp_legacy_theme_mark_activity_favorite',
			'activity_mark_unfav'         => 'bp_legacy_theme_unmark_activity_favorite',
			'activity_widget_filter'      => 'bp_legacy_theme_activity_template_loader',
			'delete_activity'             => 'bp_legacy_theme_delete_activity',
			'delete_activity_comment'     => 'bp_legacy_theme_delete_activity_comment',
			'get_single_activity_content' => 'bp_legacy_theme_get_single_activity_content',
			'new_activity_comment'        => 'bp_legacy_theme_new_activity_comment',
			'post_update'                 => 'bp_legacy_theme_post_update',
			'bp_spam_activity'            => 'bp_legacy_theme_spam_activity',
			'bp_spam_activity_comment'    => 'bp_legacy_theme_spam_activity',

			// Groups
			'groups_invite_user' => 'bp_legacy_theme_ajax_invite_user',
			'joinleave_group'    => 'bp_legacy_theme_ajax_joinleave_group',

			// Messages
			'messages_autocomplete_results' => 'bp_legacy_theme_ajax_messages_autocomplete_results',
			'messages_close_notice'         => 'bp_legacy_theme_ajax_close_notice',
			'messages_delete'               => 'bp_legacy_theme_ajax_messages_delete',
			'messages_markread'             => 'bp_legacy_theme_ajax_message_markread',
			'messages_markunread'           => 'bp_legacy_theme_ajax_message_markunread',
			'messages_send_reply'           => 'bp_legacy_theme_ajax_messages_send_reply',
		);

		// Conditional actions
		if ( bp_is_active( 'messages', 'star' ) ) {
			$actions['messages_star'] = 'bp_legacy_theme_ajax_messages_star_handler';
		}

		/**
		 * Register all of these AJAX handlers
		 *
		 * The "wp_ajax_" action is used for logged in users, and "wp_ajax_nopriv_"
		 * executes for users that aren't logged in. This is for backpat with BP <1.6.
		 */
		foreach( $actions as $name => $function ) {
			add_action( 'wp_ajax_'        . $name, $function );
			add_action( 'wp_ajax_nopriv_' . $name, $function );
		}

		add_filter( 'bp_ajax_querystring', 'bp_legacy_theme_ajax_querystring', 10, 2 );

		/** Override **********************************************************/

		/**
		 * Fires after all of the BuddyPress theme compat actions have been added.
		 *
		 * @since BuddyPress (1.7.0)
		 *
		 * @param BP_Legacy $this Current BP_Legacy instance.
		 */
		do_action_ref_array( 'bp_theme_compat_actions', array( &$this ) );
	}

	/**
	 * Load the theme CSS
	 *
	 * @since BuddyPress (1.7)
	 * @since BuddyPress (2.3.0) Support custom CSS file named after the current theme or parent theme.
	 *
	 * @uses wp_enqueue_style() To enqueue the styles
	 */
	public function enqueue_styles() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Locate the BP stylesheet
		$ltr = $this->locate_asset_in_stack( "buddypress{$min}.css",     'css' );

		// LTR
		if ( ! is_rtl() && isset( $ltr['location'], $ltr['handle'] ) ) {
			wp_enqueue_style( $ltr['handle'], $ltr['location'], array(), $this->version, 'screen' );

			if ( $min ) {
				wp_style_add_data( $ltr['handle'], 'suffix', $min );
			}
		}

		// RTL
		if ( is_rtl() ) {
			$rtl = $this->locate_asset_in_stack( "buddypress-rtl{$min}.css", 'css' );

			if ( isset( $rtl['location'], $rtl['handle'] ) ) {
				$rtl['handle'] = str_replace( '-css', '-css-rtl', $rtl['handle'] );  // Backwards compatibility
				wp_enqueue_style( $rtl['handle'], $rtl['location'], array(), $this->version, 'screen' );

				if ( $min ) {
					wp_style_add_data( $rtl['handle'], 'suffix', $min );
				}
			}
		}

		// Compatibility stylesheets for specific themes.
		$theme = $this->locate_asset_in_stack( get_template() . "{$min}.css", 'css' );
		if ( ! is_rtl() && isset( $theme['location'] ) ) {
			// use a unique handle
			$theme['handle'] = 'bp-' . get_template();
			wp_enqueue_style( $theme['handle'], $theme['location'], array(), $this->version, 'screen' );

			if ( $min ) {
				wp_style_add_data( $theme['handle'], 'suffix', $min );
			}
		}

		// Compatibility stylesheet for specific themes, RTL-version
		if ( is_rtl() ) {
			$theme_rtl = $this->locate_asset_in_stack( get_template() . "-rtl{$min}.css", 'css' );

			if ( isset( $theme_rtl['location'] ) ) {
				$theme_rtl['handle'] = $theme['handle'] . '-rtl';
				wp_enqueue_style( $theme_rtl['handle'], $theme_rtl['location'], array(), $this->version, 'screen' );

				if ( $min ) {
					wp_style_add_data( $theme_rtl['handle'], 'suffix', $min );
				}
			}
		}
	}

	/**
	 * Enqueue the required JavaScript files
	 *
	 * @since BuddyPress (1.7)
	 */
	public function enqueue_scripts() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Locate the BP JS file
		$asset = $this->locate_asset_in_stack( "buddypress{$min}.js", 'js' );

		// Enqueue the global JS, if found - AJAX will not work
		// without it
		if ( isset( $asset['location'], $asset['handle'] ) ) {
			wp_enqueue_script( $asset['handle'], $asset['location'], bp_core_get_js_dependencies(), $this->version );
		}

		/**
		 * Filters core JavaScript strings for internationalization before AJAX usage.
		 *
		 * @since BuddyPress (2.0.0)
		 *
		 * @param array $value Array of key/value pairs for AJAX usage.
		 */
		$params = apply_filters( 'bp_core_get_js_strings', array(
			'accepted'            => __( 'Accepted', 'firmasite' ),
			'close'               => __( 'Close', 'firmasite' ),
			'comments'            => __( 'comments', 'firmasite' ),
			'leave_group_confirm' => __( 'Are you sure you want to leave this group?', 'firmasite' ),
			'mark_as_fav'	      => __( 'Favorite', 'firmasite' ),
			'my_favs'             => __( 'My Favorites', 'firmasite' ),
			'rejected'            => __( 'Rejected', 'firmasite' ),
			'remove_fav'	      => __( 'Remove Favorite', 'firmasite' ),
			'show_all'            => __( 'Show all', 'firmasite' ),
			'show_all_comments'   => __( 'Show all comments for this thread', 'firmasite' ),
			'show_x_comments'     => __( 'Show all %d comments', 'firmasite' ),
			'unsaved_changes'     => __( 'Your profile has unsaved changes. If you leave the page, the changes will be lost.', 'firmasite' ),
			'view'                => __( 'View', 'firmasite' ),
		) );
		wp_localize_script( $asset['handle'], 'BP_DTheme', $params );

		// Maybe enqueue comment reply JS
		if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Maybe enqueue password verify JS (register page or user settings page)
		if ( bp_is_register_page() || ( function_exists( 'bp_is_user_settings_general' ) && bp_is_user_settings_general() ) ) {

			// Locate the Register Page JS file
			$asset = $this->locate_asset_in_stack( "password-verify{$min}.js", 'js', 'bp-legacy-password-verify' );

			$dependencies = array_merge( bp_core_get_js_dependencies(), array(
				'password-strength-meter',
			) );

			// Enqueue script
			wp_enqueue_script( $asset['handle'] . '-password-verify', $asset['location'], $dependencies, $this->version);
		}

		// Star private messages
		if ( bp_is_active( 'messages', 'star' ) && bp_is_user_messages() ) {
			wp_localize_script( $asset['handle'], 'BP_PM_Star', array(
				'strings' => array(
					'text_unstar'  => __( 'Unstar', 'firmasite' ),
					'text_star'    => __( 'Star', 'firmasite' ),
					'title_unstar' => __( 'Starred', 'firmasite' ),
					'title_star'   => __( 'Not starred', 'firmasite' ),
					'title_unstar_thread' => __( 'Remove all starred messages in this thread', 'firmasite' ),
					'title_star_thread'   => __( 'Star the first message in this thread', 'firmasite' ),
				),
				'is_single_thread' => (int) bp_is_messages_conversation(),
				'star_counter'     => 0,
				'unstar_counter'   => 0
			) );
		}
	}

	/**
	 * Get the URL and handle of a web-accessible CSS or JS asset
	 *
	 * We provide two levels of customizability with respect to where CSS
	 * and JS files can be stored: (1) the child theme/parent theme/theme
	 * compat hierarchy, and (2) the "template stack" of /buddypress/css/,
	 * /community/css/, and /css/. In this way, CSS and JS assets can be
	 * overloaded, and default versions provided, in exactly the same way
	 * as corresponding PHP templates.
	 *
	 * We are duplicating some of the logic that is currently found in
	 * bp_locate_template() and the _template_stack() functions. Those
	 * functions were built with PHP templates in mind, and will require
	 * refactoring in order to provide "stack" functionality for assets
	 * that must be accessible both using file_exists() (the file path)
	 * and at a public URI.
	 *
	 * This method is marked private, with the understanding that the
	 * implementation is subject to change or removal in an upcoming
	 * release, in favor of a unified _template_stack() system. Plugin
	 * and theme authors should not attempt to use what follows.
	 *
	 * @since BuddyPress (1.8)
	 * @access private
	 * @param string $file A filename like buddypress.css
	 * @param string $type Optional. Either "js" or "css" (the default).
	 * @param string $script_handle Optional. If set, used as the script name in `wp_enqueue_script`.
	 * @return array An array of data for the wp_enqueue_* function:
	 *   'handle' (eg 'bp-child-css') and a 'location' (the URI of the
	 *   asset)
	 */
	private function locate_asset_in_stack( $file, $type = 'css', $script_handle = '' ) {
		$locations = array();

		// Ensure the assets can be located when running from /src/.
		if ( defined( 'BP_SOURCE_SUBDIRECTORY' ) && BP_SOURCE_SUBDIRECTORY === 'src' ) {
			$file = str_replace( '.min', '', $file );
		}

		// No need to check child if template == stylesheet
		if ( is_child_theme() ) {
			$locations['bp-child'] = array(
				'dir'  => get_stylesheet_directory() . '/assets/',
				'uri'  => get_stylesheet_directory_uri() . '/assets/',
				'file' => str_replace( '.min', '', $file ),
			);
		}

		$locations['bp-parent'] = array(
			'dir'  => get_template_directory() . '/assets/',
			'uri'  => get_template_directory_uri() . '/assets/',
			'file' => str_replace( '.min', '', $file ),
		);

		$locations['bp-legacy'] = array(
			'dir'  => bp_get_theme_compat_dir(),
			'uri'  => bp_get_theme_compat_url(),
			'file' => $file,
		);

		// Subdirectories within the top-level $locations directories
		$subdirs = array(
			'buddypress/' . $type,
			'community/' . $type,
			$type,
		);

		$retval = array();

		foreach ( $locations as $location_type => $location ) {
			foreach ( $subdirs as $subdir ) {
				if ( file_exists( trailingslashit( $location['dir'] ) . trailingslashit( $subdir ) . $location['file'] ) ) {
					$retval['location'] = trailingslashit( $location['uri'] ) . trailingslashit( $subdir ) . $location['file'];
					$retval['handle']   = ( $script_handle ) ? $script_handle : "{$location_type}-{$type}";

					break 2;
				}
			}
		}

		return $retval;
	}

	/**
	 * Put some scripts in the header, like AJAX url for wp-lists
	 *
	 * @since BuddyPress (1.7)
	 */
	public function head_scripts() {
	?>

		<script type="text/javascript">
			/* <![CDATA[ */
			var ajaxurl = '<?php echo bp_core_ajax_url(); ?>';
			/* ]]> */
		</script>

	<?php
	}

	/**
	 * Adds the no-js class to the body tag.
	 *
	 * This function ensures that the <body> element will have the 'no-js' class by default. If you're
	 * using JavaScript for some visual functionality in your theme, and you want to provide noscript
	 * support, apply those styles to body.no-js.
	 *
	 * The no-js class is removed by the JavaScript created in buddypress.js.
	 *
	 * @since BuddyPress (1.7)
	 */
	public function add_nojs_body_class( $classes ) {
		if ( ! in_array( 'no-js', $classes ) )
			$classes[] = 'no-js';

		return array_unique( $classes );
	}

	/**
	 * Load localizations for topic script
	 *
	 * These localizations require information that may not be loaded even by init.
	 *
	 * @since BuddyPress (1.7)
	 */
	public function localize_scripts() {
	}

	/**
	 * Outputs sitewide notices markup in the footer.
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/4802
	 */
	public function sitewide_notices() {
		// Do not show notices if user is not logged in
		if ( ! is_user_logged_in() )
			return;

		// add a class to determine if the admin bar is on or not
		$class = did_action( 'admin_bar_menu' ) ? 'admin-bar-on' : 'admin-bar-off';

		echo '<div id="sitewide-notice" class="' . $class . '">';
		firmasite_bp_message_get_notices();
		echo '</div>';
	}

	/**
	 * Add secondary avatar image to this activity stream's record, if supported.
	 *
	 * @since BuddyPress (1.7)
	 *
	 * @param string $action The text of this activity
	 * @param BP_Activity_Activity $activity Activity object
	 * @package BuddyPress Theme
	 * @return string
	 */
	function secondary_avatars( $action, $activity ) {
		switch ( $activity->component ) {
			case 'groups' :
			case 'friends' :
				// Only insert avatar if one exists
				if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
					$reverse_content = strrev( $action );
					$position        = strpos( $reverse_content, 'a<' );
					$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
				}
				break;
		}

		return $action;
	}

	/**
	 * Filter the default theme compatibility root template hierarchy, and prepend
	 * a page template to the front if it's set.
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/6065
	 *
	 * @since BuddyPress (2.2.0)
	 *
	 * @param  array $templates
	 * @uses   apply_filters() call 'bp_legacy_theme_compat_page_templates_directory_only' and return false
	 *                         to use the defined page template for component's directory and its single items
	 * @return array
	 */
	public function theme_compat_page_templates( $templates = array() ) {

		/**
		 * Filters whether or not we are looking at a directory to determine if to return early.
		 *
		 * @since BuddyPress (2.2.0)
		 *
		 * @param bool $value Whether or not we are viewing a directory.
		 */
		if ( true === (bool) apply_filters( 'bp_legacy_theme_compat_page_templates_directory_only', ! bp_is_directory() ) ) {
			return $templates;
		}

		// No page ID yet
		$page_id = 0;

		// Get the WordPress Page ID for the current view.
		foreach ( (array) buddypress()->pages as $component => $bp_page ) {

			// Handles the majority of components.
			if ( bp_is_current_component( $component ) ) {
				$page_id = (int) $bp_page->id;
			}

			// Stop if not on a user page.
			if ( ! bp_is_user() && ! empty( $page_id ) ) {
				break;
			}

			// The Members component requires an explicit check due to overlapping components.
			if ( bp_is_user() && ( 'members' === $component ) ) {
				$page_id = (int) $bp_page->id;
				break;
			}
		}

		// Bail if no directory page set
		if ( 0 === $page_id ) {
			return $templates;
		}

		// Check for page template
		$page_template = get_page_template_slug( $page_id );

		// Add it to the beginning of the templates array so it takes precedence
		// over the default hierarchy.
		if ( ! empty( $page_template ) ) {

			/**
			 * Check for existence of template before adding it to template
			 * stack to avoid accidentally including an unintended file.
			 *
			 * @see: https://buddypress.trac.wordpress.org/ticket/6190
			 */
			if ( '' !== locate_template( $page_template ) ) {
				array_unshift( $templates, $page_template );
			}
		}

		return $templates;
	}
}
new BP_Legacy();
endif;

/**
 * Add the Create a Group button to the Groups directory title.
 *
 * bp-legacy puts the Create a Group button into the page title, to mimic
 * the behavior of bp-default.
 *
 * @since BuddyPress (2.0.0)
 * @todo Deprecate
 *
 * @param string $title Groups directory title.
 * @return string
 */
function bp_legacy_theme_group_create_button( $title ) {
	return $title . ' ' . bp_get_group_create_button();
}

/**
 * Add the Create a Group nav to the Groups directory navigation.
 *
 * bp-legacy puts the Create a Group nav at the last position of
 * the Groups directory navigation.
 *
 * @since BuddyPress (2.2.0)
 *
 * @uses   bp_group_create_nav_item() to output the create a Group nav item
 * @return string
 */
function bp_legacy_theme_group_create_nav() {
	bp_group_create_nav_item();
}

/**
 * Add the Create a Site button to the Sites directory title.
 *
 * bp-legacy puts the Create a Site button into the page title, to mimic
 * the behavior of bp-default.
 *
 * @since BuddyPress (2.0.0)
 * @todo Deprecate
 *
 * @param string $title Sites directory title.
 * @return string
 */
function bp_legacy_theme_blog_create_button( $title ) {
	return $title . ' ' . bp_get_blog_create_button();
}

/**
 * Add the Create a Site nav to the Sites directory navigation.
 *
 * bp-legacy puts the Create a Site nav at the last position of
 * the Sites directory navigation.
 *
 * @since BuddyPress (2.2.0)
 *
 * @uses   bp_blog_create_nav_item() to output the Create a Site nav item
 * @return string
 */
function bp_legacy_theme_blog_create_nav() {
	bp_blog_create_nav_item();
}

/**
 * This function looks scarier than it actually is. :)
 * Each object loop (activity/members/groups/blogs/forums) contains default
 * parameters to show specific information based on the page we are currently
 * looking at.
 *
 * The following function will take into account any cookies set in the JS and
 * allow us to override the parameters sent. That way we can change the results
 * returned without reloading the page.
 *
 * By using cookies we can also make sure that user settings are retained
 * across page loads.
 *
 * @return string Query string for the component loops
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_querystring( $query_string, $object ) {
	if ( empty( $object ) )
		return '';

	// Set up the cookies passed on this AJAX request. Store a local var to avoid conflicts
	if ( ! empty( $_POST['cookie'] ) ) {
		$_BP_COOKIE = wp_parse_args( str_replace( '; ', '&', urldecode( $_POST['cookie'] ) ) );
	} else {
		$_BP_COOKIE = &$_COOKIE;
	}

	$qs = array();

	/**
	 * Check if any cookie values are set. If there are then override the
	 * default params passed to the template loop.
	 */

	// Activity stream filtering on action
	if ( ! empty( $_BP_COOKIE['bp-' . $object . '-filter'] ) && '-1' != $_BP_COOKIE['bp-' . $object . '-filter'] ) {
		$qs[] = 'type='   . $_BP_COOKIE['bp-' . $object . '-filter'];
		$qs[] = 'action=' . $_BP_COOKIE['bp-' . $object . '-filter'];
	}

	if ( ! empty( $_BP_COOKIE['bp-' . $object . '-scope'] ) ) {
		if ( 'personal' == $_BP_COOKIE['bp-' . $object . '-scope'] ) {
			$user_id = ( bp_displayed_user_id() ) ? bp_displayed_user_id() : bp_loggedin_user_id();
			$qs[] = 'user_id=' . $user_id;
		}

		// Activity stream scope only on activity directory.
		if ( 'all' != $_BP_COOKIE['bp-' . $object . '-scope'] && ! bp_displayed_user_id() && ! bp_is_single_item() )
			$qs[] = 'scope=' . $_BP_COOKIE['bp-' . $object . '-scope'];
	}

	// If page and search_terms have been passed via the AJAX post request, use those.
	if ( ! empty( $_POST['page'] ) && '-1' != $_POST['page'] )
		$qs[] = 'page=' . absint( $_POST['page'] );

	// excludes activity just posted and avoids duplicate ids
	if ( ! empty( $_POST['exclude_just_posted'] ) ) {
		$just_posted = wp_parse_id_list( $_POST['exclude_just_posted'] );
		$qs[] = 'exclude=' . implode( ',', $just_posted );
	}

	// to get newest activities
	if ( ! empty( $_POST['offset'] ) ) {
		$qs[] = 'offset=' . intval( $_POST['offset'] );
	}

	$object_search_text = bp_get_search_default_text( $object );
 	if ( ! empty( $_POST['search_terms'] ) && $object_search_text != $_POST['search_terms'] && 'false' != $_POST['search_terms'] && 'undefined' != $_POST['search_terms'] )
		$qs[] = 'search_terms=' . urlencode( $_POST['search_terms'] );

	// Now pass the querystring to override default values.
	$query_string = empty( $qs ) ? '' : join( '&', (array) $qs );

	$object_filter = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-filter'] ) )
		$object_filter = $_BP_COOKIE['bp-' . $object . '-filter'];

	$object_scope = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-scope'] ) )
		$object_scope = $_BP_COOKIE['bp-' . $object . '-scope'];

	$object_page = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-page'] ) )
		$object_page = $_BP_COOKIE['bp-' . $object . '-page'];

	$object_search_terms = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-search-terms'] ) )
		$object_search_terms = $_BP_COOKIE['bp-' . $object . '-search-terms'];

	$object_extras = '';
	if ( isset( $_BP_COOKIE['bp-' . $object . '-extras'] ) )
		$object_extras = $_BP_COOKIE['bp-' . $object . '-extras'];

	/**
	 * Filters the AJAX query string for the component loops.
	 *
	 * @since BuddyPress (1.7.0)
	 *
	 * @param string $query_string        The query string we are working with.
	 * @param string $object              The type of page we are on.
	 * @param string $object_filter       The current object filter.
	 * @param string $object_scope        The current object scope.
	 * @param string $object_page         The current object page.
	 * @param string $object_search_terms The current object search terms.
	 * @param string $object_extras       The current object extras.
	 */
	return apply_filters( 'bp_legacy_theme_ajax_querystring', $query_string, $object, $object_filter, $object_scope, $object_page, $object_search_terms, $object_extras );
}

/**
 * Load the template loop for the current object.
 *
 * @return string Prints template loop for the specified object
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_object_template_loader() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Bail if no object passed
	if ( empty( $_POST['object'] ) )
		return;

	// Sanitize the object
	$object = sanitize_title( $_POST['object'] );

	// Bail if object is not an active component to prevent arbitrary file inclusion
	if ( ! bp_is_active( $object ) )
		return;

 	/**
	 * AJAX requests happen too early to be seen by bp_update_is_directory()
	 * so we do it manually here to ensure templates load with the correct
	 * context. Without this check, templates will load the 'single' version
	 * of themselves rather than the directory version.
	 */
	if ( ! bp_current_action() )
		bp_update_is_directory( true, bp_current_component() );

	$template_part = $object . '/' . $object . '-loop';

	// The template part can be overridden by the calling JS function
	if ( ! empty( $_POST['template'] ) ) {
		$template_part = sanitize_option( 'upload_path', $_POST['template'] );
	}

	// Locate the object template
	bp_get_template_part( $template_part );
	exit();
}

/**
 * Load messages template loop when searched on the private message page
 *
 * @return string Prints template loop for the Messages component
 * @since BuddyPress (1.6)
 */
function bp_legacy_theme_messages_template_loader() {
	bp_get_template_part( 'members/single/messages/messages-loop' );
	exit();
}

/**
 * Load group invitations loop to handle pagination requests sent via AJAX.
 *
 * @since BuddyPress (2.0.0)
 */
function bp_legacy_theme_invite_template_loader() {
	bp_get_template_part( 'groups/single/invites-loop' );
	exit();
}

/**
 * Load group membership requests loop to handle pagination requests sent via AJAX.
 *
 * @since BuddyPress (2.0.0)
 */
function bp_legacy_theme_requests_template_loader() {
	bp_get_template_part( 'groups/single/requests-loop' );
	exit();
}

/**
 * Load the activity loop template when activity is requested via AJAX,
 *
 * @return string JSON object containing 'contents' (output of the template loop
 * for the Activity component) and 'feed_url' (URL to the relevant RSS feed).
 *
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_activity_template_loader() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	$scope = '';
	if ( ! empty( $_POST['scope'] ) )
		$scope = $_POST['scope'];

	// We need to calculate and return the feed URL for each scope
	switch ( $scope ) {
		case 'friends':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/friends/feed/';
			break;
		case 'groups':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/groups/feed/';
			break;
		case 'favorites':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/feed/';
			break;
		case 'mentions':
			$feed_url = bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/feed/';
			bp_activity_clear_new_mentions( bp_loggedin_user_id() );
			break;
		default:
			$feed_url = home_url( bp_get_activity_root_slug() . '/feed/' );
			break;
	}

	// Buffer the loop in the template to a var for JS to spit out.
	ob_start();
	bp_get_template_part( 'activity/activity-loop' );
	$result['contents'] = ob_get_contents();

	/**
	 * Filters the feed URL for when activity is requested via AJAX.
	 *
	 * @since BuddyPress (1.7.0)
	 *
	 * @param string $feed_url URL for the feed to be used.
	 * @param string $scope    Scope for the activity request.
	 */
	$result['feed_url'] = apply_filters( 'bp_legacy_theme_activity_feed_url', $feed_url, $scope );
	ob_end_clean();

	exit( json_encode( $result ) );
}

/**
 * Processes Activity updates received via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_post_update() {
	$bp = buddypress();

	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'post_update', '_wpnonce_post_update' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	if ( empty( $_POST['content'] ) )
		exit( '-1<div class="clearfix"></div><div id="message" class="error alert alert-danger bp-ajax-message"><p>' . __( 'Please enter some content to post.', 'firmasite' ) . '</p></div>' );

	$activity_id = 0;
	if ( empty( $_POST['object'] ) && bp_is_active( 'activity' ) ) {
		$activity_id = bp_activity_post_update( array( 'content' => $_POST['content'] ) );

	} elseif ( $_POST['object'] == 'groups' ) {
		if ( ! empty( $_POST['item_id'] ) && bp_is_active( 'groups' ) )
			$activity_id = groups_post_update( array( 'content' => $_POST['content'], 'group_id' => $_POST['item_id'] ) );

	} else {

		/** This filter is documented in bp-activity/bp-activity-actions.php */
		$activity_id = apply_filters( 'bp_activity_custom_update', false, $_POST['object'], $_POST['item_id'], $_POST['content'] );
	}

	if ( empty( $activity_id ) )
		exit( '-1<div class="clearfix"></div><div id="message" class="error alert alert-danger bp-ajax-message"><p>' . __( 'There was a problem posting your update. Please try again.', 'firmasite' ) . '</p></div>' );

	$last_recorded = ! empty( $_POST['since'] ) ? date( 'Y-m-d H:i:s', intval( $_POST['since'] ) ) : 0;
	if ( $last_recorded ) {
		$activity_args = array( 'since' => $last_recorded );
		$bp->activity->last_recorded = $last_recorded;
		add_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10, 1 );
	} else {
		$activity_args = array( 'include' => $activity_id );
	}

	if ( bp_has_activities ( $activity_args ) ) {
		while ( bp_activities() ) {
			bp_the_activity();
			bp_get_template_part( 'activity/entry' );
		}
	}

	if ( ! empty( $last_recorded ) ) {
		remove_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10, 1 );
	}

	exit;
}

/**
 * Posts new Activity comments received via a POST request.
 *
 * @global BP_Activity_Template $activities_template
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_new_activity_comment() {
	global $activities_template;

	$bp = buddypress();

	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
		return;
	}

	// Check the nonce
	check_admin_referer( 'new_activity_comment', '_wpnonce_new_activity_comment' );

	if ( ! is_user_logged_in() ) {
		exit( '-1' );
	}

	$feedback = __( 'There was an error posting your reply. Please try again.', 'firmasite' );

	if ( empty( $_POST['content'] ) ) {
		exit( '-1<div class="clearfix"></div><div id="message" class="error alert alert-danger bp-ajax-message"><p>' . esc_html__( 'Please do not leave the comment area blank.', 'firmasite' ) . '</p></div>' );
	}

	if ( empty( $_POST['form_id'] ) || empty( $_POST['comment_id'] ) || ! is_numeric( $_POST['form_id'] ) || ! is_numeric( $_POST['comment_id'] ) ) {
		exit( '-1<div class="clearfix"></div><div id="message" class="error alert alert-danger bp-ajax-message"><p>' . esc_html( $feedback ) . '</p></div>' );
	}

	$comment_id = bp_activity_new_comment( array(
		'activity_id' => $_POST['form_id'],
		'content'     => $_POST['content'],
		'parent_id'   => $_POST['comment_id'],
	) );

	if ( ! $comment_id ) {
		if ( ! empty( $bp->activity->errors['new_comment'] ) && is_wp_error( $bp->activity->errors['new_comment'] ) ) {
			$feedback = $bp->activity->errors['new_comment']->get_error_message();
			unset( $bp->activity->errors['new_comment'] );
		}

		exit( '-1<div class="clearfix"></div><div id="message" class="error alert alert-danger bp-ajax-message"><p>' . esc_html( $feedback ) . '</p></div>' );
	}

	// Load the new activity item into the $activities_template global
	bp_has_activities( 'display_comments=stream&hide_spam=false&show_hidden=true&include=' . $comment_id );

	// Swap the current comment with the activity item we just loaded
	if ( isset( $activities_template->activities[0] ) ) {
		$activities_template->activity = new stdClass();
		$activities_template->activity->id              = $activities_template->activities[0]->item_id;
		$activities_template->activity->current_comment = $activities_template->activities[0];

		// Because the whole tree has not been loaded, we manually
		// determine depth
		$depth = 1;
		$parent_id = (int) $activities_template->activities[0]->secondary_item_id;
		while ( $parent_id !== (int) $activities_template->activities[0]->item_id ) {
			$depth++;
			$p_obj = new BP_Activity_Activity( $parent_id );
			$parent_id = (int) $p_obj->secondary_item_id;
		}
		$activities_template->activity->current_comment->depth = $depth;
	}

	// get activity comment template part
	bp_get_template_part( 'activity/comment' );

	unset( $activities_template );
	exit;
}

/**
 * Deletes an Activity item received via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_delete_activity() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'bp_activity_delete_link' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	if ( empty( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) )
		exit( '-1' );

	$activity = new BP_Activity_Activity( (int) $_POST['id'] );

	// Check access
	if ( ! bp_activity_user_can_delete( $activity ) )
		exit( '-1' );

	/** This action is documented in bp-activity/bp-activity-actions.php */
	do_action( 'bp_activity_before_action_delete_activity', $activity->id, $activity->user_id );

	if ( ! bp_activity_delete( array( 'id' => $activity->id, 'user_id' => $activity->user_id ) ) )
		exit( '-1<div class="clearfix"></div><div id="message" class="error alert alert-danger bp-ajax-message"><p>' . __( 'There was a problem when deleting. Please try again.', 'firmasite' ) . '</p></div>' );

	/** This action is documented in bp-activity/bp-activity-actions.php */
	do_action( 'bp_activity_action_delete_activity', $activity->id, $activity->user_id );
	exit;
}

/**
 * Deletes an Activity comment received via a POST request
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_delete_activity_comment() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce
	check_admin_referer( 'bp_activity_delete_link' );

	if ( ! is_user_logged_in() )
		exit( '-1' );

	$comment = new BP_Activity_Activity( $_POST['id'] );

	// Check access
	if ( ! bp_current_user_can( 'bp_moderate' ) && $comment->user_id != bp_loggedin_user_id() )
		exit( '-1' );

	if ( empty( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) )
		exit( '-1' );

	/** This action is documented in bp-activity/bp-activity-actions.php */
	do_action( 'bp_activity_before_action_delete_activity', $_POST['id'], $comment->user_id );

	if ( ! bp_activity_delete_comment( $comment->item_id, $comment->id ) )
		exit( '-1<div class="clearfix"></div><div id="message" class="error alert alert-danger bp-ajax-message"><p>' . __( 'There was a problem when deleting. Please try again.', 'firmasite' ) . '</p></div>' );

	/** This action is documented in bp-activity/bp-activity-actions.php */
	do_action( 'bp_activity_action_delete_activity', $_POST['id'], $comment->user_id );
	exit;
}

/**
 * AJAX spam an activity item or comment
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.6)
 */
function bp_legacy_theme_spam_activity() {
	$bp = buddypress();

	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check that user is logged in, Activity Streams are enabled, and Akismet is present.
	if ( ! is_user_logged_in() || ! bp_is_active( 'activity' ) || empty( $bp->activity->akismet ) )
		exit( '-1' );

	// Check an item ID was passed
	if ( empty( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) )
		exit( '-1' );

	// Is the current user allowed to spam items?
	if ( ! bp_activity_user_can_mark_spam() )
		exit( '-1' );

	// Load up the activity item
	$activity = new BP_Activity_Activity( (int) $_POST['id'] );
	if ( empty( $activity->component ) )
		exit( '-1' );

	// Check nonce
	check_admin_referer( 'bp_activity_akismet_spam_' . $activity->id );

	/** This action is documented in bp-activity/bp-activity-actions.php */
	do_action( 'bp_activity_before_action_spam_activity', $activity->id, $activity );

	// Mark as spam
	bp_activity_mark_as_spam( $activity );
	$activity->save();

	/** This action is documented in bp-activity/bp-activity-actions.php */
	do_action( 'bp_activity_action_spam_activity', $activity->id, $activity->user_id );
	exit;
}

/**
 * Mark an activity as a favourite via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_mark_activity_favorite() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( bp_activity_add_user_favorite( $_POST['id'] ) )
		_e( 'Remove Favorite', 'firmasite' );
	else
		_e( 'Favorite', 'firmasite' );

	exit;
}

/**
 * Un-favourite an activity via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_unmark_activity_favorite() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( bp_activity_remove_user_favorite( $_POST['id'] ) )
		_e( 'Favorite', 'firmasite' );
	else
		_e( 'Remove Favorite', 'firmasite' );

	exit;
}

/**
 * Fetches an activity's full, non-excerpted content via a POST request.
 * Used for the 'Read More' link on long activity items.
 *
 * @return string HTML
 * @since BuddyPress (1.5)
 */
function bp_legacy_theme_get_single_activity_content() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	$activity_array = bp_activity_get_specific( array(
		'activity_ids'     => $_POST['activity_id'],
		'display_comments' => 'stream'
	) );

	$activity = ! empty( $activity_array['activities'][0] ) ? $activity_array['activities'][0] : false;

	if ( empty( $activity ) )
		exit; // @todo: error?

	/**
	 * Fires before the return of an activity's full, non-excerpted content via a POST request.
	 *
	 * @since BuddyPress (1.7.0)
	 *
	 * @param string $activity Activity content. Passed by reference.
	 */
	do_action_ref_array( 'bp_legacy_theme_get_single_activity_content', array( &$activity ) );

	// Activity content retrieved through AJAX should run through normal filters, but not be truncated
	remove_filter( 'bp_get_activity_content_body', 'bp_activity_truncate_entry', 5 );

	/** This filter is documented in bp-activity/bp-activity-template.php */
	$content = apply_filters( 'bp_get_activity_content_body', $activity->content );

	exit( $content );
}

/**
 * Invites a friend to join a group via a POST request.
 *
 * @since BuddyPress (1.2)
 * @todo Audit return types
 */
function bp_legacy_theme_ajax_invite_user() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_ajax_referer( 'groups_invite_uninvite_user' );

	if ( ! $_POST['friend_id'] || ! $_POST['friend_action'] || ! $_POST['group_id'] )
		return;

	if ( ! bp_groups_user_can_send_invites( $_POST['group_id'] ) )
		return;

	if ( ! friends_check_friendship( bp_loggedin_user_id(), $_POST['friend_id'] ) )
		return;

	$group_id = (int) $_POST['group_id'];
	$friend_id = (int) $_POST['friend_id'];

	if ( 'invite' == $_POST['friend_action'] ) {
		$group = groups_get_group( $group_id );

		// Users who have previously requested membership do not need
		// another invitation created for them
		if ( BP_Groups_Member::check_for_membership_request( $friend_id, $group_id ) ) {
			$user_status = 'is_pending';

		// Create the user invitation
		} elseif ( groups_invite_user( array( 'user_id' => $friend_id, 'group_id' => $group_id ) ) ) {
			$user_status = 'is_invited';

		// Miscellaneous failure
		} else {
			return;
		}

		$user = new BP_Core_User( $friend_id );

		$uninvite_url = bp_is_current_action( 'create' )
			? bp_get_groups_directory_permalink() . 'create/step/group-invites/?user_id=' . $friend_id
			: bp_get_group_permalink( $group )    . 'send-invites/remove/' . $friend_id;

		echo '<li id="uid-' . esc_attr( $user->id ) . '">';
		echo $user->avatar_thumb;
		echo '<h4>' . $user->user_link . '</h4>';
		echo '<span class="activity">' . esc_attr( $user->last_active ) . '</span>';
		echo '<div class="action">
				<a class="button remove btn btn-default btn-xs" href="' . wp_nonce_url( $uninvite_url, 'groups_invite_uninvite_user' ) . '" id="uid-' . esc_attr( $user->id ) . '">' . __( 'Remove Invite', 'firmasite' ) . '</a>
			  </div>';

		if ( 'is_pending' == $user_status ) {
			echo '<p class="description">' . sprintf( __( '%s has previously requested to join this group. Sending an invitation will automatically add the member to the group.', 'firmasite' ), $user->user_link ) . '</p>';
		}

		echo '</li>';
		exit;

	} elseif ( 'uninvite' == $_POST['friend_action'] ) {
		// Users who have previously requested membership should not
		// have their requests deleted on the "uninvite" action
		if ( BP_Groups_Member::check_for_membership_request( $friend_id, $group_id ) ) {
			return;
		}

		// Remove the unsent invitation
		if ( ! groups_uninvite_user( $friend_id, $group_id ) ) {
			return;
		}

		exit;

	} else {
		return;
	}
}

/**
 * Friend/un-friend a user via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_addremove_friend() {

	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Cast fid as an integer
	$friend_id = (int) $_POST['fid'];

	// Trying to cancel friendship
	if ( 'is_friend' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
		check_ajax_referer( 'friends_remove_friend' );

		if ( ! friends_remove_friend( bp_loggedin_user_id(), $friend_id ) ) {
			echo __( 'Friendship could not be canceled.', 'firmasite' );
		} else {
			echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="add btn btn-default btn-xs" rel="add" title="' . __( 'Add Friend', 'firmasite' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $friend_id, 'friends_add_friend' ) . '">' . __( 'Add Friend', 'firmasite' ) . '</a>';
		}

	// Trying to request friendship
	} elseif ( 'not_friends' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
		check_ajax_referer( 'friends_add_friend' );

		if ( ! friends_add_friend( bp_loggedin_user_id(), $friend_id ) ) {
			echo __(' Friendship could not be requested.', 'firmasite' );
		} else {
			echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="remove btn btn-default btn-xs" rel="remove" title="' . __( 'Cancel Friendship Request', 'firmasite' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/cancel/' . $friend_id . '/', 'friends_withdraw_friendship' ) . '" class="requested">' . __( 'Cancel Friendship Request', 'firmasite' ) . '</a>';
		}

	// Trying to cancel pending request
	} elseif ( 'pending' == BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $friend_id ) ) {
		check_ajax_referer( 'friends_withdraw_friendship' );

		if ( friends_withdraw_friendship( bp_loggedin_user_id(), $friend_id ) ) {
			echo '<a id="friend-' . esc_attr( $friend_id ) . '" class="add btn btn-default btn-xs" rel="add" title="' . __( 'Add Friend', 'firmasite' ) . '" href="' . wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $friend_id, 'friends_add_friend' ) . '">' . __( 'Add Friend', 'firmasite' ) . '</a>';
		} else {
			echo __("Friendship request could not be cancelled.", 'firmasite');
		}

	// Request already pending
	} else {
		echo __( 'Request Pending', 'firmasite' );
	}

	exit;
}

/**
 * Accept a user friendship request via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_accept_friendship() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_admin_referer( 'friends_accept_friendship' );

	if ( ! friends_accept_friendship( (int) $_POST['id'] ) )
		echo "-1<div class='clearfix'></div><div id='message' class='error alert alert-danger'><p>" . __( 'There was a problem accepting that request. Please try again.', 'firmasite' ) . '</p></div>';

	exit;
}

/**
 * Reject a user friendship request via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_reject_friendship() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_admin_referer( 'friends_reject_friendship' );

	if ( ! friends_reject_friendship( (int) $_POST['id'] ) )
		echo "-1<div class='clearfix'></div><div id='message' class='error alert alert-danger'><p>" . __( 'There was a problem rejecting that request. Please try again.', 'firmasite' ) . '</p></div>';

	exit;
}

/**
 * Join or leave a group when clicking the "join/leave" button via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_joinleave_group() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Cast gid as integer
	$group_id = (int) $_POST['gid'];

	if ( groups_is_user_banned( bp_loggedin_user_id(), $group_id ) )
		return;

	if ( ! $group = groups_get_group( array( 'group_id' => $group_id ) ) )
		return;

	if ( ! groups_is_user_member( bp_loggedin_user_id(), $group->id ) ) {
		if ( 'public' == $group->status ) {
			check_ajax_referer( 'groups_join_group' );

			if ( ! groups_join_group( $group->id ) ) {
				_e( 'Error joining group', 'firmasite' );
			} else {
				echo '<a id="group-' . esc_attr( $group->id ) . '" class="leave-group btn btn-default btn-xs" rel="leave" title="' . __( 'Leave Group', 'firmasite' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' ) . '">' . __( 'Leave Group', 'firmasite' ) . '</a>';
			}

		} elseif ( 'private' == $group->status ) {

			// If the user has already been invited, then this is
			// an Accept Invitation button
			if ( groups_check_user_has_invite( bp_loggedin_user_id(), $group->id ) ) {
				check_ajax_referer( 'groups_accept_invite' );

				if ( ! groups_accept_invite( bp_loggedin_user_id(), $group->id ) ) {
					_e( 'Error requesting membership', 'firmasite' );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="leave-group btn btn-default btn-xs" rel="leave" title="' . __( 'Leave Group', 'firmasite' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' ) . '">' . __( 'Leave Group', 'firmasite' ) . '</a>';
				}

			// Otherwise, it's a Request Membership button
			} else {
				check_ajax_referer( 'groups_request_membership' );

				if ( ! groups_send_membership_request( bp_loggedin_user_id(), $group->id ) ) {
					_e( 'Error requesting membership', 'firmasite' );
				} else {
					echo '<a id="group-' . esc_attr( $group->id ) . '" class="membership-requested btn btn-default btn-xs" rel="membership-requested" title="' . __( 'Membership Requested', 'firmasite' ) . '" href="' . bp_get_group_permalink( $group ) . '">' . __( 'Membership Requested', 'firmasite' ) . '</a>';
				}
			}
		}

	} else {
		check_ajax_referer( 'groups_leave_group' );

		if ( ! groups_leave_group( $group->id ) ) {
			_e( 'Error leaving group', 'firmasite' );
		} elseif ( 'public' == $group->status ) {
			echo '<a id="group-' . esc_attr( $group->id ) . '" class="join-group btn btn-default btn-xs" rel="join" title="' . __( 'Join Group', 'firmasite' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'join', 'groups_join_group' ) . '">' . __( 'Join Group', 'firmasite' ) . '</a>';
		} elseif ( 'private' == $group->status ) {
			echo '<a id="group-' . esc_attr( $group->id ) . '" class="request-membership btn btn-default btn-xs" rel="join" title="' . __( 'Request Membership', 'firmasite' ) . '" href="' . wp_nonce_url( bp_get_group_permalink( $group ) . 'request-membership', 'groups_send_membership_request' ) . '">' . __( 'Request Membership', 'firmasite' ) . '</a>';
		}
	}

	exit;
}

/**
 * Close and keep closed site wide notices from an admin in the sidebar, via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_close_notice() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset( $_POST['notice_id'] ) ) {
		echo "-1<div class='clearfix'></div><div id='message' class='error alert alert-danger'><p>" . __( 'There was a problem closing the notice.', 'firmasite' ) . '</p></div>';

	} else {
		$user_id      = get_current_user_id();
		$notice_ids   = bp_get_user_meta( $user_id, 'closed_notices', true );
		$notice_ids[] = (int) $_POST['notice_id'];

		bp_update_user_meta( $user_id, 'closed_notices', $notice_ids );
	}

	exit;
}

/**
 * Send a private message reply to a thread via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_messages_send_reply() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	check_ajax_referer( 'messages_send_message' );

	$result = messages_new_message( array( 'thread_id' => (int) $_REQUEST['thread_id'], 'content' => $_REQUEST['content'] ) );

	if ( !empty( $result ) ) {

		// pretend we're in the message loop
		global $thread_template;

		bp_thread_has_messages( array( 'thread_id' => (int) $_REQUEST['thread_id'] ) );

		// set the current message to the 2nd last
		$thread_template->message = end( $thread_template->thread->messages );
		$thread_template->message = prev( $thread_template->thread->messages );

		// set current message to current key
		$thread_template->current_message = key( $thread_template->thread->messages );

		// now manually iterate message like we're in the loop
		bp_thread_the_message();

		// manually call oEmbed
		// this is needed because we're not at the beginning of the loop
		bp_messages_embed()
	?>

		<div class="message-box new-message <?php bp_the_thread_message_css_class(); ?>">
			<div class="message-metadata">
				<?php

				/**
				 * Fires before the single message header is displayed.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_before_message_meta' ); ?>
				<?php echo bp_loggedin_user_avatar( 'type=thumb&width=30&height=30' ); ?>

				<strong><a href="<?php echo bp_loggedin_user_domain(); ?>"><?php bp_loggedin_user_fullname(); ?></a> <span class="activity"><?php printf( __( 'Sent %s', 'firmasite' ), bp_core_time_since( bp_core_current_time() ) ); ?></span></strong>

				<?php

				/**
				 * Fires after the single message header is displayed.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_after_message_meta' ); ?>
			</div>

			<?php

			/**
			 * Fires before the message content for a private message.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_before_message_content' ); ?>

			<div class="message-content">
				<?php bp_the_thread_message_content(); ?>
			</div>

			<?php

			/**
			 * Fires after the message content for a private message.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_after_message_content' ); ?>

			<div class="clear clearfix"></div>
		</div>
	<?php
		// clean up the loop
		bp_thread_messages();

	} else {
		echo "-1<div class='clearfix'></div><div id='message' class='error alert alert-danger'><p>" . __( 'There was a problem sending that reply. Please try again.', 'firmasite' ) . '</p></div>';
	}

	exit;
}

/**
 * Mark a private message as unread in your inbox via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_message_markunread() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset($_POST['thread_ids']) ) {
		echo "-1<div class='clearfix'></div><div id='message' class='error alert alert-danger'><p>" . __( 'There was a problem marking messages as unread.', 'firmasite' ) . '</p></div>';

	} else {
		$thread_ids = explode( ',', $_POST['thread_ids'] );

		for ( $i = 0, $count = count( $thread_ids ); $i < $count; ++$i ) {
			BP_Messages_Thread::mark_as_unread( (int) $thread_ids[$i] );
		}
	}

	exit;
}

/**
 * Mark a private message as read in your inbox via a POST request.
 *
 * @return mixed String on error, void on success
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_message_markread() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset($_POST['thread_ids']) ) {
		echo "-1<div class='clearfix'></div><div id='message' class='error alert alert-danger'><p>" . __('There was a problem marking messages as read.', 'firmasite' ) . '</p></div>';

	} else {
		$thread_ids = explode( ',', $_POST['thread_ids'] );

		for ( $i = 0, $count = count( $thread_ids ); $i < $count; ++$i ) {
			BP_Messages_Thread::mark_as_read( (int) $thread_ids[$i] );
		}
	}

	exit;
}

/**
 * Delete a private message(s) in your inbox via a POST request.
 *
 * @return string HTML
 * @since BuddyPress (1.2)
 */
function bp_legacy_theme_ajax_messages_delete() {
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	if ( ! isset($_POST['thread_ids']) ) {
		echo "-1<div class='clearfix'></div><div id='message' class='error alert alert-danger'><p>" . __( 'There was a problem deleting messages.', 'firmasite' ) . '</p></div>';

	} else {
		$thread_ids = wp_parse_id_list( $_POST['thread_ids'] );
		messages_delete_thread( $thread_ids );

		_e( 'Messages deleted.', 'firmasite' );
	}

	exit;
}

/**
 * AJAX handler for autocomplete.
 *
 * Displays friends only, unless BP_MESSAGES_AUTOCOMPLETE_ALL is defined.
 *
 * @since BuddyPress (1.2.0)
 *
 * @return string HTML.
 */
function bp_legacy_theme_ajax_messages_autocomplete_results() {

	/**
	 * Filters the max results default value for ajax messages autocomplete results.
	 *
	 * @since BuddyPress (1.5.0)
	 *
	 * @param int $value Max results for autocomplete. Default 10.
	 */
	$limit = isset( $_GET['limit'] ) ? absint( $_GET['limit'] )          : (int) apply_filters( 'bp_autocomplete_max_results', 10 );
	$term  = isset( $_GET['q'] )     ? sanitize_text_field( $_GET['q'] ) : '';

	// Include everyone in the autocomplete, or just friends?
	if ( bp_is_current_component( bp_get_messages_slug() ) ) {
		$only_friends = ( buddypress()->messages->autocomplete_all === false );
	} else {
		$only_friends = true;
	}

	$suggestions = bp_core_get_suggestions( array(
		'limit'        => $limit,
		'only_friends' => $only_friends,
		'term'         => $term,
		'type'         => 'members',
	) );

	if ( $suggestions && ! is_wp_error( $suggestions ) ) {
		foreach ( $suggestions as $user ) {

			// Note that the final line break acts as a delimiter for the
			// autocomplete JavaScript and thus should not be removed
			printf( '<span id="%s" href="#"></span><img src="%s" style="width: 15px"> &nbsp; %s (%s)' . "\n",
				esc_attr( 'link-' . $user->ID ),
				esc_url( $user->image ),
				esc_html( $user->name ),
				esc_html( $user->ID )
			);
		}
	}

	exit;
}

/**
 * AJAX callback to set a message's star status.
 *
 * @since BuddyPress (2.3.0)
 */
function bp_legacy_theme_ajax_messages_star_handler() {
	if ( false === bp_is_active( 'messages', 'star' ) || empty( $_POST['message_id'] ) ) {
		return;
	}

	// Check nonce
	check_ajax_referer( 'bp-messages-star-' . (int) $_POST['message_id'], 'nonce' );

	// Check capability
	if ( ! is_user_logged_in() || ! bp_core_can_edit_settings() ) {
		return;
	}

	if ( true === bp_messages_star_set_action( array(
		'action'     => $_POST['star_status'],
		'message_id' => (int) $_POST['message_id'],
		'bulk'       => ! empty( $_POST['bulk'] ) ? true : false
	 ) ) ) {
		echo '1';
		die();
	}

	echo '-1';
	die();
}
