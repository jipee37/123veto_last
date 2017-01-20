<?php //fix bug header ?>
<div id="header">
    <h1><a href="<?php bloginfo('url');?>"> <?php bloginfo('name');?></a></h1>
    <?php wp_nav_menu(array('theme_location' => 'main_menu')); ?> 
</div>

