<?php
/**
 * The Header template for our theme
 */
 $medics_options = get_option( 'medics_theme_options' );
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
	<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<title>
<?php wp_title( '|', true, 'right' ); ?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!-- header -->
<header> 
  <!-- TOP HEADER -->
  <?php if(!empty($medics_options['helpline']) || !empty($medics_options['phone']) || !empty($medics_options['email']) || !empty($medics_options['fburl']) || !empty($medics_options['twitter']) || !empty($medics_options['googleplus'])) {  ?>
  <div class="col-md-12 top-header no-padding ">
    <div class="container container-medics">
      <div class="col-md-6 help-line no-padding">
        <?php if(!empty($medics_options['phone'])) { ?>
        <span><?php echo esc_attr($medics_options['helpline']).' '.esc_attr($medics_options['phone']);?> </span>
        <?php } ?>
      </div>
      <div class="col-md-6 top-email-id no-padding">
        <div class="header-col-1 no-padding">
          <?php if(!empty($medics_options['email'])) { ?>
          <span> E-mail : <a href="mailto:<?php echo is_email($medics_options['email']);?>"><?php echo is_email($medics_options['email']);?></a></span>
          <?php } ?>
        </div>
        <div class="header-col-2 social-icons no-padding">
          <ul class="list-inline no-padding">
            <?php if(!empty($medics_options['fburl'])){ ?>
            <li><a href="<?php echo esc_url($medics_options['fburl']);?>"><i class="fa fa-facebook"></i></a></li>
            <?php } ?>
            <?php if(!empty($medics_options['twitter'])){ ?>
            <li><a href="<?php echo esc_url($medics_options['twitter']);?>"><i class="fa fa-twitter"></i></a></li>
            <?php } ?>
            <?php if(!empty($medics_options['googleplus'])){ ?>
            <li><a href="<?php echo esc_url($medics_options['googleplus']);?>"><i class="fa fa-google-plus"></i></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
  <!-- END TOP HEADER -->
  <div class="container container-medics">
    <div class="col-md-12 logo-menu no-padding">
      <div class="col-md-3 logo-icon no-padding">
        <?php if(empty($medics_options['logo'])) { ?>
        <h1 class="medics-site-name"><a href="<?php echo esc_url( get_site_url() ); ?>"><?php echo get_bloginfo('name'); ?></a></h1>
        <?php } else { ?>
        <a href="<?php echo esc_url( get_site_url() ); ?>"><img src="<?php echo esc_url($medics_options['logo']); ?>" alt="logo" class="logo-center" /></a>
        <?php } ?>
      </div>
      <div class="col-md-9 no-padding clearfix">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle navbar-toggle-top sort-menu-icon" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only"></span> <span class="icon-bar icon-color"></span> <span class="icon-bar icon-color"></span> <span class="icon-bar icon-color"></span> </button>
        </div>
        <?php
			$medics_defaults = array(
							'theme_location'  => 'primary',
							'container'       => 'div',
							'container_class' => 'navbar-collapse collapse pull-right no-padding',
							'container_id'    => '',
							'menu_class'      => 'navbar-collapse pull-right collapse no-padding',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul class="nav navbar-nav medics-menu">%3$s</ul>',
							'depth'           => 0,
							'walker'          => ''
						);
			wp_nav_menu($medics_defaults); ?>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</header>
<!-- END HEADER -->