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


var JASliderCSS = new Class({

	Implements: Options,
	
	options: {
		interval: 5000,
		duration: 2000,
		
		repeat: true,				//animation repeat or not
		autoplay: true,				//auto play
		
		navigation: false,			//show navigation controls or not
		thumbnail: false,			//show thumbnail or not
		
		urls: null,
		targets: null
	},
	
	initialize: function (element, options) {
		var jslider = $(element);
		
		if(!jslider){
			return false;
		}
		
		this.setOptions(options);
		
		var options = this.options,
			jmain = jslider.getElement('.ja-ss-items'),
			jitems = jslider.getElements('.ja-ss-item'),			
			vars = {
				jslider: jslider,
				jmain: jmain,
				jitems: jitems,
				
				total: jitems.length,
				curIdx: -1,
				nextIdx: -1,
				curImg: null,
				
				retain: 0,
				
				touch: 'ontouchstart' in window && !(/hp-tablet/gi).test(navigator.appVersion),
				
				running: 0,
				stop: 0,
				timer: 0,
				animFinished: this.animFinished.bind(this),
				vendor: (function () {
						var vendors = 't,webkitT,MozT,msT,OT'.split(','),
							dummyStyle = document.createElement('div').style,
							t, i = 0, l = vendors.length;

						for ( ; i < l; i++ ) {
							t = vendors[i] + 'ransform';
							if ( t in dummyStyle ) {
								return vendors[i].substr(0, vendors[i].length - 1).toLowerCase();
							}
						}

						return false;
					})()
			};
			
		if(vars.vendor !== false && !Browser.ie9){
			vars.vendor = vars.vendor ? '-' + vars.vendor.toLowerCase() + '-' : '';
		} else {
			vars.vendor = false;
		}

		// add a ghost item to solve the container width/height problem
		jitems[0].clone().setStyles ({'position':'relative', 'visibility':'hidden', 'z-index': 1}).addClass('ja-ss-item-ghost').inject (jmain,'top');
		
		// store original class 
		jitems.each (function(item){
			item._className = item.className;
		});
		this.vars = vars;
		
		this.initItemAction();
		this.initThumbAction();
		this.initControlAction();
		this.initKbNav();
		
		if(vars.touch){
			this.initTouchDevice();
		}

		vars.direct = 'next';
		jslider.setStyle('visibility', 'visible');
		
		this.prepare(vars.curIdx +1);
		this.animFinished();
	},
	
	stop: function(){
		clearTimeout(this.vars.timer);
		this.vars.stop = 1;
	},
	
	prev: function(){
		var vars = this.vars;
		if(vars.running){
			return false;
		}
		this.prepare(vars.curIdx -1);
	},
	
	next: function(){
		var vars = this.vars;
		if(vars.running){
			return false;
		}
		this.prepare(vars.curIdx +1);
	},
	
	playback: function(){
		this.vars.direct = 'prev';
		this.vars.stop = 0;
		this.prev();
	},
	
	play: function(){
		this.vars.direct = 'next';
		this.vars.stop = 0;
		this.next();
	},
	
	start: function(){
		var vars = this.vars;
		
		clearTimeout(vars.timer);
		vars.timer = setTimeout(this[this.vars.direct].bind(this), this.options.interval)
	},

	img: function(src, callback){
		var image = new Image();

		['load', 'abort', 'error'].each(function(name){
			var type = 'on' + name;

			image[type] = function(){
				if (!image) return;
				
				image = image.onload = image.onabort = image.onerror = null;
				
				if(typeOf(callback) == 'function'){
					callback.delay(1);
				}
			};
		});

		image.src = src;
		if (image && image.complete){
			image.onload.delay(1);
		}
	},
	
	load: function(idx){
		var vars = this.vars;
		vars.jitems[idx].store('loaded', 1);	//mark it as loaded
		
		vars.retain = Math.max(0, vars.retain - 1); //remove reference count to loading
	
		if(vars.nextIdx == idx){	//check, we are waiting for the image loaded
			this.run(idx);
		} else if(vars.nextIdx == -1){	//already passed, do not have to check
			if(vars.retain == 0){
				vars.jslider.removeClass('ja-ss-loading');
			}
		}
	},
	
	prepare: function(idx){
		var vars = this.vars,
			options = this.options;
			
		if(idx >= vars.total){		
			idx = 0;
		}
		
		if(idx < 0){
			idx = vars.total - 1;
		}
		
		var	curImg = vars.jitems[idx];
		if(curImg.get('tag') != 'img'){
			curImg = curImg.getElement('img');
		}
		
		//there was no image, we will run it immediately
		if (!curImg){
			return this.run(idx);
		}
		
		//if there was some image, preload it
		vars.nextIdx = idx;
		
		if(curImg.retrieve('loaded')){	//already loaded			
			return this.run(idx);
		} else{
			vars.running = true;
			vars.retain++;		//increase reference count
			vars.jslider.addClass('ja-ss-loading');
			
			this.img(curImg.src, this.load.bind(this, idx));
		}
	},
	
	run: function(idx){
		var vars = this.vars,
			options = this.options;
			
		vars.retain = 0; //reset reference count, no matter how many item loading - just for animation look better
		vars.jslider.removeClass('ja-ss-loading');
			
		if(vars.curIdx == idx){
			return false;
		}
		
		vars.running = true;

		if (vars.jthumbitems) {
			vars.jthumbitems.removeClass('active')[idx].addClass('active');

			if(vars.thumbscroll && vars.jthumbs){
				if (idx <= (vars.startidx + 1) || idx >= (vars.startidx + vars.visible - 1)) {
					vars.startidx = Math.max(0, Math.min(idx - vars.visible + 2, vars.total - vars.visible));

					if(vars.vendor !== false){
						vars.jthumbs.setStyle(vars.vendor + 'transform', 
							'translate' + vars.thumborient + '(' + (-Math.min(vars.startidx, vars.maxpercent) * 100) + '%)');
					} else {
						vars.thumbsFx.start(vars.thumborient, -Math.min(vars.startidx, vars.maxpercent) * 100);
					}	
				}
			}
		}
		
		this.slide(idx);
		
		vars.jslider.removeClass('ja-ss-progress');
	},
	
	slide: function(idx){
		var options = this.options,
				vars = this.vars;
		
		vars.jitems.each(function(item, index){
			var cls = (idx == index) ? 'curr active' : 
					(index < idx && (index != 0 || idx != vars.jitems.length-1)) ? 'prev' :
					(index > idx && (idx != 0 || index != vars.jitems.length-1)) ? 'next' :
					(idx == 0) ? 'prev' : 'next';
			// check if element move out/in
			if (item.hasClass ('curr') || cls.match(/curr/)) cls = 'animate ' + cls;

			// remove old classes and add new classes
			item.className = item._className + ' ' + cls;
		});
		
		clearTimeout(vars.timer);
		vars.timer = setTimeout(vars.animFinished, this.options.duration);
		vars.curIdx = idx;
	},
	
	animFinished: function(){ 
		var options = this.options,
			vars = this.vars;
			
		vars.running = false;
		
		if (!vars.stop && (options.autoplay && (vars.curIdx < vars.total -1 || options.repeat))) {
			this.start();
			
			vars.jslider.addClass('ja-ss-progress');
		}
	},
	
	initThumbAction: function () {
		var options = this.options;

		if (options.thumbnail) {
			var vars = this.vars,
				jslider = vars.jslider,
				jthumbs = vars.jslider.getElement('.ja-ss-thumbs'),
				jthumbwrap = vars.jslider.getElement('.ja-ss-thumbs-wrap'),
				jthumbitems = vars.jslider.getElements('.ja-ss-thumb');
				
			if(jthumbitems.length){
				jthumbitems.removeClass('active');
				
				for (var i = 0, il = jthumbitems.length; i < il; i++) {
					jthumbitems[i].addEvent('click', this.prepare.bind(this, i));
				}
				
				jthumbs.addEvent('mousewheel', function (e) {
					if (e.wheel < 0) {
						e.stop();
						this.next(true);
					} else {
						e.stop();
						this.prev(true);
					}
				}.bind(this));

				var jthumbimg = jthumbitems[0].getElement('img'),
					thumbready = function () {
						//check if need for animation
						var coord1 = jthumbitems[0].getCoordinates(),
							coord2 = jthumbitems[1].getCoordinates(),
							orient = 'width';

						if(Math.min(coord1.width, coord1.height) > 32){
							//has thumbnail
							
							if(coord1.top < coord2.top && coord1.left == coord2.left){
								orient = 'height';
							}

							var itemsize = coord1[orient],
								padd = 0;

							if(orient == 'width'){
								padd = jthumbitems[0].getStyle('margin-left').toInt()
									+ jthumbitems[0].getStyle('margin-right').toInt();
							} else {
								padd = jthumbitems[0].getStyle('margin-top').toInt()
									+ jthumbitems[0].getStyle('margin-bottom').toInt();
							}

							itemsize += padd;
							
							jthumbwrap.addClass('ja-ss-thumb-' + orient);
							jthumbs.setStyles({
								width: '',
								height: ''
							}).setStyle(orient, itemsize);

							var visiblesize = jthumbwrap['get' + orient.capitalize()](),
								visible = Math.floor(visiblesize / itemsize),
								maxsize = (itemsize * jthumbitems.length - visiblesize - padd),
								maxpercent = (maxsize <= 0 ? (maxsize / 2) : maxsize) / itemsize;

							if(vars.vendor === false && !vars.thumbsFx){
								new Element('div', {'class': 'ja-ss-thumbie'}).wraps(jthumbs).setStyle('position', 'relative').setStyle(orient, itemsize);

								vars.thumbsFx = new Fx.Tween(jthumbs, {
									duration: 500,
									link: 'cancel',
									unit: '%'
								});
							}

							Object.append(vars, {
								thumbscroll: true,
								thumborient: (vars.vendor !== false ? (orient == 'width' ? 'X' : 'Y') : (orient == 'width' ? 'left' : 'top')),
								total: jthumbitems.length,
								visible: visible,
								maxpercent: maxpercent,
								jthumbs: jthumbs,
								startidx: vars.startidx || 0
							});

							if(vars.vendor !== false){
								vars.jthumbs.setStyle(vars.vendor + 'transform', 
									'translate' + vars.thumborient + '(' + (-Math.min(vars.startidx, vars.maxpercent) * 100) + '%)');
							} else {
								vars.thumbsFx.start(vars.thumborient, -Math.min(vars.startidx, vars.maxpercent) * 100);
							}
						} else {

							Object.append(vars, {
								thumbscroll: false,
								jthumbitems: jthumbitems
							});
						}
					};

				window.addEvent('resize', function(){
					if(vars.thumborient){
						clearTimeout(vars.tsid);
						vars.tsid = setTimeout(thumbready, 500);
					}
				});

				if(jthumbimg){
					this.img(jthumbimg.get('src'), thumbready);
				} else {
					thumbready.delay(10);
				}
				
				vars.jthumbitems = jthumbitems;
			}
		}
	},

	initControlAction: function () {
		var btnarr,
			options = this.options;
			
		if(options.navigation){
			var jslider = this.vars.jslider,
				controls = ['prev', 'play', 'stop', 'playback', 'next'];
				
			for (var j = 0, jl = controls.length; j < jl; j++) {
				if(this[controls[j]]){
					btnarr = jslider.getElements('.ja-ss-' + controls[j]);
					
					for (var i = 0, il = btnarr.length; i < il; i++) {
						btnarr[i].addEvent('click', this[controls[j]].bind(this, true));
					}
				}
			}
		}
	},
	
	initItemAction: function(){
		var options = this.options;

		if (options.urls) {
			var vars = this.vars,
				anchor = function(from, limit){
					if(!limit){
						limit = vars.jslider;
					}

					while(from && from != limit){
						if(from.get('tag').toLowerCase() == 'a'){
							return from;
						}
						
						from = from.getParent();
					}

					return null;
				},

				handle = function(e){
					var index = vars.jitems.indexOf(this);
						
					if(index == -1){
						index = vars.curIdx;
					}
					
					var url = options.urls[index],
						target = options.targets[index],
						link = anchor(e.target);

					if(link && link.href != '' && link.href != window.location.href){
						return true;
					}

					if (url) {
						e.stop();
						
						if (target == '_blank'){
							window.open(url, 'JAWindow');
						} else {
							window.location.href = url;
						}
					}
					
					return false;
				};
			
			vars.jmain.addEvent('click', handle);
			vars.jitems.addEvent('click', handle);
		}
	},
	
	initTouchDevice: function(){
		var	inst = this,
			vars = this.vars,
			ltouch = function(){
				vars.ltouch = true;
			},
			start = function(e){
				
				clearTimeout(vars.ltid);

				var point = e.touches[0];
				
				vars.moved = false;
				vars.px = point.pageX;
				vars.py = point.pageY;
				vars.tm = e.timeStamp || new Date().getTime();
				vars.ltouch = false;
				vars.ltid = setTimeout(ltouch, 150);
				vars.tcancel = false;

				document.addEvent('touchmove', move);
				vars.jslider.addEvent('touchend', end);
			},
			move = function(e){
				clearTimeout(vars.ltid);

				var point = e.changedTouches[0];

				if(!vars.tcancel && !vars.ltouch && Math.abs(point.pageX - vars.px) > Math.abs(point.pageY - vars.py)){
					e.stop();
				
					var tm = e.timeStamp || new Date().getTime();
					if(tm - vars.tm > 300){
						vars.tm = tm;
						vars.px = point.pageX;
					}
					
					vars.moved = true;

				} else {
					vars.tcancel = true;
					return;
				}
			},
			end = function(e){
				
				document.removeEvent('touchmove', move);
				vars.jslider.removeEvent('touchend', end);

				if (e.touches.length != 0 || vars.tcancel){
					return;
				}
				
				var point = e.changedTouches[0];
				
				if(!vars.moved){
					var target = point.target;
					while (target.nodeType != 1){
						target = target.parentNode;
					}

					if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA') {
						var ev = document.createEvent('MouseEvents');
						ev.initMouseEvent('click', true, true, e.view, 1,
							point.screenX, point.screenY, point.clientX, point.clientY,
							e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
							0, null);
						ev._fake = true;
						target.dispatchEvent(ev);
					}

				} else if(((e.timeStamp || new Date().getTime()) - vars.tm) < 300) {
					
					if(point.pageX - vars.px > 30){
						inst.prev(true);
					} else if (point.pageX - vars.px < -30) {
						inst.next(true);
					}
				}
			};
		
		vars.jslider.addEvent('touchstart', start);
	},
	
	initKbNav: function(){
		document.addEvent('keydown', function(e){
			if (e.code == 39 || e.code == 40) {
				this.next();
			} else if (e.code == 37 || e.code == 38) {
				this.prev();
			}
		}.bind(this));
	}
});
