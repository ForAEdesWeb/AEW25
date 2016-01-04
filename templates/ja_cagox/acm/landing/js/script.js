(function($){
	$(document).ready(function(){
		var callbackScroll = function(panel) {
			if ($('.block-landing-item', panel).size()) {
				$('header.t3-header').removeClass('light-style dark-style')
					.addClass($('.block-landing-item.active .fp-tableCell > div', panel).attr('data-style'));

				$('#fp-nav').removeClass('light-style dark-style')
					.addClass($('.block-landing-item.active .fp-tableCell > div', panel).attr('data-style'));
					
				var logo = $('header.t3-header .logo-img'), logosmall = $('header.t3-header .logo-img-sm');
				if ($('header.t3-header').hasClass('dark-style')) {
					logo.attr('src', logo.attr('src').replace('logo-default.png', 'logo-default-light.png'));
					logosmall.attr('src', logosmall.attr('src').replace('logo-mobile.png', 'logo-mobile-light.png'));
				} else {
					logo.attr('src', logo.attr('src').replace('logo-default-light.png', 'logo-default.png'));
					logosmall.attr('src', logosmall.attr('src').replace('logo-mobile-light.png', 'logo-mobile.png'));
				}
			}
		};
		
		var panel = $('#fullpage');
		callbackScroll(panel);
				
		panel.fullpage({
			verticalCentered: true,
			css3: true,
			navigation: true,
			controlArrows: false,
			slidesNavigation: false,
			navigationPosition: 'bottom',
			scrollingSpeed: 1000,
			easingcss3: 'cubic-bezier(0.175, 0.885, 0.320, 1.09)',

			afterLoad: function(anchorLink, index){

			},
			onLeave: function(index, nextIndex, direction){
				callbackScroll(panel);
			}
		});
		
		
	});
})(jQuery);