/** 
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 *------------------------------------------------------------------------------
 */
 

(function($){
	$(document).ready(function(){
		if($('.full-screen').length > 0) {
			var heightscreen = $(window).height() - $('.t3-header').outerHeight() - $('.uber-header').outerHeight() - $('.uber-bar').outerHeight() - $('.slideshow-thumbs .carousel-indicators').height(),
					videoscreen  = $('.video-wrapper').outerHeight(),
					widthscreen  = $('.full-screen').width(),
					pdcenter		 = (heightscreen - $('.hero-content').height())/2,
					pdvideo 		 = (videoscreen - $('.hero-content').height())/2;
			
			
			$('.full-screen').height(heightscreen);
			$('.full-screen .hero-content').css('padding-top',pdcenter);
			
			if(widthscreen/heightscreen > 16/9) {
				$('.full-screen.style-4').height(heightscreen);
				$('.full-screen.style-4 .hero-content').css('padding-top',pdcenter);	
			} else {
				$('.full-screen.style-4').height(videoscreen);
				$('.full-screen.style-4 .hero-content').css('padding-top',pdvideo);
			}
			
			$(window).resize(function(){
				var heightscreen = $(window).height() - $('.t3-header').outerHeight() - $('.uber-header').outerHeight() - $('.uber-bar').outerHeight() - $('.slideshow-thumbs .carousel-indicators').height(),
						videoscreen  = $('.video-wrapper').outerHeight(),
						widthscreen  = $('.full-screen').width(),
						pdcenter		 = (heightscreen - $('.hero-content').height())/2,
						pdvideo 		 = (videoscreen - $('.hero-content').height())/2;
				
				$('.full-screen').height(heightscreen);
				$('.full-screen .hero-content').css('padding-top',pdcenter);
				
				if(widthscreen/heightscreen > 16/9) {
					$('.full-screen.style-4').height(heightscreen);
					$('.full-screen.style-4 .hero-content').css('padding-top',pdcenter);	
				} else {
					$('.full-screen.style-4').height(videoscreen);
					$('.full-screen.style-4 .hero-content').css('padding-top',pdvideo);
				}
			});
		}

		$('.acm-hero').each(function(){
			var hero = $(this),
					url = hero.css('background-image');
					url = url.replace(/url\(\"?/,'').replace(/\"?\)/,'');
			if(url != 'none') {
				var bgImg = new Image();
				bgImg.src = url;
				bgImg.onload = function() {
					hero.data('imgHeight', bgImg.height);
					hero.data('imgWidth', bgImg.width);
					hero.trigger('hero.resize');
				}
			}
		});
		
		$(window).resize(function(){
			$('.acm-hero').trigger('hero.resize');
		});
		
		$('.acm-hero').on ('hero.resize', function () {
			var hero = $(this),
				carousel = hero.parents('.carousel-inner').first(),
				screenHeight = carousel.length ? carousel.outerHeight() : hero.outerHeight(),
				screenWidth  = carousel.length ? carousel.outerWidth() : hero.outerWidth(),
				imgHeight = hero.data('imgHeight'),
				imgWidth = hero.data('imgWidth');
			if (imgHeight && imgWidth) {
				if(imgWidth/imgHeight > screenWidth/screenHeight) {
					hero.css('background-size','auto 100%');
				} else {
					hero.css('background-size','100% auto');
				}
			}
		});
		
		if($('html.ie8').length > 0) {
			$('.acm-hero').each(function(){
				var bg = $(this).css('background-image');
	      bg = bg.replace('url("','').replace('")','');
	      
	      if(typeof bg !== 'none'){
	        $(this).css({
	            "filter": "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+bg+"', sizingMethod='scale')"
	        });
	      }
			});
		}
		
	});
})(jQuery);

 
