<?php 
/**
 * Author Page template file
**/
get_header(); 
?>

<div class="medics-single-blog section-main header-blog">
  <div class=" container-medics container">
    <h1><?php _e('Author', 'medics'); echo " : <span>".__('All posts by','medics').' ' .get_the_author().'</span>'; ?></h1>
    <div class="header-breadcrumb">
      <ol>
        <?php if (function_exists('medics_custom_breadcrumbs')) medics_custom_breadcrumbs(); ?>
      </ol>
    </div>
  </div>
</div>
<div class="container container-medics">
  <div class="col-md-12 no-padding">
    <div class="col-md-9 clearfix single-blog no-padding">
      <?php while ( have_posts() ) : the_post(); ?>
      <div class="blog-date-col-1">
        <h2><?php echo get_the_date("M j, Y "); ?> </h2>
        <div class="blog-comment"> <i class="fa fa-comments"></i>
          <?php comments_number( '0', '1', '%' ); ?>
        </div>
      </div>
      <div class="blog-contan-col-2">
        <?php $medics_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
        <?php 
			if($medics_image){
				echo'<img src="'.esc_url($medics_image).'" class="img-responsive medics-featured-image" alt="'.get_the_title().'">';
			}
		 ?>
        <h1><a href="<?php echo esc_url( get_permalink() ); ?>" class="medics-link">
          <?php the_title(); ?>
          </a></h1>
        <div class="dr-name-icon">
          <?php medics_entry_meta(); ?>
          <?php  if(get_the_tags() != '') { ?>
          <i class="fa fa-tags"></i> <span>
          <?php the_tags('<li>', '</li>, <li>', '</li>'); ?>
          </span>
          <?php } ?>
        </div>
        <div class="medics-contant">
          <?php the_excerpt(); ?>
        </div>
      </div>
      <?php endwhile; ?>
      <?php if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
      <div class="col-md-12 medics-default-pagination"><?php if(function_exists('faster_pagination')) { faster_pagination('',1); } else { ?> 
		  <span class="medics-previous-link">
        <?php previous_posts_link(); ?>
        </span> <span class="medics-next-link">
        <?php next_posts_link(); ?>
        </span> <?php } ?> </div>
      <?php } ?>
    </div>
    <?php get_sidebar(); ?>
  </div>
</div>
<?php get_footer(); ?>
