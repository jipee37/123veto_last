<?php 
/**
 * The main template file
**/
get_header(); 
$medics_options = get_option( 'medics_theme_options' );
?>
<div class="medics-single-blog section-main header-blog">
  
</div>
<div class="container container-medics">
  <div class="col-md-12 no-padding">
    <div class="col-md-9 clearfix no-padding">
      <?php 
	  $medics_args = array( 
						'orderby'      => 'post_date', 
						'order'        => 'DESC',
						'post_type'    => 'post',
						'paged' => $paged,
						'post_status'    => 'publish'	
					  );
	$medics_query = new WP_Query($medics_args);
	?>
      <?php if ($medics_query->have_posts() ) : while ($medics_query->have_posts()) : $medics_query->the_post(); ?>
      <?php $medics_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
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
       
              <h1><a href=<?php echo esc_url( get_permalink() ); ?>>
                <?php the_title(); ?>
                </a></h1>
              <div class="dr-name-icon">
             <?php medics_entry_meta(); ?>
             
             <?php  if(get_the_tags() != '') { ?>
                <i class="fa fa-tags"></i>  
                <span>
                <?php the_tags('<li>', '</li>, <li>', '</li>'); ?>
                </span>
            <?php  } ?>    
               
                 </div>
              <div class="medics-contant">
            <?php the_excerpt(); ?>
          </div>
         
        </div>
      </div>
      <?php endwhile; endif; // end of the loop. ?>
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
<?php  get_footer(); ?>
