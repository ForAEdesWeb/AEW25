/**
 * ------------------------------------------------------------------------
 * JA Slideshow Lite Module for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */


;(function(){
	if(document.getElementById('jaieanim')){ //already exist this code, no need to load css again
		return;
	}
	
	var assets = jassurl,
		head = document.head || document.getElementsByTagName('head')[0],
		iefix = document.createElement ('link'),
		ieanim = null;
		
	try{
		//IE error when load more than 32 styleSheets
		ieanim = document.createStyleSheet();
	} catch(e){	
		
	}
	
	if(ieanim){
		// add css	
		ieanim.cssText = ''+
			'.ja-ss-item { behavior:url(' + assets + 'js/animate.htc) }\n' +
			'.ja-ss-item.curr { behavior:url(' + assets + 'js/animate.htc?curr) }\n' +
			'.ja-ss-item.prev { behavior:url(' + assets + 'js/animate.htc?prev) }\n' +
			'.ja-ss-item.next { behavior:url(' + assets + 'js/animate.htc?next) }\n';

		iefix.id = 'jaieanim';
		iefix.type = 'text/css';
		iefix.rel = 'stylesheet';
		iefix.href = assets + 'css/iefix.css';
		head.appendChild(iefix);
	}
})();