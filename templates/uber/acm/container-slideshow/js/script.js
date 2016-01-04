
(function($){
	
	$(document).ready(function() {
		// Add show menu for style - 3
		if($('.indicators-menu').length > 0) {
			$('.indicators-menu').mouseover(function(){
				$('.mn-carousel').addClass('show-menu');
			});
			
			$('.carousel-ctrl').mouseleave(function(){
				$('.mn-carousel').removeClass('show-menu');
			});
		}
		
		if($('.acm-features.style-6 .content-left').length > 0) {
			$('.acm-container-slide.slide-3').addClass('ft-left');
		}
		
		if($('.acm-features.style-6 .content-right').length > 0) {
			$('.acm-container-slide .content-right').each(function() {
				var ctr = $(this).parents('.acm-container-slide.slide-3:first');
					ctr.addClass('ft-right');
					ctr.removeClass('ft-left');
			});
		}
	});
	
})(jQuery);