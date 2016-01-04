!function($){
	var JAMHelper = {
		sid: null,
		ready: false,
		resize: function(){
			$('.main-siderbar, .main-siderbar1').masonry('reload');
		}
	};

	$(window).ready(function(){
		JAMHelper.ready = $('.main-siderbar, .main-siderbar1').masonry({
			itemSelector: '.t3-module',
			isResizable: true
		}).length;
	});

	$(window).load(function() {
		
		$('#back-to-top').on('click', function(){
			$('html, body').stop(true).animate({
				scrollTop: 0
			}, {
				duration: 800, 
				easing: 'easeInOutCubic',
				complete: window.reflow
			});

			return false;
		});

		if(JAMHelper.ready) {
			clearTimeout(JAMHelper.sid);
			JAMHelper.sid = setTimeout(JAMHelper.resize, navigator.userAgent.match(/Mobile/i) ? 1200 : 1100);
		}

		//fix for JA Tabs
		if(JAMHelper.ready) {
			$('.main-siderbar .ja-tabs-title, .main-siderbar .ja-accordion').on('click', function (argument) {
				clearTimeout(JAMHelper.sid);
				JAMHelper.sid = setTimeout(JAMHelper.resize, navigator.userAgent.match(/Mobile/i) ? 1300 : 1100);
			});
		}
	});

	$(window).on('resize', function(){
		if(!JAMHelper.ready) { return }
		clearTimeout(JAMHelper.sid);
		JAMHelper.sid = setTimeout(JAMHelper.resize, navigator.userAgent.match(/Mobile/i) ? 500 : 250);
	});

}(jQuery);

window.addEvent('domready', function(){
	//fix validate.js error
	if(Browser.ie && Browser.version <= 8){
		Browser.Features.inputemail = false;
	}
});