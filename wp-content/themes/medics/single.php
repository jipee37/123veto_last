<?php 
/**
 * Single Post template file
**/
get_header(); 
?>

<div class="medics-single-blog section-main header-blog">
  <div class=" container-medics container">
    <h1> <span> <?php echo get_the_title(); ?> </span> </h1>
  </div>
</div>
<div class="container container-medics">
  <div class="col-md-12 no-padding">
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php while ( have_posts() ) : the_post(); ?>
      <?php $medics_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
      <div class="col-md-9 clearfix no-padding">
        <div class="single-blog">
          <div class="blog-date-col-1">
            <h2><?php echo get_the_date("M j, Y "); ?> </h2>
            <div class="blog-comment"> <i class="fa fa-comments"></i><?php comments_number( '0', '1', '%' ); ?> </div>
          </div>
          <div class="blog-contan-col-2">
            <?php 
			if($medics_image){
				echo'<img src="'.esc_url($medics_image).'" class="img-responsive medics-featured-image" alt="'.get_the_title().'">';
			}
		?>
            <h1>
              <?php the_title(); ?>
            </h1>
            <div class="dr-name-icon">
              <?php medics_entry_meta(); ?>
              <?php  if(get_the_tags() != '') { ?>
              <i class="fa fa-tags"></i> <span>
              <?php the_tags('<li>', '</li>, <li>', '</li>'); ?>
              </span>
              <?php  } ?>
            </div>
            <div class="medics-contant">
              <?php the_content(); 
				wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'medics' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
			?>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
        <div class="col-md-12 medics-default-pagination"> <span class="medics-previous-link">
          <?php previous_post_link(); ?>
          </span> <span class="medics-next-link">
          <?php next_post_link(); ?>
          </span> </div>
        <?php  comments_template( '', true ); ?>
      </div>
      <?php  get_sidebar(); ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
