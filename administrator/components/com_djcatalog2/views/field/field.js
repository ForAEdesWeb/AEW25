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

var Djfieldtype = new Class(
			{
				initialize : function(fieldtype, typeselector, fieldId) {
					this.typeSelector = typeselector;
					this.fieldId = fieldId;
					this.formWrapper = document.id('fieldtypeSettings');
					this.fieldtype = fieldtype;
					this.displayForm();

					if (typeof (document.id(this.typeSelector)) !== 'undefined') {
						/*
						$(this.typeSelector).addEvent('change', function(evt) {
							this.fieldtype = $(this.typeSelector).value;
							this.displayForm();
						}.bind(this));
						*/
						/*
						document.id(this.typeSelector).chosen().addEvent('change', function(evt) {
							this.fieldtype = document.id(this.typeSelector).value;
							this.displayForm();
						}.bind(this));
						*/
						
						document.id(this.typeSelector).onchange = (function(evt) {
							this.fieldtype = document.id(this.typeSelector).value;
							this.displayForm();
							
							// f..f...f...in jQuery Chosen library
							if (typeof(jQuery) != 'undefined') {
								jQuery('#jform_filterable').trigger("liszt:updated");
								jQuery('#jform_searchable').trigger("liszt:updated");
								jQuery('#jform_sortable').trigger("liszt:updated");
							}
						}.bind(this));
					}
				},
				displayForm : function() {
					if (typeof (this.formWrapper) !== 'undefined') {
						this.ajax = new Request(
								{
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
											
											var button = el.getElements('span.button-x');
											button.addEvent('click', function(){
												row.destroy();
											});
											
											var buttonDown = el.getElements('span.button-down');
											buttonDown.addEvent('click', function(){
												row.fireEvent('moveDown', row);
											});
											
											var buttonUp = el.getElements('span.button-up');
											buttonUp.addEvent('click', function(){
												row.fireEvent('moveUp', row);
											});
										}.bind(this));
										
									}.bind(this)
								});
						this.ajax.send.delay(10, this.ajax);
					}
					
					var switch_f = document.id('jform_filterable');
					var switch_s = document.id('jform_searchable');
					var switch_o = document.id('jform_sortable');
					
					if (!this.fieldtype || this.fieldtype =='empty') {
						if (switch_f) {
							switch_f.value='0';
							switch_f.setAttribute('disabled','disabled');
						}
						if (switch_s) {
							switch_s.value='0';
							switch_s.setAttribute('disabled','disabled');
						}
						if (switch_o) {
							switch_o.value='0';
							switch_o.setAttribute('disabled','disabled');
						}
					} else {
						if (this.fieldtype != 'select' && this.fieldtype != 'checkbox' && this.fieldtype != 'radio') {
							if (switch_f) {
								switch_f.value='0';
								switch_f.setAttribute('disabled','disabled');
							}
							switch_s.removeAttribute('disabled');
						} else {
							/*
							if ($('jform_searchable')) {
								$('jform_searchable').value='0';
								$('jform_searchable').setAttribute('disabled','disabled');
							}*/
							switch_s.removeAttribute('disabled');
							switch_f.removeAttribute('disabled');
						}
						
						if (this.fieldtype == 'text' || this.fieldtype == 'select' || this.fieldtype == 'radio' || this.fieldtype == 'calendar') {
							switch_o.removeAttribute('disabled');
						} else {
							if (switch_o) {
								switch_o.value='0';
								switch_o.setAttribute('disabled','disabled');
							}
						}
						
					}
				},
				appendOption : function() {
					if (typeof (document.id('DjfieldOptions')) !== 'undefined') {
						var optionInput = document.createElement('input');
						var optionId = document.createElement('input');
						var optionPosition = document.createElement('input');
						
						var deleteButton = document.createElement('span');
						var upButton = document.createElement('span');
						var downButton = document.createElement('span');
						
						optionInput.setAttribute('name', 'fieldtype[option][]');
						optionInput.setAttribute('type', 'text');
						optionInput.setAttribute('size', '30');
						optionInput.setAttribute('class', 'input-medium');
						
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
						optionPosition.setAttribute('class', 'input-mini');
						optionPosition.setAttribute('value', parseInt(maxPos+1));
						
						optionId.setAttribute('name', 'fieldtype[id][]');
						optionId.setAttribute('type', 'hidden');
						optionId.setAttribute('value', '0');
						
						deleteButton.setAttribute('class','btn button-x');
						deleteButton.innerHTML='&nbsp;&nbsp;&minus;&nbsp;&nbsp;';
						
						downButton.setAttribute('class','btn button-down');
						downButton.innerHTML='&nbsp;&nbsp;&darr;&nbsp;&nbsp;';
						
						upButton.setAttribute('class','btn button-up');
						upButton.innerHTML='&nbsp;&nbsp;&nbsp;&uarr;&nbsp;&nbsp;&nbsp;';
						
						
						var optionInputCell = document.createElement('td');
						optionInputCell.appendChild(optionId);
						optionInputCell.appendChild(optionInput);
						
						var optionPositionCell = document.createElement('td');
						optionPositionCell.appendChild(optionPosition);
						optionPositionCell.appendChild(deleteButton);
						optionPositionCell.appendChild(downButton);
						optionPositionCell.appendChild(upButton);
						
						
						var optionRow = document.createElement('tr');
						optionRow.appendChild(optionInputCell);
						optionRow.appendChild(optionPositionCell);
						
						deleteButton.addEvent('click', function(){
							optionRow.destroy();
						});
						
						downButton.addEvent('click', function(){
							optionRow.fireEvent('moveDown', optionRow);
						});
						
						upButton.addEvent('click', function(){
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