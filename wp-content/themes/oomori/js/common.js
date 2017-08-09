jQuery(document).ready(function() {
	jQuery('#page-top').click(function() {
		jQuery("html, body").animate({ scrollTop: 0 }, 1500);
	});

	jQuery('#contact').find('.jumper').click(function() {
		var heightHeader = 0;
		if(jQuery("header").find(".sp-content").css('display') == 'block') {
			heightHeader = jQuery("header .sp-content .headerInner").css('max-height');
		}
		jQuery("body, html").animate({ 
	      scrollTop: jQuery( jQuery(this).attr('href') ).offset().top - parseInt(heightHeader)
	    }, 800);
	});
});

