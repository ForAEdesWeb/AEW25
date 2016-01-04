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

this.Djfieldtype = new Class({
	initialize : function(fieldtype, typeselector, fieldId) {
		this.typeSelector = typeselector;
		this.fieldId = fieldId;
		this.formWrapper = document.id('fieldtypeSettings');
		this.fieldtype = fieldtype;
		this.displayForm();

		if (typeof (document.id(this.typeSelector)) !== 'undefined') {
			document.id(this.typeSelector).addEvent('change', function(evt) {
				this.fieldtype = document.id(this.typeSelector).value;
				this.displayForm();
			}.bind(this));
		}
	},
	displayForm : function() {
			if (typeof (this.formWrapper) !== 'undefined') {
				this.ajax = new Request({
							url : 'index.php?option=com_djcatalog2&view=field&layout=fielddata&format=raw&fieldtype='
									+ this.fieldtype
									+ '&fieldId='
									+ this.fieldId
									+ '&suffix='
									+ this.typeSelector,
							onSuccess : function(resp) {
								this.formWrapper.innerHTML = resp;
								
								var rows = this.formWrapper.getElements('tr');
								
								rows.each(function(el,ind){
									var row = el;
									
									row.addEvent('moveDown',this.moveDown.bind(this).pass(row));
									row.addEvent('moveUp',this.moveUp.bind(this).pass(row));
									
									var button = el.getElements('.button2-left span.button-x');
									button.addEvent('click', function(){
										row.destroy();
									});
									
									var buttonDown = el.getElements('.button2-left span.button-down');
									buttonDown.addEvent('click', function(){
										row.fireEvent('moveDown', row);
									});
									
									var buttonUp = el.getElements('.button2-left span.button-up');
									buttonUp.addEvent('click', function(){
										row.fireEvent('moveUp', row);
									});
								}.bind(this));
								
							}.bind(this)
						});
				this.ajax.send.delay(10, this.ajax);
			}
			if (!this.fieldtype || this.fieldtype =='empty') {
				if (document.id('jform_filterable')) {
					document.id('jform_filterable').value='0';
					document.id('jform_filterable').setAttribute('disabled','disabled');
				}
				if (document.id('jform_searchable')) {
					document.id('jform_searchable').value='0';
					document.id('jform_searchable').setAttribute('disabled','disabled');
				}
				if (document.id('jform_sortable')) {
					document.id('jform_sortable').value='0';
					document.id('jform_sortable').setAttribute('disabled','disabled');
				}
			} else {
				if (this.fieldtype == 'calendar') {
					if (document.id('jform_filterable')) {
						document.id('jform_filterable').value='0';
						document.id('jform_filterable').setAttribute('disabled','disabled');
					}
					if (document.id('jform_searchable')) {
						document.id('jform_searchable').value='0';
						document.id('jform_searchable').setAttribute('disabled','disabled');
					}
					document.id('jform_sortable').removeAttribute('disabled');
				}
				else if (this.fieldtype != 'select' && this.fieldtype != 'checkbox' && this.fieldtype != 'radio') {
					if (document.id('jform_filterable')) {
						document.id('jform_filterable').value='0';
						document.id('jform_filterable').setAttribute('disabled','disabled');
					}
					document.id('jform_searchable').removeAttribute('disabled');
					
					if (this.fieldtype == 'text') {
						document.id('jform_sortable').removeAttribute('disabled');
					} else {
						if (document.id('jform_sortable')) {
							document.id('jform_sortable').value='0';
							document.id('jform_sortable').setAttribute('disabled','disabled');
						}
					}
					
				} else {
					/*
					if ($('jform_searchable')) {
						$('jform_searchable').value='0';
						$('jform_searchable').setAttribute('disabled','disabled');
					}*/
					document.id('jform_searchable').removeAttribute('disabled');
					document.id('jform_filterable').removeAttribute('disabled');
					
					if (this.fieldtype != 'checkbox') {
						document.id('jform_sortable').removeAttribute('disabled');
					} else {
						if (document.id('jform_sortable')) {
							document.id('jform_sortable').value='0';
							document.id('jform_sortable').setAttribute('disabled','disabled');
						}
					}
					
				}
		}
	},
		appendOption : function() {
			if (typeof (document.id('DjfieldOptions')) !== 'undefined') {
				var optionInput = new Element('input');
				var optionId = new Element('input');
				var optionPosition = new Element('input');
				
				var deleteButton = new Element('div');
				var upButton = new Element('div');
				var downButton = new Element('div');
				
				optionInput.setAttribute('name', 'fieldtype[option][]');
				optionInput.setAttribute('type', 'text');
				optionInput.setAttribute('size', '30');
				optionInput.setAttribute('class', 'inputbox required');
				
				var inputs = this.formWrapper.getElements('input');
				var maxPos = 0;
				inputs.each(function(el,ind) {
					if (el.name == 'fieldtype[position][]') {
						if (maxPos < parseInt(el.value)) {
							maxPos = parseInt(el.value);
						}
					}
				});
				
				optionPosition.setAttribute('name', 'fieldtype[position][]');
				optionPosition.setAttribute('type', 'text');
				optionPosition.setAttribute('size', '4');
				optionPosition.setAttribute('class', 'inputbox');
				optionPosition.setAttribute('value', parseInt(maxPos+1));
				
				optionId.setAttribute('name', 'fieldtype[id][]');
				optionId.setAttribute('type', 'hidden');
				optionId.setAttribute('value', '0');
				
				deleteButton.setAttribute('class','button2-left');
				deleteButton.innerHTML='<div class="blank"><span class="button-x">&nbsp;&nbsp;&minus;&nbsp;&nbsp;</span></div>';
				
				downButton.setAttribute('class','button2-left');
				downButton.innerHTML='<div class="blank"><span class="button-down">&nbsp;&nbsp;&darr;&nbsp;&nbsp;</span></div>';
				
				upButton.setAttribute('class','button2-left');
				upButton.innerHTML='<div class="blank"><span class="button-up">&nbsp;&nbsp;&nbsp;&uarr;&nbsp;&nbsp;&nbsp;</span></div>';
				
				
				var optionInputCell = new Element('td');
				optionInputCell.appendChild(optionId);
				optionInputCell.appendChild(optionInput);
				
				var optionPositionCell = new Element('td');
				optionPositionCell.appendChild(optionPosition);
				optionPositionCell.appendChild(deleteButton);
				optionPositionCell.appendChild(downButton);
				optionPositionCell.appendChild(upButton);
				
				
				var optionRow = new Element('tr');
				optionRow.appendChild(optionInputCell);
				optionRow.appendChild(optionPositionCell);
				var deleteSpan = deleteButton.getElements('span.button-x');
				deleteSpan.addEvent('click', function(){
					optionRow.destroy();
				});
				
				var downSpan = downButton.getElements('span.button-down');
				downSpan.addEvent('click', function(){
					optionRow.fireEvent('moveDown', optionRow);
				});
				
				var upSpan = upButton.getElements('span.button-up');
				upSpan.addEvent('click', function(){
					optionRow.fireEvent('moveUp', optionRow);
				});
				
				optionRow.addEvent('moveDown',this.moveDown.bind(this).pass(optionRow));
				optionRow.addEvent('moveUp',this.moveUp.bind(this).pass(optionRow));
									
				document.id('DjfieldOptions').appendChild(optionRow);
			}
		},
		moveDown:function(row) {
			var tbody = document.id('DjfieldOptions');
			var rows = this.formWrapper.getElements('tbody tr');
			var count = rows.length;
			rows.each(function(el,ind){
				if (row.match(el) && ind < count - 1) {
					this.switchRows(row, rows[ind+1]);
				}
			}.bind(this));
		},
		moveUp:function(row) {
			var tbody = document.id('DjfieldOptions');
			var rows = this.formWrapper.getElements('tbody tr');
			var count = rows.length;
			rows.each(function(el,ind){
				if (row.match(el) && ind > 0) {
					this.switchRows(row, rows[ind-1]);
				}
			}.bind(this));
		},
		switchRows : function(row1, row2) {
			var inputs1 = row1.getElements('input');
			var inputs2 = row2.getElements('input');
			if (inputs1.length == inputs2.length) {
				for (var i=0; i < inputs1.length; i++) {
					if (inputs1[i].name != 'fieldtype[position][]'){
						var temp = inputs1[i].value;
						inputs1[i].value = inputs2[i].value;
						inputs2[i].value = temp;
					}
				}
			}
		}
	});
