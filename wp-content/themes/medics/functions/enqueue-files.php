<?php 
/*
 * medics Enqueue css and js files
*/
function medics_enqueue()
{
	wp_enqueue_style('medics-bootstrap',get_template_directory_uri().'/css/bootstrap.min.css',array(),'','');
	wp_enqueue_style('medics-style',get_stylesheet_uri());
	wp_enqueue_style('medics-font-awesome',get_template_directory_uri().'/css/font-awesome.min.css',array(),'','');
	wp_enqueue_style('medics-scada-font','//fonts.googleapis.com/css?family=Scada');
	wp_enqueue_script('medics-bootstrapjs',get_template_directory_uri().'/js/bootstrap.min.js',array('jquery'));
	if(is_page_template('page-template/frontpage.php')){
		wp_enqueue_script('medics-sliderjs',get_template_directory_uri().'/js/owl.carousel.min.js',array('jquery'));
		wp_enqueue_script('medics-defaultjs',get_template_directory_uri().'/js/default.js',array('jquery') ,'1.0');
		wp_enqueue_style('medics-owl-carousel-css',get_template_directory_uri().'/css/owl.carousel.css',array(),'','');
	}
	
	if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 
}
add_action('wp_enqueue_scripts', 'medics_enqueue');
