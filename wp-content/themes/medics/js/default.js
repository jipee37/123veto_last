jQuery(document).ready(function(e) { 
	jQuery("#from-theblog").owlCarousel({
    items : 3,
    itemsDesktop : [1199,3],
    itemsDesktopSmall : [979,3],
	scrollPerPage : 3
    });
	jQuery(".next3").click(function(){
	jQuery("#from-theblog").trigger('owl.next');
	})
	jQuery(".prev3").click(function(){
	jQuery("#from-theblog").trigger('owl.prev');
	})
});
