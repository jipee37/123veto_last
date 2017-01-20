<?php
/*
 * medics Breadcrumbs
*/
function medics_custom_breadcrumbs() {

  $medics_showonhome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $medics_delimiter = '/'; // medics_delimiter between crumbs
  $medics_home = __('Home','medics'); // text for the 'Home' link
  $medics_showcurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $medics_before = ' '; // tag before the current crumb
  $medics_after = ' '; // tag after the current crumb

  global $post;
  $medics_homelink = esc_url(home_url());

  if (is_home() || is_front_page()) {

    if ($medics_showonhome == 1) echo '<div id="crumbs" class="conter-text medics-breadcrumb"><a href="' . $medics_homelink . '">' . $medics_home . '</a></div>';

  } else {

    echo '<div id="crumbs" class="conter-text medics-breadcrumb"><a href="' . $medics_homelink . '">' . $medics_home . '</a> ' . $medics_delimiter . ' ';

    if ( is_category() ) {
      $medics_thisCat = get_category(get_query_var('cat'), false);
      if ($medics_thisCat->parent != 0) echo get_category_parents($medics_thisCat->parent, TRUE, ' ' . $medics_delimiter . ' ');
      echo $medics_before . __('Archive by category','medics').' "' . single_cat_title('', false) . '"' . $medics_after;

    } elseif ( is_search() ) {
      echo $medics_before . __('Search results for','medics').' "' . get_search_query() . '"' . $medics_after;

    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $medics_delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $medics_delimiter . ' ';
      echo $medics_before . get_the_time('d') . $medics_after;

    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $medics_delimiter . ' ';
      echo $medics_before . get_the_time('F') . $medics_after;

    } elseif ( is_year() ) {
      echo $medics_before . get_the_time('Y') . $medics_after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $medics_post_type = get_post_type_object(get_post_type());
        $medics_slug = $medics_post_type->rewrite;
        echo '<a href="' . $medics_homelink . '/' . $medics_slug['slug'] . '/">' . $medics_post_type->labels->singular_name . '</a>';
        if ($medics_showcurrent == 1) echo ' ' . $medics_delimiter . ' ' . $medics_before . get_the_title() . $medics_after;
      } else {
        $medics_cat = get_the_category(); $medics_cat = $medics_cat[0];
        $medics_cats = get_category_parents($medics_cat, TRUE, ' ' . $medics_delimiter . ' ');
        if ($medics_showcurrent == 0) $medics_cats = preg_replace("#^(.+)\s$medics_delimiter\s$#", "$1", $medics_cats);
        echo $medics_cats;
        if ($medics_showcurrent == 1) echo $medics_before . get_the_title() . $medics_after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $medics_post_type = get_post_type_object(get_post_type());
      echo $medics_before . $medics_post_type->labels->singular_name . $medics_after;

    } elseif ( is_attachment() ) {
      $medics_parent = get_post($post->post_parent);
      $medics_cat = get_the_category($medics_parent->ID); $medics_cat = $medics_cat[0];
      echo get_category_parents($medics_cat, TRUE, ' ' . $medics_delimiter . ' ');
      echo '<a href="' . get_permalink($medics_parent) . '">' . $medics_parent->post_title . '</a>';
      if ($medics_showcurrent == 1) echo ' ' . $medics_delimiter . ' ' . $medics_before . get_the_title() . $medics_after;

    } elseif ( is_page() && !$post->post_parent ) {
      if ($medics_showcurrent == 1) echo $medics_before . get_the_title() . $medics_after;

    } elseif ( is_page() && $post->post_parent ) {
      $medics_parent_id  = $post->post_parent;
      $medics_breadcrumbs = array();
      while ($medics_parent_id) {
        $medics_page = get_page($medics_parent_id);
        $medics_breadcrumbs[] = '<a href="' . get_permalink($medics_page->ID) . '">' . get_the_title($medics_page->ID) . '</a>';
        $medics_parent_id  = $medics_page->post_parent;
      }
      $medics_breadcrumbs = array_reverse($medics_breadcrumbs);
      for ($medics_i = 0; $medics_i < count($medics_breadcrumbs); $medics_i++) {
        echo $medics_breadcrumbs[$medics_i];
        if ($medics_i != count($medics_breadcrumbs)-1) echo ' ' . $medics_delimiter . ' ';
      }
      if ($medics_showcurrent == 1) echo ' ' . $medics_delimiter . ' ' . $medics_before . get_the_title() . $medics_after;

    } elseif ( is_tag() ) {
      echo $medics_before . __('Posts tagged','medics').' "' . single_tag_title('', false) . '"' . $medics_after;

    } elseif ( is_author() ) {
       global $author;
      $medics_userdata = get_userdata($author);
      echo $medics_before . __('Articles posted by ','medics'). $medics_userdata->display_name . $medics_after;

    } elseif ( is_404() ) {
      echo $medics_before . __('Error 404','medics') . $medics_after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page','medics') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }

    echo '</div>';

  }
} // end medics_custom_breadcrumbs()
