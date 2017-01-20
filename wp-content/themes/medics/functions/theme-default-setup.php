<?php 
/*
 * thumbnail list
*/ 
function medics_thumbnail_image($content) {

    if( has_post_thumbnail() )
         return the_post_thumbnail( 'thumbnail' ); 
}
/*
 * medics Title
*/
function medics_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$medics_site_description = get_bloginfo( 'description', 'display' );
	if ( $medics_site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $medics_site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'medics' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'medics_wp_title', 10, 2 );

/**
 * Add default menu style if menu is not set from the backend.
 */
function medics_add_menuid ($page_markup) {
preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $medics_matches);
$medics_divclass = '';
if(!empty($medics_matches)) { $medics_divclass = $medics_matches[1]; }
$medics_toreplace = array('<div class="'.$medics_divclass.' pull-right-res">', '</div>');
$medics_replace = array('<div class="nav navbar-nav menu">', '</div>');
$medics_new_markup = str_replace($medics_toreplace,$medics_replace, $page_markup);
$medics_new_markup= preg_replace('/<ul/', '<ul class="nav navbar-nav medics-menu"', $medics_new_markup);
return $medics_new_markup; }
add_filter('wp_page_menu', 'medics_add_menuid');

/*
 * medics Main Sidebar
*/
function medics_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'medics' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'medics' ),
		'before_widget' => '<aside id="%1$s" class="sidebar-widget widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="sidebar-title"><h1 class="sidebar-title">',
		'after_title'   => '</h1></div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Area One', 'medics' ),
		'id'            => 'footer-1',
		'description'   => __( 'Footer Area One that appears on footer.', 'medics' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget footer-widget-1 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="footer-blogs">',
		'after_title'   => '</h1><div class="footer-title-line"></div>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Two', 'medics' ),
		'id'            => 'footer-2',
		'description'   => __( 'Footer Area Two that appears on footer.', 'medics' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget footer-widget-2 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="footer-blogs">',
		'after_title'   => '</h1><div class="footer-title-line"></div>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Three', 'medics' ),
		'id'            => 'footer-3',
		'description'   => __( 'Footer Area Three that appears on footer.', 'medics' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget footer-widget-3 no-padding %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="footer-blogs">',
		'after_title'   => '</h1><div class="footer-title-line"></div>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Area Four', 'medics' ),
		'id'            => 'footer-4',
		'description'   => __( 'Footer Area Four that appears on footer.', 'medics' ),
		'before_widget' => '<aside id="%1$s" class="widget footer-widget footer-widget-4 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="footer-blogs">',
		'after_title'   => '</h1><div class="footer-title-line"></div>',
	) );
}
add_action( 'widgets_init', 'medics_widgets_init' );

/*
 * medics Set up post entry meta.
 *
 * Meta information for current post: categories, tags, permalink, author, and date.
 */
function medics_entry_meta() {

	$medics_category_list = get_the_category_list( ', ', 'medics' );

	$medics_tag_list = get_the_tag_list( ', ', 'medics' );

	$medics_date = sprintf( '<time datetime="%3$s">%4$s</time>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$medics_author = sprintf( '<i class="fa fa-user"></i><a href="%1$s" title="%2$s" >%3$s</a>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'medics' ), get_the_author() ) ),
		get_the_author()
	);


	
	
	if ( $medics_tag_list ) {
			$medics_utility_text = __( 'Posted in : %1$s  on %3$s by : %4$s', 'medics' );
		} elseif ( $medics_category_list ) {
			$medics_utility_text = __( 'Posted in : %1$s  on %3$s by : %4$s', 'medics' );
		} else {
			$medics_utility_text = __( 'Posted on : %3$s by : %4$s', 'medics' );
		}
	

	printf(
		$medics_utility_text,
		$medics_category_list,
		$medics_tag_list,
		$medics_date,
		$medics_author
	);
}

if ( ! function_exists( 'medics_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own medics_comment(), and that function will be used instead.
 *
 */
function medics_comment( $comment, $medics_args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>

<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
  <p>
    <?php _e( 'Pingback:', 'medics' ); ?>
    <?php comment_author_link(); ?>
    <?php edit_comment_link( __( 'Edit', 'medics' ), '<span class="edit-link">', '</span>' ); ?>
  </p>
</li>
<?php
		break;
		default :
		// Proceed with normal comments.
		if($comment->comment_approved==1)
		{
		global $post;
	?>
<div <?php  comment_class(); ?> id="li-comment-<?php  comment_ID(); ?>" class="">
<div id="comment-<?php comment_ID(); ?>" class="comment-box clearfix no-padding">
<div class="comment-col-1"> <a href="#"><?php echo get_avatar( get_the_author_meta('ID'), '80'); ?></a> </div>
<div class="comment-col-2">
  <?php
                                printf( '<span>%1$s</span>',
                                   get_comment_author_link(),
                                    ( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author ', 'medics' ) . '</span>' : ''
                                );
                         ?>
  <?php
                                echo '<span>'.get_comment_date().'</span>';
                               echo '<a href="#">'.comment_reply_link( array_merge( $medics_args, array( 'reply_text' => __( 'Reply', 'medics' ), 'after' => '', 'depth' => $depth, 'max_depth' => $medics_args['max_depth'] ) ) ).'</a>';
                                
                            ?>
  <div class="row">
    <?php comment_text(); ?>
  </div>
</div>

<!-- .txt-holder -->
</article>
<!-- #comment-## -->
<?php
		}
		break;
	endswitch; // end comment_type check
}
endif;

function medics_read_more( ) {
return ' ..<br /><div class="reading"><a href="'. get_permalink() . '">'.__('Continue Reading','medics').'</a></div>';
 }
add_filter( 'excerpt_more', 'medics_read_more' ); 

/**length post text**/
function medics_custer_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'medics_custer_excerpt_length', 999 );
