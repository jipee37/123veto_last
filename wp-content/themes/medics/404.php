<?php 
/**
 * 404 page template file
**/
get_header(); 
?>

<div class="container container-medics">
  <div class="col-md-12 no-padding">
    <div class="jumbotron">
      <h1><?php _e('Epic 404 - Article Not Found', 'medics') ?></h1>
      <p> <?php _e("This is embarassing. We can't find what you were looking for.", "medics") ?></p>
      <section class="post_content">
        <p> <?php _e('Whatever you were looking for was not found, but maybe try looking again or search using the form below.', 'medics') ?></p>
        <div class="row">
          <div class="col-sm-12">
            <?php get_search_form(); ?>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<?php get_footer(); ?>
