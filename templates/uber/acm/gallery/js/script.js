(function($){
	
	$(document).ready(function(){
		
		// Popup Gallery Images
		if ($('.isotope-layout').length > 0) {
			$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
				event.preventDefault();
				return $(this).ekkoLightbox();
			});	
		}
	});
 })(jQuery);
 
 (function($){
	  $(document).ready(function(){
	    var $container = $('.isotope-layout .isotope');
	
	    if (!$container.length) return ;
	
	    $container.isotope({
	      itemSelector: '.item',
	      masonry: {
	        columnWidth: '.grid-sizer',
	      }
	    });
	    
	    // re-order when images loaded
	    $container.imagesLoaded(function(){
	      $container.isotope();
	    
	      /* fix for IE-8 */
	      setTimeout (function() {
	        $('.isotope-layout .isotope').isotope();
	      }, 8000);  
	    });
	  });
	})(jQuery);