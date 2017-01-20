<?php
/*
* Template Name:Home
*/
?>
<?php get_header(); 
$medics_options = get_option( 'medics_theme_options' );
?>
<!-- HOME BANNER -->
<div class="medics-home-banner ">
  <?php if(get_header_image()){ ?>
  <div class="custom-header-img"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" class="img-responsive"> </a> </div>
  <?php } ?>
</div>
<!-- END HOME BANNER --> 

<!--home-title-->
<div class="section-main front-main">
  <div class=" container-medics container homepage-theme-title">
    <h2>
      <?php if(!empty($medics_options['home-title'])) { echo sanitize_text_field($medics_options['home-title']); } ?>
    </h2>
    <h3>
      <?php if(!empty($medics_options['home-content'])) { echo sanitize_text_field($medics_options['home-content']); } ?>
    </h3>
    <?php if((!empty($medics_options['home-content'])) || (!empty($medics_options['home-title']))) { ?>
    <div class="center-welcome-line text-center"></div>
    <?php } ?>
  </div>
</div>
<!--End-home-title-->

<div class="container container-medics medics-margin-bottom"> 
  <!-- technology -->
  <div class="row technology">
    <?php for($medics_section_i=1; $medics_section_i <=3 ;$medics_section_i++ ): ?>
    <div class="col-md-4 technology-blog">
      <div class="screenshot" id="first-img-<?php echo $medics_section_i; ?>">
        <?php if(!empty($medics_options['home-icon-'.$medics_section_i])) {  echo "<img src='".esc_url($medics_options['home-icon-'.$medics_section_i])."' alt=''  />"; } ?>
      </div>
      <h1>
        <?php if(!empty($medics_options['section-title-'.$medics_section_i])) { echo sanitize_text_field($medics_options['section-title-'.$medics_section_i]); } ?>
      </h1>
      <p>
        <?php if(!empty($medics_options['section-content-'.$medics_section_i])) { echo sanitize_text_field($medics_options['section-content-'.$medics_section_i]); } ?>
      </p>
    </div>
    <?php endfor; ?>
  </div>
  <!--end technology --> 
  
  <!-- FROM THE BLOG -->
  <?php if(!empty($medics_options['post-category'])){ ?>
  <div class="col-md-12 main-title no-padding clearfix">
    <h1>
       <?php if(!empty($medics_options['homeblogtitle'])) { echo esc_attr($medics_options['homeblogtitle']); }else{ _e('FROM THE BLOG','medics'); }?>
      <span id="next1" class="next black-box next3"><i class="fa fa-caret-right"></i></span> <span id="prev1" class="prev black-box prev3"><i class="fa fa-caret-left"></i> </span> </h1>
    <div class="full-line-title"></div>
  </div>
  <div class="medics-homeblog" id="from-theblog">
    <?php
	$medics_args = array(
	   'cat'  => $medics_options['post-category'],
		'meta_query' => array(
			array(
			 'key' => '_thumbnail_id',
			 'compare' => 'EXISTS'
			),
		)
	);	
	$medics_query=new $wp_query($medics_args); ?>
    <?php if ( $medics_query->have_posts() ) { ?>
    <?php while($medics_query->have_posts()) {  $medics_query->the_post(); ?>
    <?php $medics_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
    <div class="home-blog item">
      <div class="col-md-2 no-padding blog-date">
        <h6><?php echo get_the_date("M j, Y "); ?></h6>
        <div class="blog-comment"> <i class="fa fa-comments"></i><?php echo get_comments_number(); ?> </div>
      </div>
      <div class="col-md-10 no-padding blog-contan">
        <div class="medics-home-blog-img">
          <?php 
			if($medics_image){
				echo'<img src="'.esc_url($medics_image).'" class="img-responsive medics-featured-image" alt="'.get_the_title().'">';
			}
			?>
        </div>
        <h1><a href=<?php echo get_permalink(); ?>>
          <?php the_title(); ?>
          </a></h1>
        <div class="dr-name-icon"> <i class="fa fa-user"></i><span><?php echo get_the_author(); ?></span>
          <?php  if(get_the_tags() != '') { ?>
          <i class="fa fa-tags"></i> <span>
          <?php the_tags('<li>', '</li>, <li>', '</li>'); ?>
          </span>
          <?php  } ?>
        </div>
        <p>
          <?php the_excerpt(); ?>
        </p>
      </div>
    </div>
    <?php } } else { echo '<p>'.__('no posts found','medics').'</p>'; } ?>
    <?php // endwhile; endif; // end of the loop. ?>
  </div>
  <?php } ?>
  <!-- END FROM THE BLOG --> 

</div>
<?php  if(!empty($medics_options['home-download-text'])) { ?>
<div class="twiterpost col-md-12 no-padding">
  <div class="container container-medics twiterpost ">
    <div class="icon-msg">
      <p>
        <?php  echo esc_attr($medics_options['home-download-text']);  ?>
      </p>
    </div>
    <?php if(!empty($medics_options['home-download-link'])) { ?>
    <div class="medics-download-link pull-right"> <a href="<?php  echo esc_url($medics_options['home-download-link']); ?>"><?php _e('Download','medics') ?></a> </div>
    <?php } ?>
  </div>
</div>
<?php  } ?>
<?php get_footer(); ?>
