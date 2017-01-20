<?php
/* 
 *	Medics Recent post widget	
 */
class medics_randompostwidget extends WP_Widget
{
function medics_randompostwidget()
{
$medics_widget_ops = array('classname' => 'medics_recentpostwidget', 'description' => __('Displays a recent post with thumbnail','medics') );
parent::__construct('medics_recentpostwidget', __('Medics Recent Post','medics'), $medics_widget_ops);
}

function form($medics_instance)
{
$medics_instance = wp_parse_args( (array) $medics_instance, array( 'title' => '' ) );
$medics_instance['title'];
if(!empty($medics_instance['post_number'])) { $medics_instance['post_number']; } 
?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'medics'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if(!empty($medics_instance['title'])) { echo $medics_instance['title']; } ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'post_number' ); ?>"><?php _e('Number of post to show:', 'medics'); ?></label>
            <input id="<?php echo $this->get_field_id( 'post_number' ); ?>" name="<?php echo $this->get_field_name( 'post_number' ); ?>" value="<?php if(!empty($medics_instance['post_number'])) { echo $medics_instance['post_number']; } else { echo '5'; } ?>" style="width:100%;" />
        </p>
<?php
}

function update($medics_new_instance, $medics_old_instance)
{
$medics_instance = $medics_old_instance;
$medics_instance['title'] = $medics_new_instance['title'];
$medics_instance['post_number'] = $medics_new_instance['post_number'];
return $medics_instance;
}

function widget($medics_args, $medics_instance)
{
extract($medics_args, EXTR_SKIP);

echo $before_widget;
$medics_title = empty($medics_instance['title']) ? ' ' : apply_filters('widget_title', $medics_instance['title']);

if (!empty($medics_title))
echo $before_title . $medics_title . $after_title;;

//widget code here
?>
<div class="medics-custom-widget">
<?php
$medics_args = array(
	'posts_per_page'   => $medics_instance['post_number'],
	'orderby'          => 'post_date',
	'order'            => 'DESC',
	'post_type'        => 'post',
	'post_status'      => 'publish'
);
$medics_single_post = new WP_Query( $medics_args );

while ( $medics_single_post->have_posts() ) { $medics_single_post->the_post();
?>
<div class="footer-single-post col-md-12 no-padding">
    <div class="recent-col-1"> 
    <?php 
	$medics_feat_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
      <div class="ImageWrapper chrome-fix">
      <?php if($medics_feat_image!="") { ?>
      <img src="<?php echo $medics_feat_image; ?>" class="img-responsive medics-post-widget" alt="<?php echo get_the_title(); ?>" />
    <?php } ?>
    </div>
    </div>
    <div class="recent-col-2">
    	<h6><a href="<?php echo get_permalink(); ?>" class="recent-post-title-link"><?php the_title(); ?></a></h6>
    </div>
    <p><?php the_excerpt(); ?></p>
    
  	<div class="recent-blog-count">
      	<div><i class="fa fa-user"></i><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_the_author(); ?></a> </div> 
        <div><i class="fa fa-calendar"></i><?php echo get_the_date("M j, Y "); ?> </div> 
        <div><i class="fa fa-comments"></i><?php echo get_comments_number(); ?></div> 
    </div>
  </div>
<?php } wp_reset_query(); ?>
</div>
<?php	
echo $after_widget;
}
}
add_action( 'widgets_init', create_function('', 'return register_widget("medics_randompostwidget");') );
?>
