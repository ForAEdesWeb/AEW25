/**
 * @version 3.x
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2013 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michał Olczyk michal.olczyk@design-joomla.eu
 *
 */

function DJCatMatchModules(className, setLineHeight, reset) {
	var maxHeight = 0;
	var divs = null;
	if (typeof(className) == 'string') {
		divs = document.id(document.body).getElements(className);
	} else {
		divs = className;
	}
	if (divs.length > 1) {
		divs.each(function(element) {
			if (reset == true) {
				element.setStyle('height', '');
			}
			//maxHeight = Math.max(maxHeight, parseInt(element.getStyle('height')));
			maxHeight = Math.max(maxHeight, parseInt(element.getSize().y));
		});
		
		divs.setStyle('height', maxHeight);
		if (setLineHeight) {
			divs.setStyle('line-height', maxHeight);
		}
	}
}

this.DJCatImageSwitcher = function (){
	var mainimagelink = document.id('djc_mainimagelink');
	var mainimage = document.id('djc_mainimage');
	var thumbs = document.id('djc_thumbnails') ? document.id('djc_thumbnails').getElements('img') : null;
	var thumblinks = document.id('djc_thumbnails') ? document.id('djc_thumbnails').getElements('a') : null;
	
	if(mainimagelink && mainimage) {
		mainimagelink.removeEvents('click').addEvent('click', function(evt) {
			var rel = mainimagelink.rel;
			document.id(rel).fireEvent('click', document.id(rel));

			/*if(!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
				return false;
			}
			return true;*/
			
			return false;
		});
	}
	
	if (!mainimage || !mainimagelink || !thumblinks || !thumbs) return false;
	
	thumblinks.each(function(thumblink,index){
		var fx = new Fx.Tween(mainimage, {link: 'cancel', duration: 200});

		thumblink.addEvent('click',function(event){
			event.preventDefault();
			//new Event(element).stop();
			/*
			mainimage.onload = function() {
				fx.start('opacity',0,1);
			};
			*/
			var img = new Image();
			img.onload = function() {
				fx.start('opacity',0,1);
			};
			
			fx.start('opacity',1,0).chain(function(){
				mainimagelink.href = thumblink.href;
				mainimagelink.title = thumblink.title;
				mainimagelink.rel = 'djc_lb_'+index;
				img.src = thumblink.rel;
				mainimage.src = img.src;
				mainimage.alt = thumblink.title;
			});
			return false;
		});
	});
}; 

window.addEvent('domready', function(){
	DJCatImageSwitcher();
	
	// contact form handler
	var contactform = document.id('contactform');
	var makesure = document.id('djc_contact_form');
	var contactformButton = document.id('djc_contact_form_button');
	var contactformButtonClose = document.id('djc_contact_form_button_close');
	if (contactform && makesure) {
		var djc_formslider = new Fx.Slide('contactform',{
			duration: 200,
			resetHeight: true
		});
		
		if (window.location.hash == 'contactform' || window.location.hash == '#contactform') {
			djc_formslider.slideIn().chain(function(){
				if (djc_formslider.open == true) {
					var scrollTo = new Fx.Scroll(window).toElement('contactform');
				}
			});
		} else if (contactformButton) {
			djc_formslider.hide();
		}
		if (contactformButton) {
			contactformButton.addEvent('click', function(event) {
				event.stop();
				djc_formslider.slideIn().chain(function(){
					if (djc_formslider.open == true) {
						var scrollTo = new Fx.Scroll(window).toElement('contactform');
					}
				});
			});
		}
		if (contactformButtonClose) {
			contactformButtonClose.addEvent('click', function(event){
				event.stop();
				djc_formslider.slideOut().chain(function(){
					if (djc_formslider.open == false) {
						var scrollTo = new Fx.Scroll(window).toElement('djcatalog');
					}
				});
			});
		}
	}
	
	// add to cart form handler
	var cart_forms = document.id(document.body).getElements('form.djc_form_addtocart');
	if (cart_forms.length > 0) {
		
		var cart_popup = new Element('div', {'id': 'djc_cart_popup', 'class' : 'djc_cart_popup', 'rel' : '{handler: \'clone\', size: {x: \'100%\', y: \'100%\'}, onOpen: function() {this.win.addClass(\'djc_cart_modal\'); this.overlay.addClass(\'djc_cart_modal\'); window.addEvent(\'resize\', function(){ this.resize({x: Math.max(Math.floor(window.getSize().x / 2), 400), y: Math.max(Math.floor(window.getSize().y / 4), 200)}, true); }.bind(this) ); window.fireEvent(\'resize\'); }, onClose: function(){this.win.removeClass(\'djc_cart_modal\'); this.overlay.removeClass(\'djc_cart_modal\');}}'});
		//var cart_popup = new Element('div', {'id': 'djc_cart_popup', 'class' : 'djc_cart_popup', 'rel' : '{handler: \'clone\', size: {x: \'100%\', y: \'auto\'}, onOpen: function() {this.win.addClass(\'djc_cart_modal\'); this.overlay.addClass(\'djc_cart_modal\'); window.addEvent(\'resize\', function(){ this.resize({x: Math.max(Math.floor(window.getSize().x / 2), 400)}, true); }.bind(this) ); window.fireEvent(\'resize\'); }, onClose: function(){this.win.removeClass(\'djc_cart_modal\'); this.overlay.removeClass(\'djc_cart_modal\');}}'});
		var cart_wrap = new Element('div', {'id': 'djc_cart_popup_wrap', 'style': 'display: none;'});
		var cart_loader = new Element('div', {'id': 'djc_cart_popup_loader', 'style': 'display: none;', 'html': '<span></span>'});
		cart_wrap.adopt(cart_popup);
		
		document.id(document.body).adopt(cart_loader);
		document.id(document.body).adopt(cart_wrap);
		
		cart_forms.each(function(el, pos){
			el.addEvent('submit', function(evt){
				var request = el.get('send');
				request.onSuccess = function(responseText, responseXML) {
					cart_loader.setStyle('display', 'none');
					var response = JSON.parse(responseText);
					var popup_instance = document.id('djc_cart_popup');
					popup_instance.innerHTML = '<p>' + response.message + '</p>';
					SqueezeBox.fromElement(popup_instance, {parse: 'rel'});
					
					if (typeof response.basket_count != 'undefined') {
						document.id(document.body).getElements('strong.djc_mod_cart_items_count').each(function(count_el){
							count_el.innerHTML = response.basket_count;
						});
						var basket_items = document.id(document.body).getElements('.mod_djc2_cart_contents');
						var basket_is_empty = document.id(document.body).getElements('.mod_djc2cart_is_empty');
						
						if (basket_items) {
							if (response.basket_count > 0) {
								basket_items.setStyle('display', 'block');
							} else {
								basket_items.setStyle('display', 'none');
							}
						}
						
						if (basket_is_empty) {
							if (response.basket_count > 0) {
								basket_is_empty.setStyle('display', 'none');
							} else {
								basket_is_empty.setStyle('display', 'block');
							}
						}
					}
					
				};
				request.onFailure = function(xhr) {
					cart_loader.setStyle('display', 'none');
				};
				el.set('send', {method: 'post', url: el.action + '?ajax=1'});
				cart_loader.setStyle('display', 'block');
				el.send();
				return false;
			});
		});
	}
});

var DJCatMatchBackgrounds = function(){
	
	//DJCatMatchModules('.djc_subcategory_bg', false, true);
	DJCatMatchModules('.djc_thumbnail', true, true);
	
	if (document.id(document.body).getElements('.djc_subcategory_row')) {
		document.id(document.body).getElements('.djc_subcategory_row').each(function(row, index){
			var elements = row.getElements('.djc_subcategory_bg');
			DJCatMatchModules(elements, false, true);
		});
	}
	
	if (document.id(document.body).getElements('.djc_item_row')) {
		document.id(document.body).getElements('.djc_item_row').each(function(row, index){
			var elements = row.getElements('.djc_item_bg');
			DJCatMatchModules(elements, false, true);
		});
	}
};

/*
window.addEvent('domready', function() {
	DJCatMatchBackgrounds();
});
*/

window.addEvent('load', function() {
	DJCatMatchBackgrounds();
	
	var djcatpagebreak_acc = new Fx.Accordion('.djc_tabs .accordion-toggle',
			'.djc_tabs .accordion-body', {
				alwaysHide : false,
				display : 0,
				duration : 100,
				onActive : function(toggler, element) {
					toggler.addClass('active');
					element.addClass('in');
				},
				onBackground : function(toggler, element) {
					toggler.removeClass('active');
					element.removeClass('in');
				}
			});
	var djcatpagebreak_tab = new Fx.Accordion('.djc_tabs li.nav-toggler',
			'.djc_tabs div.tab-pane', {
				alwaysHide : true,
				display : 0,
				duration : 150,
				onActive : function(toggler, element) {
					toggler.addClass('active');
					element.addClass('active');
				},
				onBackground : function(toggler, element) {
					toggler.removeClass('active');
					element.removeClass('active');
				}
			});
});

window.addEvent('resize', function(){
	DJCatMatchBackgrounds();
});
