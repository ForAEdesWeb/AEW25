!function($){
	window.addEvent('load', function() {
		var groupbysub = $('jform_params_groupbysubcat0');
		if(groupbysub){
			function hideChildren(catname, disabled){
				var select = $(catname),
					options = $(catname).options;
				if(options == null){
					return;
				}

				var opt = null;
				for(var i = 0, il = options.length; i < il; i++){
					opt = options[i];
					if($(opt).hasClass('subcat')){
						if(disabled){
							opt.selected = false;
						}

						opt.disabled = disabled;
					}
				}

				if(typeof jQuery != 'undefined' && jQuery.fn.chosen){
					jQuery(select).trigger('liszt:updated');
				}
			};

			var disabled = false;
			if(groupbysub.checked){
				disabled = true;
			}
			/* Set show/hide for sub Joomla Category and sub K2 Category */
			hideChildren('jform_params_k2catsid', disabled);
			hideChildren('jform_params_catsid', disabled);
			
			groupbysub.addEvent('click', function(){
				hideChildren('jform_params_k2catsid', true);
				hideChildren('jform_params_catsid', true);
				
			});	
			
			$('jform_params_groupbysubcat1').addEvent('click', function(){
				hideChildren('jform_params_k2catsid', false);
				hideChildren('jform_params_catsid', false);
			});
		}
	});
}(document.id);