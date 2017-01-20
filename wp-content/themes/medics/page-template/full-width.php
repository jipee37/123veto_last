<?php 
/**
 * Template Name: Full Width
**/
get_header();
?>

<div class="medics-single-blog section-main header-blog">
  <div class=" container-medics container">
    <h1><span>
      <?php the_title(); ?>
      </span></h1>
    <div class="header-breadcrumb">
      <ol>
        <?php if (function_exists('medics_custom_breadcrumbs')) medics_custom_breadcrumbs(); ?>
      </ol>
    </div>
  </div>
</div>
<div class="container container-medics">
  <div class="col-md-12 clearfix no-padding medics-fullsidebar">
    <?php while ( have_posts() ) : the_post(); ?>
    <?php $medics_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
    <div class="single-blog">
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
          <?php if(get_the_tags() != '') { ?>
          <i class="fa fa-tags"></i> <span>
          <?php the_tags('<li>', '</li>, <li>', '</li>'); ?>
          </span>
          <?php } ?>
        </div>
        <p>
          <?php the_content(); ?>
          <?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'medics' ),
					'after' => '</div>',
				) );
			?>
        </p>
      </div>
    </div>
    <?php endwhile; ?>
    <?php comments_template( '', true ); ?>
  </div>
</div>
<?php get_footer(); ?>
