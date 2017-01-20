<?php 
/**
 * Main Sidebar
**/
?>

<div class="col-md-3 main-sidebar clearfix">
  <?php if ( is_active_sidebar( 'sidebar-1' ) ) { 
			 dynamic_sidebar( 'sidebar-1' );
	 } ?>
</div>
