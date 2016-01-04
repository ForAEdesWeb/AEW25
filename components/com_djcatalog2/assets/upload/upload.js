
/**
 * @version $Id: upload.js 272 2014-05-21 10:25:49Z michal $
 * @package DJ-Catalog2
 * @subpackage DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michał Olczyk michal.olczyk@design-joomla.eu
 */

function DJC2PlUploadStartUploadImage(up,files) {
	return DJC2PlUploadStartUpload(up, files, 'image');
}

function DJC2PlUploadStartUploadFile(up,files) {
	return DJC2PlUploadStartUpload(up, files, 'file');
}

function DJC2PlUploadStartUpload(up, files, prefix) {
	
	var wrapper = document.id('djc_uploader_'+prefix+'_items');
	var total = wrapper.getElements('.djc_uploader_item').length;
	var limit = parseInt(wrapper.getProperty('data-limit'));
	
	var limitreached = false;
	
	if (total + files.length >= limit && limit >= 0) {
		var remaining = limit - total;
		var toRemove = files.length - remaining;
		
		if (toRemove > 0 && files.length > 0){
			limitreached = true;
			for (var i = files.length-1; i >= 0; i--) {
				if (toRemove <= 0) {
					break;
				}
				up.removeFile(up.files[i]);					
				toRemove--;
			}		
		}					   				
	}
	
	if (limitreached) {
		alert(DJCatalog2UploaderVars.lang.limitreached);
	}
	
	up.start();
}

function DJC2PlUploadInjectUploadedImage(up,file,info) {
	var prefix = 'image';
	
	var response = JSON.decode(info.response); 
	if(response.error) {
		file.status = plupload.FAILED;
		file.name += ' - ' + response.error.message;
		document.id(file.id).addClass('ui-state-error');
		document.id(file.id).getElement('td.plupload_file_name').appendText(' - ' + response.error.message);
		return false;
	}
	
	var html = '<td class="center"><img src="'+DJCatalog2UploaderVars.url+'tmp/djc2upload/'+file.target_name+'" alt="'+file.name+'" />';
	html += '<input type="hidden" name="'+prefix+'_file_id[]" value="0" />';
	html += '<input type="hidden" name="'+prefix+'_file_name[]" value="'+file.target_name+'" />';
	html += '</td>';
	html += '<td><input type="text" class="djc_uploader_caption inputbox input input-medium" name="'+prefix+'_caption[]" value="'+DJCatalog2MUStripExt(file.name)+'" /></td>';
	html += '<td class="center"><input type="checkbox" onchange="DJCatalog2UPExcludeCheckbox(this);" /><input type="hidden" name="'+prefix+'_exclude[]" value="0" class="djc_hiddencheckbox" /></td>';
	html += '<td class="center"><button class="button btn djc_uploader_remove_btn">'+DJCatalog2UploaderVars.lang.remove+'</button></td>';
	
	var item = new Element('tr',{'class':'djc_uploader_item', html: html});
	DJCatalog2MUInitItemEvents(item);
	
	// add uploaded image to the list and make it sortable
	item.inject(document.id('djc_uploader_'+prefix+'_items'), 'bottom');
	this.DJCatalog2MUUploaders['djc_uploader_'+prefix].addItems(item);
	
	up.removeFile(file);
	
	return true;
}

function DJC2PlUploadInjectUploadedFile(up,file,info) {
	var prefix = 'file';
	
	var response = JSON.decode(info.response); 
	if(response.error) {
		file.status = plupload.FAILED;
		file.name += ' - ' + response.error.message;
		document.id(file.id).addClass('ui-state-error');
		document.id(file.id).getElement('td.plupload_file_name').appendText(' - ' + response.error.message);
		return false;
	}
	
	var html = '<td class="center">'+file.name;
	html += '<input type="hidden" name="'+prefix+'_file_id[]" value="0">';
	html += '<input type="hidden" name="'+prefix+'_file_name[]" value="'+file.target_name+'">';
	html += '</td>';
	html += '<td><input type="text" class="djc_uploader_caption inputbox input input-medium" name="'+prefix+'_caption[]" value="'+DJCatalog2MUStripExt(file.name)+'" /></td>';
	html += '<td class="center">';
	
	if (DJCatalog2UploaderVars.client == 1) {
		html +='<input type="text" class="djc_uploader_hits inputbox input input-small" name="'+prefix+'_hits[]" value="0" readonly="readonly" />';	
	} else {
		html +='<span>0</span>';
	}
	
	html += '</td>';
	html += '<td class="center"><button class="button btn djc_uploader_remove_btn">'+DJCatalog2UploaderVars.lang.remove+'</button></td>';
	
	var item = new Element('tr',{'class':'djc_uploader_item', html: html});
	DJCatalog2MUInitItemEvents(item);
	
	// add uploaded image to the list and make it sortable
	item.inject(document.id('djc_uploader_'+prefix+'_items'), 'bottom');
	this.DJCatalog2MUUploaders['djc_uploader_'+prefix].addItems(item);
	
	up.removeFile(file);
	
	return true;
}

function DJCatalog2MUInitItemEvents(item) {
	
	if(!item) return;
	item.getElement('.djc_uploader_remove_btn').addEvent('click',function(){
		(function(){item.dispose();}).delay(50);
		return false;
	});
	item.getElements('input').each(function(input){
		input.addEvent('focus',function(){
			item.addClass('active');
		});
		input.addEvent('blur',function(){
			item.removeClass('active');
		});
	});
}

function DJCatalog2MUStripExt(filename) {
	
	var pattern = /\.[^.]+$/;
	return filename.replace(pattern, "");	
}

function DJCatalog2UPExcludeCheckbox(element){
	var p = element.parentNode;
	var inputs = p.getElementsByClassName('djc_hiddencheckbox');
	if (inputs.length == 0) {
		return false;
	}

	for (var k in inputs) {
		if (inputs.hasOwnProperty(k) && typeof inputs[k].type != 'undefined' && typeof inputs[k].name != 'undefined') {
			if (typeof element.checked != 'undefined' && element.checked) {
				inputs[k].value = '1';
			} else {
				inputs[k].value = '0';
			}
		}
	}
	return false;
}

function DJCatalog2UPAddUploader(suffix, wrapper_id){
	
	var wrapper = document.id('djc_uploader_'+suffix+'_items');
	var total = wrapper.getElements('.djc_uploader_item').length + wrapper.getElements('.djc_uploader_item_simple').length;
	var limit = parseInt(wrapper.getProperty('data-limit'));
	
	if (total >= limit && limit >= 0) {
		return false;				   				
	}
	
    var copy = document.id('djc_uploader_simple_'+suffix).clone().inject(wrapper_id + '_items', 'bottom');
    copy.setStyle('display', '');
    
    return false;
}

window.addEvent('domready', function(){
	this.DJCatalog2MUUploaders = [];
	
	var uploaders = $$('.djc_uploader');
	uploaders.each(function(element){
		id = element.id;
		if (id) {
			instance = document.id(document.body).getElement('#'+id + ' .djc_uploader_items');
			this.DJCatalog2MUUploaders[id] = new Sortables(instance,{
				clone: false,
				revert: {duration:'short',transition:'expo:out'},
				opacity: 0.5
			}); 
		}
	});
	
	$$('.djc_uploader_item').each(function(item){
		DJCatalog2MUInitItemEvents(item);
	});
});