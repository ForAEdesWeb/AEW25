function recreateThumbnails(id,type) {
	if (type != '' && type != 'item' && type != 'producer' && type != 'category'){
		return false;
	}
	
	var allButtons = document.getElements('button.recreator_button');
	var logArea = document.id('djc_thumbrecreator_log');
	var startFrom = document.id('djc_thumbrecreator_start');
	
	if (window.DJCatalog2AllowRecreation == false) {
		allButtons.each(function(e){e.removeAttribute('disabled');});
		window.DJCatalog2AllowRecreation = true;
		logArea.innerHTML = 'STOPPED BY USER!\n'+logArea.innerHTML;
		return;
	}
	
	if (!id && startFrom) {
		if (parseInt(startFrom.value) > 0) {
			id = parseInt(startFrom.value);
		}
	}
	
	var recAjax = new Request({
	    url: 'index.php?option=com_djcatalog2&task=thumbs.go&tmpl=component&format=raw&image_id=' + id + '&type=' + type,
	    method: 'post',
	    encoding: 'utf-8',
	    onSuccess: function(response) {
	    	var recProgressBar = document.id('djc_progress_bar');
			var recProgressPercent = document.id('djc_progress_percent');
			
			if (response == 'end') {
				allButtons.each(function(e){e.removeAttribute('disabled');});
				recProgressBar.setStyle('width','100%');
				recProgressPercent.innerHTML = '100%';
				logArea.innerHTML = 'DONE!\n'+logArea.innerHTML;
				return true;
			} else if (response == 'error') {
				logArea.innerHTML = 'Unexpected error\n' + logArea.innerHTML;
				allButtons.each(function(e){e.removeAttribute('disabled');});
				recProgressBar.setStyle('width','0');
				recProgressPercent.innerHTML = '0%';
			}
			else {
				var jsonObj = null;
				try {
					jsonObj = JSON.decode(response);
				} catch(err) {
					logArea.innerHTML = 'ERROR!'+ response + '\n' + logArea.innerHTML;
					if (startFrom) {
						startFrom.value = parseInt(id)+1;
					}
					return recreateThumbnails(parseInt(id)+1, type);
				}

				var percentage = (((jsonObj.total - jsonObj.left) / jsonObj.total) * 100);

				recProgressBar.setStyle('width',percentage + '%');
				recProgressPercent.innerHTML = percentage.toFixed(2) + '%';
				logArea.innerHTML = ('OK! ID:TYPE:NAME='+ jsonObj.id +':' + jsonObj.type + ':' + jsonObj.name + '\n') + logArea.innerHTML;
				if (startFrom) {
					startFrom.value = jsonObj.id;
				}
				return recreateThumbnails(jsonObj.id, type);
			}
		}
	});
	recAjax.send();
}

function purgeThumbnails() {
	var recAjax = new Request({
	    url: 'index.php?option=com_djcatalog2&task=thumbs.purge&tmpl=component&format=raw',
	    method: 'post',
	    encoding: 'utf-8',
	    onSuccess: function(response) {
	    	alert(response);
	    	window.location.replace(window.location.toString());
		}
	});
	recAjax.send();
}

window.addEvent('domready', function(){
	var recButton = document.id('djc_start_recreation');
	var recItemButton = document.id('djc_start_recreation_item');
	var recCatButton = document.id('djc_start_recreation_category');
	var recProdButton = document.id('djc_start_recreation_producer');
	
	var allButtons = document.getElements('button.recreator_button');
	
	var recProgressBar = document.id('djc_progress_bar');
	var recProgressPercent = document.id('djc_progress_percent');
	
	var stopButton = document.id('djc_thumbrecreator_stop');
	if (stopButton) {
		stopButton.addEvent('click',function(){
			window.DJCatalog2AllowRecreation = false;
		});
	}
	
	this.DJCatalog2AllowRecreation = true;
	
	if (recButton && recProgressBar && recProgressPercent) {
		allButtons.each(function(e){e.removeAttribute('disabled');});
		recButton.addEvent('click',function(){
			window.DJCatalog2AllowRecreation = true;
			allButtons.each(function(e){e.setAttribute('disabled', 'disabled');});
			recreateThumbnails(0,'');
		});
		recItemButton.addEvent('click',function(){
			window.DJCatalog2AllowRecreation = true;
			allButtons.each(function(e){e.setAttribute('disabled', 'disabled');});
			recreateThumbnails(0,'item');
		});
		recCatButton.addEvent('click',function(){
			window.DJCatalog2AllowRecreation = true;
			allButtons.each(function(e){e.setAttribute('disabled', 'disabled');});
			recreateThumbnails(0,'category');
		});
		recProdButton.addEvent('click',function(){
			window.DJCatalog2AllowRecreation = true;
			allButtons.each(function(e){e.setAttribute('disabled', 'disabled');});
			recreateThumbnails(0,'producer');
		});
	}
	
	var clearButton = document.id('djc_start_deleting');
	if (clearButton) {
		clearButton.removeAttribute('disabled');
		clearButton.addEvent('click',function(){
			recButton.setAttribute('disabled', 'disabled');
			purgeThumbnails();
		});
	}
});