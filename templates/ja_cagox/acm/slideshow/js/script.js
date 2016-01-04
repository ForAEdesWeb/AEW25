(function($){
	$(document).ready(function(){
		if($('.full-screen').length > 0) {
			var heightscreen = $(window).height() - $('.t3-header').outerHeight(),
					widthscreen  = $('.full-screen').width(),
					pdcenter		 = (heightscreen - $('.hero-content').height())/2;
			
			
			$('.full-screen').height(heightscreen);
			
			$(window).resize(function(){
				var heightscreen = $(window).height() - $('.t3-header').outerHeight(),
					widthscreen  = $('.full-screen').width(),
					pdcenter		 = (heightscreen - $('.hero-content').height())/2;
			
			
				$('.full-screen').height(heightscreen);
			});
		}
	});
})(jQuery);