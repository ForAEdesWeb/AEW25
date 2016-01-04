(function($){
	$(document).ready(function(){
		if($('.full-screen').length > 0) {
			var heightscreen = $(window).height() - $('.block-header:first-child').outerHeight() - $('.block-topbar:first-child').outerHeight(),
					widthscreen  = $('.full-screen').width(),
					pdcenter		 = (heightscreen - $('.hero-content').height())/2;
			
			
			$('.full-screen').height(heightscreen);
			
			$(window).resize(function(){
				var heightscreen = $(window).height() - $('.t3-header').outerHeight() - $('.uber-header').outerHeight() - $('.uber-bar').outerHeight() - $('.slideshow-thumbs .carousel-indicators').height(),
						videoscreen  = $('.video-wrapper').outerHeight(),
						widthscreen  = $('.full-screen').width(),
						pdcenter		 = (heightscreen - $('.hero-content').height())/2,
						pdvideo 		 = (videoscreen - $('.hero-content').height())/2;
				
				$('.full-screen').height(heightscreen);
			});
		}
	});
})(jQuery);