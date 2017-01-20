<?php

/**

** activation theme

**/

function theme_enqueue_styles() {

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    wp_enqueue_style('theme_child-style', get_stylesheet_uri(), array(), '0.1', 'all');

//get_theme_root_uri('themes', '123veto-child').'/style1.css

}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

?>