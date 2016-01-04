function initProfiles(source) {
	var ptitles = $$('#ja-'+source+'-content .ja-profile-titles li.ja-profile');
	
	//load profile
    ptitles.each (function (el) {
		isdefault = el.hasClass('default');
		isActive = el.hasClass('active');
		if(isActive) {
			jaEditProfile(source, el.getProperty('rel'));
		}
		el.addEvent(
			'click',
			function (){
				changeProfile(source, el, false);
			}.bind(this)
		);
	}, this);
	
	//profile actions
	$$('#ja-'+source+'-content span.ja-profile-action').addEvent ('click', function (event) {
		//var event = new Event(event);
		
		if(this.getParent().hasClass('active')) {
			var profile = this.getProperty('rel');
			var offset = $('ja-profile-'+source+'-'+profile).getCoordinates().left;
			$('ja-profile-action').setStyles ({
				'display': 'inline',
				'top': event.page.y,
				'left': offset
			});
			$('ja-profile-action').setProperty('rel', source + '|' + profile);
		}
		event.stop();
	});
}

function resetProfileForm(source) {
	$('jform_params_'+source+'_account').value = '';
}

function changeProfile(source, obj, isdefault) {
	/* Set tab activity */
	var lis = $$('#ja-'+source+'-content .ja-profile-titles li.ja-profile');
	var pre_Obj = null;
	var item = null;
	for(var i=0; i<lis.length; i++){
		item = lis[i];
		if(item.className.indexOf('active')>-1){
			item.removeClass('active');
			pre_Obj = item;
			break;
		}
	}

	obj.addClass('active');
	
	jaEditProfile(source, obj.getElement('.ja-profile-title').getProperty('rel'));
}

function jaNewProfile(source, obj) {
	var profile = prompt('Please enter profile name', '');
	if(profile) {
		var req = new Request({
                method: 'get',
                url: plugin_admin_url+'?jatask=new_profile&profile_source='+source+'&profile='+profile,
                onRequest: function() { 
                        // on request
                },
                onComplete: function(response) {
                    var obj = JSON.decode(response);
                    
                    if(obj.status == 'error') {
                    	alert(obj.message);
                    	return false;
                    } else {
                    	//alert(obj.message);
                    	var result = obj.result;
                    	
                    	var source = result.source;
                    	var profile = result.profile;
						
						resetProfileForm(source);
                    	Object.each(result.data, function(value, key){
						    if($('jform_params_'+key)) {
						    	$('jform_params_'+key).set('value', value);
						    }
						});
                    	
                    	var listPro = $('list-profiles-'+source);
                    	listPro.getElements('li.active').each(function(el) {
                    		el.removeClass('active');
                    	});
                    	
                    	var li = new Element('li', { 
							'id': 'ja-profile-'+source+'-'+profile,
                    		'class': 'ja-profile active', 
                    		'rel': result.profile, 
                    		events: {
						        click: function(){
						            changeProfile(source, this, false);
						        }
						    } 
                    	});
                    	var stitle = new Element('span', { 
                    		'class': 'ja-profile-title', 
                    		'rel': result.profile, 
                    		html: result.profile
                    	});
                    	var saction = new Element('span', { 
                    		'class': 'ja-profile-action', 
                    		'rel': result.profile, 
                    		'html': '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                    		events: {
						        click: function(event){
						            var event = new Event(event);
	        
							        if(this.getParent().hasClass('active')) {
							        	var offset = $('ja-profile-'+source+'-'+this.getProperty('rel')).getCoordinates().left;
								        $('ja-profile-action').setStyles ({
								        	'display': 'inline',
								            'top': event.page.y,
								            'left': offset
								        });
										$('ja-profile-action').setProperty('rel', source + '|' + this.getProperty('rel'));
							        }
							        event.stop();
						        }
						    }  
                    	});
                    	
                    	li.appendChild(stitle);
                    	li.appendChild(saction);
                    	
                    	listPro.appendChild(li);
                    	
                    	//change profile
                    	$('ja-profile-'+source+'-'+profile).fireEvent('click');
                    	
                    	return true;
                    }
                }
        }).send();
	}
}

function jaEditProfile(source, profile) {
	
	$('profile_'+source).value = profile;
	
	/*if($('ja-profile-'+source+'-'+profile)) {
		if($('ja-profile-'+source+'-'+profile).hasClass('loaded')) {
			return true;
		}
	}*/
	
	var req = new Request({
                method: 'post',
                url: plugin_admin_url+'?jatask=edit_profile&profile_source='+source+'&profile='+profile,
                onRequest: function() { 
                        // on request
                },
                onComplete: function(response) {
                    var obj = JSON.decode(response);
                    
                    if(obj.status == 'error') {
                    	alert(obj.message);
                    	return false;
                    } else {
                    	var result = obj.result;
                    	
						resetProfileForm(source);
                    	var skey = '';
                    	Object.each(result, function(value, key){
						    if($('jform_params_'+key)) {
							    //Joomla 3.0 dropdown box
							    if($('jform_params_'+key+'_chzn') && typeof(jQuery) !== 'undefined') {
							    	var chosen = jQuery('#jform_params_'+key).val(value).trigger("liszt:updated").data('chosen');
									if(chosen){
										chosen.current_value = value;
									}
							    	//$('jform_params_'+key+'_chzn_o_'+selectedIndex).fireEvent('click');
							    } else {
							    	$('jform_params_'+key).set('value', value);
							    }
						    }
						});
						
                    	if($('ja-profile-'+source+'-'+profile)) {
                    		//mark as loaded
                    		$('ja-profile-'+source+'-'+profile).addClass('loaded');
							//update show/hide elements
							$('jform_params_'+source+'_target').fireEvent('change');
							if($('jform_params_'+source+'_title_type')) {
								$('jform_params_'+source+'_title_type').fireEvent('change');
							}
                    	}
                    }
                }
        }).send();
}

function jaDeleteProfile(source, profile) {
	if(!confirm('Are you sure?')) {
		return false;
	}
	var req = new Request({
                method: 'get',
                url: plugin_admin_url+'?jatask=delete_profile&profile_source='+source+'&profile='+profile,
                onRequest: function() { 
                        // on request
                },
                onComplete: function(response) {
                    var obj = JSON.decode(response);
                    
                    if(obj.status == 'error') {
                    	alert(obj.message);
                    	return false;
                    } else {
						var result = obj.result;
						var source = result.source;
						var profile = result.profile;
						
						var id = 'ja-profile-'+source+'-'+profile;
						if($(id)) {
							$(id).dispose();
						}
						//set first profile as active
						var ptitles = $$('#ja-'+source+'-content .ja-profile-titles li.ja-profile');
						if(ptitles.length) {
							ptitles[0].fireEvent('click');
						}
						
                    	return true;
                    }
                }
        }).send();
}

function jaSaveProfile(source) {
	//alert(source);
	var profile = $('profile_'+source).value;
	if(profile == '') {
		profile = prompt('Please enter profile name', '');
		if(!profile) {
			return;
		}
	}
	
    var queryString=$(document.adminForm).toQueryString();
    
    var req = new Request({
                method: 'post',
                url: plugin_admin_url+'?jatask=save_profile&profile_source='+source,
                data: queryString,
                onRequest: function() { 
                        // on request
                },
                onComplete: function(response) {
                    var obj = JSON.decode(response);
                    alert(obj.message);
                }
        }).send();
}

function jaTestFacebook(pid) {
	$('test_'+pid).setProperty('href', 'http://www.facebook.com/feeds/page.php?id='+$(pid).value+'&format=rss20');
}

function jaTestTwitter(pid) {
	$('test_'+pid).setProperty('href', 'https://twitter.com/#!/search/'+encodeURIComponent($(pid).value));
}

function jaTestYoutube(pid) {
	$('test_'+pid).setProperty('href', 'http://gdata.youtube.com/feeds/base/users/'+encodeURIComponent($(pid).value)+'/uploads?orderby=updated&alt=rss');
}

function jaTestVimeo(pid) {
	//user, group, album, channel
	
	var vtype = $(pid+'_type').value;
	var vid = $(pid).value;
	var url = '';
	switch (vtype) {
		case 'user': url = 'http://vimeo.com/api/v2/'+vid+'/all_videos.xml'; break;
		case 'album': url = 'http://vimeo.com/api/v2/album/'+vid+'/videos.xml'; break;
		case 'group': url = 'http://vimeo.com/api/v2/group/'+vid+'/videos.xml'; break;
		case 'channel': url = 'http://vimeo.com/api/v2/channel/'+vid+'/videos.xml'; break;
	}
	$('test_'+pid).setProperty('href', url);
}

function jaTestInstagram(pid) {
	var keyword = $(pid).value;
	var url = 'http://widget.stagram.com/rss/popular/';
	if(keyword.toUpperCase() == '[POPULAR]') {
		url = "http://widget.stagram.com/rss/popular/";
	} else if (keyword.substr(0, 1) == '@') {
		keyword = keyword.substr(1);
		url = 'http://widget.stagram.com/rss/n/'+keyword+'/';
	} else if (keyword.substr(0, 1) == '#') {
		keyword = keyword.substr(1);
		url = 'http://widget.stagram.com/rss/tag/'+keyword+'/';
	} else {
		url = 'http://widget.stagram.com/rss/tag/'+keyword+'/';
	}
	$('test_'+pid).setProperty('href', url);
}

function jaTestFlickr(pid) {
	var keyword = $(pid).value;
	var url = 'http://api.flickr.com/services/feeds/photos_public.gne?format=rss2';
	
	var re = /\d+\@[^\@]+/;
	if (re.test(keyword)) {
		url = url+'&id='+keyword;
	} else {
		url = url+'&tags='+keyword;
	}
	$('test_'+pid).setProperty('href', url);
}