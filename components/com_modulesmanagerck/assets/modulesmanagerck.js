/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

var $ck = jQuery.noConflict();

$ck(document).ready(function(){
	ckloadModulesList();
	createModulesManagercks();
	$ck(document.body).append('<div id="ckmoduleoptions"></div>');
	$ck(document.body).append('<div id="ckmessagebox"></div>');
	$ck(document.body).append('<div id="ckoverlay"></div>');
	$ck(document.body).append('<div id="ckmoduleslist"></div>');
	MMCKswitchModulesDisplay('onlypage');
	if ($ck('#managerbutton').hasClass('active')) $ck('#managerbutton').draggable();
	$ck( ".cktip" ).tooltip({ tooltipClass: "cktooltip", position: { my: "left+15 center", at: "right center" } });
});

function initModulesManagerck() {
	$ck(".ckmodulesinner").sortable({
		connectWith: ".ckmodulesinner",
		dropOnEmpty: true ,
		handle: ".ckhandler",
		activate: function (event, ui) {
			if (ui != undefined) {
				 $ck(ui.item).css("width", "250px");
			}
		}
	}).disableSelection();
}

function createModulesManagercks() {
	$ck(".ckmodulesinner").each(function(i, moduleposition) {
		moduleposition = $ck(moduleposition);
		moduleposition.find(".ckmodule").each(function(){
			addControls($ck(this));
		});
		moduleposition.sortable({
			connectWith: ".ckmodulesinner",
			dropOnEmpty: true ,
			handle: ".ckhandler",
			beforeStop: function(event, ui){
				hasbeendropped = true;
				pks = new Array();
				order = new Array();

				moduleposition.find(".ckmodule").each(function(){
					index = parseInt($ck(this).index());
					pks.push($ck(this).attr('ckid'));
					order.push($ck(this).index());
					if ($ck(this).attr('ckid') == $ck(ui.helper).attr('ckid')) hasbeendropped = false;
				});
				if (!hasbeendropped) {
					ajaxSaveOrder(pks, order);
				} else {
					// if (ui != undefined) {
						// ajaxSavePosition($ck(ui.item).attr('ckid'), $ck(ui.item).parents('.ckmodules').attr('ckposition'), pks, order);
					// }
				}
				if (ui != undefined) {
					$ck(ui.item).removeClass("ckmoduledrag");
				}
			},
			stop: function(event, ui){
				if (ui != undefined) {
					$ck(ui.item).removeClass("ckmoduledrag");
				}
			},
			activate: function (event, ui) {
				if (ui != undefined) {
					$ck(ui.item).addClass("ckmoduledrag");
				}
			},
			receive: function(event, ui){
				pks = new Array();
				order = new Array();

				$ck(ui.item.parent()).find(".ckmodule").each(function(){
					index = parseInt($ck(this).index());
					pks.push($ck(this).attr('ckid'));
					order.push($ck(this).index());
				});

				if (ui != undefined) {
					ajaxSavePosition($ck(ui.item).attr('ckid'), $ck(this).parents('.ckmodules').attr('ckposition'), pks, order);
				}
			}
		});
	});
}

function addControls(el) {
	el = $ck(el);
	el.find(".cktitle").tooltip({ tooltipClass: "cktooltip", position: { my: "left+10 top+20", at: "right center" } });
	el.find(".cktitle").dblclick(function(){
		txt = $ck(this).text();
		$ck(this).html("<input value='"+txt+"' />");
		$ck(this).attr('text-origin', txt);
		$ck(this).find("input").select();
	});

	el.find(".ckremove").click(function(){
		if (confirm('Are you sure to completely remove this module ?')) {
			ckRemoveModule(el);
		}
	});

	el.find(".ckpublish").click(function(){
		ajaxPublish(el, el.attr('ckid'), el.attr('ckpublished'));
	})

	el.find(".ckoptions").click(function(){
		ckLoadModuleOptions(el.attr('ckid'));
	});

	el.on("blur", ".cktitle input", function(){
		txt = $ck(this).val();
		if (txt && txt != $ck(this).parent().attr('text-origin')) {
			ajaxSaveTitle(el.attr('ckid'), txt);
		}
		if (txt) {
			$ck(this).parent().html(txt);
		}
	});

	el.on("keyup", ".cktitle input", function(e) {
		if(e.keyCode == 13) {
			$ck(this).trigger("blur");
		}
	});
}

function ajaxSaveOrder(cid, order) {
	ckwaitmessage();
	var myurl = "index.php?option=com_modulesmanagerck&task=modules.saveOrderAjax&format=raw";
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		cid: cid,
		order: order
		}
	}).done(function(code) {
		cksuccessmessage();
	}).fail(function(code) {
		ckerrormessage();
	});
}

function ajaxSaveTitle(id, title) {
	ckwaitmessage();
	var myurl = "index.php?option=com_modulesmanagerck&task=modules.saveTitleAjax&format=raw";
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		id: id,
		title: title
		}
	}).done(function(code) {
		cksuccessmessage();
	}).fail(function(code) {
		ckerrormessage();
	});
}

function ajaxSavePosition(id, position, pks, order) {
	ckwaitmessage();
	var myurl = "index.php?option=com_modulesmanagerck&task=modules.savePositionAjax&format=raw";
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		id: id,
		position: position
		}
	}).done(function(code) {
		if ($ck.trim(code) == $ck.trim(position)) {
			ajaxSaveOrder(pks, order);
		} else {
			box = $ck('#ckmessagebox');
			box.show('fast').removeClass('ckwait').addClass('ckerror').text('Error while processsing ! Position not updated');
		}
	}).fail(function(code) {
		ckerrormessage();
	});
}

function ajaxPublish(el, id, state) {
	$ck(el).addClass('ckwait');
	var myurl = "index.php?option=com_modulesmanagerck&task=modules.publishAjax&format=raw";
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		id: id,
		state: state
		}
	}).done(function(code) {
		if (code == 1 || code == 0) {
			$ck(el).removeClass('ckwait').attr('ckpublished', parseInt(code));
		} else {
			box = $ck('#ckmessagebox');
			box.show('fast').removeClass('ckwait').addClass('ckerror').text('Error while processsing ! State not updated');
		}
	}).fail(function() {
		ckerrormessage();
	});
}

function addckwaiticon(id) {
	button = $ck('#publish'+id);
	button.find('i').attr('class', 'icon-');
	button.addClass('ckwait');
}

function removeckwaiticon(id) {
	button = $ck('#publish'+id);
	button.removeClass('ckwait');
}

function ckRemoveModule(module) {
	ckwaitmessage();
	var myurl = "index.php?option=com_modulesmanagerck&task=modules.removeModuleAjax&format=raw";
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		id: module.attr('ckid')
		}
	}).done(function(code) {
		if (code) {
			$ck(module).remove();
			cksuccessmessage();
		} else {
			ckerrormessage();
		}
	}).fail(function() {
		ckerrormessage();
	});
}

function ckloadModulesList() {
	var myurl = "index.php?option=com_modulesmanagerck&view=modules&layout=select&format=raw";
	jQuery.ajax({
	type: "POST",
	url: myurl
	}).done(function(code) {
		$ck('#ckmoduleslist').append(code);
		modulesList = $ck('#ckmoduleslist');
		modulesListClose = $ck('<div id="ckmoduleslistclose" />').text('X').click(function() {
			modulesList.fadeOut();
			$ck('.activeposition').removeClass('activeposition');
			hideOverlay();
		});
		modulesList.prepend(modulesListClose);
	}).fail(function() {
		ckerrormessage();
	});
}

function ckShowModulesList(positioncontainer) {
	positioncontainer.addClass('activeposition');
	$ck('#ckmoduleslist').show( 'slide', {'direction':'up'} );
	showOverlay();
}

function ckAddModule(extensionid, type) {
	ckwaitmessage();
	var myurl = "index.php?option=com_modulesmanagerck&task=modules.addModuleAjax&format=raw";
	jQuery.ajax({
	type: "POST",
	url: myurl,
	data: {
		extensionid: extensionid,
		position: $ck('.activeposition').attr('ckposition'),
		ordering: $ck('.ckmodule', $ck('.activeposition')).length,
		type: type
		}
	}).done(function(code) {
		newmodule = $ck(code);
		addControls(newmodule);
		$ck('.activeposition > .ckmodulesinner').append(newmodule);
		newmodule.hide().fadeIn('slow');
		$ck('#ckmoduleslist').fadeOut();
		$ck('.activeposition').removeClass('activeposition');
		ckLoadModuleOptions(newmodule.attr('ckid'));
		cksuccessmessage();
	}).fail(function(code) {
		ckerrormessage();
		hideOverlay();
	});
}

function ckLoadModuleOptions(id) {
	ckwaitmessage();
	showOverlay();
	var frame =  '<iframe frameborder="0" onload="$ck(\'#ckmessagebox\').removeClass(\'ckwait\').hide();" height="'+$ck(window).outerHeight()+'" width="100%" src="index.php?option=com_modulesmanagerck&task=module.edit&id='+id+'&tmpl=component" style="overflow:auto;">';
	$ck('#ckmoduleoptions').empty().append(frame).show( 'slide', {'direction':'up'} );
}

function ckCloseModuleOptions(){
	$ck('#ckmoduleoptions').empty().hide();
	hideOverlay();
}

function ckwaitmessage() {
	box = $ck('#ckmessagebox');
	box.show().removeClass('cksuccess').removeClass('ckerror').addClass('ckwait').text('Please wait ...');
}

function cksuccessmessage() {
	box = $ck('#ckmessagebox');
	box.removeClass('ckwait').removeClass('ckerror').addClass('cksuccess').text('Success !').delay(700).hide('fast');
}

function ckerrormessage() {
	box = $ck('#ckmessagebox');
	box.show('fast').removeClass('ckwait').addClass('ckerror').text('Error while processsing ! Please reload the page !');
}

function showOverlay() {
	$ck('#ckoverlay').height($ck(document.body).outerHeight()).show();
}

function hideOverlay() {
	$ck('#ckoverlay').hide();
}

function MMCKswitchModulesDisplay(type) {
	if (type == 'all') {
		$ck('.ckmodule').show();
	} else if (type == 'allpublished') {
		$ck('.ckmodule[ckpublished=1]').show();
		$ck('.ckmodule[ckpublished=0]').hide();
	} else {
		$ck('.ckmodule[ckpageonly=0]').hide();
	}
}