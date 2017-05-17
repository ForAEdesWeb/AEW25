<?php
/**
 * @version $Id: edit_legacy.php 272 2014-05-21 10:25:49Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$params = JComponentHelper::getParams('com_djcatalog2');

$net_prices = (bool)((int)$params->get('price_including_tax', 1) == 0);

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'order.cancel' || document.formvalidator.isValid(document.id('order-form'))) {
			Joomla.submitform(task, document.getElementById('order-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=order&layout=edit&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="order-form" class="form-validate"
	enctype="multipart/form-data">
	
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_DJCATALOG2_ORDER_FIELDSET_CUSTOMER'); ?></legend>
			<ul class="adminformlist">
				<?php 
				$fields = $this->form->getFieldset('customer');
				foreach ($fields as $field) { ?>
				<li><?php echo $field->label; ?> <?php echo $field->input; ?></li>
				<?php } ?>

			</ul>

		</fieldset>

	</div>
	
	<div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_DJCATALOG2_ORDER_FIELDSET_HEADER'); ?></legend>

			<ul class="adminformlist">
				<?php 
				$fields = $this->form->getFieldset('header');
				foreach ($fields as $field) { ?>
				<li><?php echo $field->label; ?> <?php echo $field->input; ?></li>
				<?php } ?>

			</ul>

		</fieldset>
	</div>
	
	<div class="clr"></div>
	
	<div class="width-100">
	    <fieldset class="adminform">
	        <legend><?php echo JText::_( 'COM_DJCATALOG2_ORDER_ITEMS_FIELDSET' ); ?></legend>
	
	        <table class="admintable ordertable">
	            <thead>
	                <tr>
	                    <th width="5%">
	                        <?php echo JText::_('COM_DJCATALOG2_ITEM_ID') ?>
	                    </th>
	                    <th>
	                        <?php echo JText::_('COM_DJCATALOG2_NAME') ?>
	                    </th>
	                    <th width="10%">
	                        <?php echo JText::_('COM_DJCATALOG2_QUANTITY') ?>
	                    </th>
	                    <th width="10%">
	                        <?php echo JText::_('COM_DJCATALOG2_PRICE') ?>
	                    </th>
	                    <th width="10%">
	                        <?php echo JText::_('COM_DJCATALOG2_TAX_RATE') ?>
	                    </th>
	                    <th width="10%">
	                        <?php echo JText::_('COM_DJCATALOG2_TAX') ?>
	                    </th>
	                    <th width="15%">
	                        <?php echo JText::_('COM_DJCATALOG2_GROSS_PRICE') ?>
	                    </th>
	                    <th width="10%"></th>
	                </tr>
	            </thead>
	            <tfoot>
	                <tr>
	                    <td colspan="8">
	                        <hr />
	                    </td>
	                    </tr>
	                <tr>
	                    <td colspan="3"><?php echo JText::_('COM_DJCATALOG2_FOOT_TOTAL') ?></td>
	                    <td>
	                        <input name="total" id="baseprice_total" value="<?php echo number_format($this->item->total, 2, '.', '') ?>" class="readonly" readonly="readonly" size="10"/>
	                    </td>
	                    <td></td>
	                    <td>
	                        <input name="tax" id="tax_total" value="<?php echo number_format($this->item->tax, 2, '.', '') ?>" class="readonly" readonly="readonly" size="10"/>
	                    </td>
	                    <td>
	                        <input name="grand_total" id="grand_total" value="<?php echo number_format($this->item->grand_total, 2, '.', '') ?>" class="readonly" readonly="readonly" size="10"/>
	                    </td>
	                    <td><span id="order_add" class="button btn"><?php echo JText::_('COM_DJCATALOG2_ADD_NEW'); ?></span></td>
	                </tr>
	            </tfoot>
	            <tbody id="order_items">
	            <?php 
	            foreach ($this->item->items as $row) { ?>
	                <tr>
	                    <td>
	                        <input name="jform[order_items][item_id][<?php echo $row->id; ?>]" type="text" value="<?php echo $row->item_id ?>" size="5" disabled="disabled" />
	                    </td>
	                    <td>
	                        <input name="jform[order_items][item_name][<?php echo $row->id; ?>]" type="text" value="<?php echo $row->item_name ?>" size="30" disabled="disabled"/>
	                        <input name="jform[order_items][id][<?php echo $row->id; ?>]" type="hidden" value="<?php echo $row->id ?>" disabled="disabled"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][quantity][<?php echo $row->id; ?>]" type="text" value="<?php echo $row->quantity ?>" size="5" class="calc quantity" disabled="disabled"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][base_cost][<?php echo $row->id; ?>]" type="text" value="<?php echo number_format($row->base_cost,2,'.','') ?>" size="10" class="calc basecost" disabled="disabled"/>
	                        <input name="jform[order_items][cost][<?php echo $row->id; ?>]" type="hidden" value="<?php echo number_format($row->cost,2,'.','') ?>" class="calc cost" disabled="disabled"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][tax_rate][<?php echo $row->id; ?>]" type="text" value="<?php echo number_format($row->tax_rate,4,'.','') ?>" size="5" class="calc taxrate" disabled="disabled"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][tax][<?php echo $row->id; ?>]" type="text" value="<?php echo number_format($row->tax,2,'.','') ?>" size="5" class="calc tax readonly" readonly="readonly" disabled="disabled"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][total][<?php echo $row->id; ?>]" type="text" value="<?php echo number_format($row->total,2,'.','') ?>" size="10" class="calc total" disabled="disabled"/>
	                    </td>
	                    <td>
	                        <span class="order_remove button btn"><?php echo JText::_('COM_DJCATALOG2_REMOVE_ITEM'); ?></span>
	                    </td>
	                </tr>
	            <?php } ?>
	            
	                <tr id="order_row_pattern" style="display:none">
	                    <td>
	                        <input name="jform[order_items][item_id][]" type="text" value="" size="5" />
	                    </td>
	                     <td>
	                        <input name="jform[order_items][item_name][]" type="text" value="" size="30" />
	                        <input name="jform[order_items][id][]" type="hidden" value="" />
	                    </td>
	                    <td>
	                        <input name="jform[order_items][quantity][]" type="text" value="0" size="5" class="calc quantity" />
	                    </td>
	                    <td>
	                        <input name="jform[order_items][base_cost][]" type="text" value="" size="10" class="calc basecost"/>
	                        <input name="jform[order_items][cost][]" type="hidden" value="" class="calc cost"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][tax_rate][]" type="text" value="0.00" size="5" class="calc taxrate"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][tax][]" type="text" value="" size="5" class="calc tax readonly" readonly="readonly"/>
	                    </td>
	                    <td>
	                        <input name="jform[order_items][total][]" type="text" value="" size="10" class="calc total"/>
	                    </td>
	                    <td>
	                        <span class="order_remove button"><?php echo JText::_('COM_DJCATALOG2_REMOVE_ITEM'); ?></span>
	                    </td>
	                </tr>
	            
	            </tbody>
	        </table>
	    </fieldset>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">
    function invoiceRecalculate(e) {
        var src = document.id(e.target);
        var parent = src.getParent('tr');
        
        var quantity, basecost, cost, tax, taxrate, total;
        
        parent.getElements('input.calc').each(function(el){
            if (el.hasClass('quantity')) {
                quantity = el;
            } else if (el.hasClass('basecost')) {
                basecost = el;
            } else if (el.hasClass('cost')) {
                cost = el;
            } else if (el.hasClass('taxrate')) {
                taxrate = el;
            } else if (el.hasClass('tax')) {
                tax = el;
            } else if (el.hasClass('total')) {
                total = el;
            } else {
                console.log(el.className);
            }
        });
        
        var r = new RegExp("\,", "i");
        var t = new RegExp("[^0-9\,\.]+", "i");
        src.setProperty('value', src.getProperty('value').replace(r, "."));
        src.setProperty('value', src.getProperty('value').replace(t, ""));
        
        if (src.hasClass('quantity')) {
            new_cost = (parseFloat(basecost.value) * parseFloat(quantity.value));
            if (isNaN(new_cost)) return;
            new_cost = new_cost.toFixed(2);
            cost.value = new_cost;

            new_tax = parseFloat(new_cost) * parseFloat(taxrate.value);
            if (isNaN(new_tax)) return;
            new_tax = new_tax.toFixed(2);
            tax.value = new_tax;
            
            new_total = parseFloat(new_cost) + parseFloat(new_tax);
            if (isNaN(new_total)) return;
            new_total = new_total.toFixed(2);
            total.value = new_total;
            
        } else if (src.hasClass('basecost')) {
            new_cost = (parseFloat(basecost.value) * parseFloat(quantity.value));
            if (isNaN(new_cost)) return;
            new_cost = new_cost.toFixed(2);
            cost.value = new_cost;
            
            new_tax = parseFloat(new_cost) * parseFloat(taxrate.value);
            if (isNaN(new_tax)) return;
            new_tax = new_tax.toFixed(2);
            tax.value = new_tax;
            
            new_total = parseFloat(new_cost) + parseFloat(new_tax);
            if (isNaN(new_total)) return;
            new_total = new_total.toFixed(2);
            total.value = new_total;
            
        } else if (src.hasClass('taxrate')) {
            new_tax = parseFloat(cost.value) * parseFloat(taxrate.value);
            if (isNaN(new_tax)) return;
            new_tax = new_tax.toFixed(2);
            tax.value = new_tax;
            
            new_total = parseFloat(cost.value) + parseFloat(new_tax);
            if (isNaN(new_total)) return;
            new_total = new_total.toFixed(2);
            total.value = new_total;
            
        } else if (src.hasClass('total')) {
            new_tax = parseFloat(total.value) * (parseFloat(taxrate.value)/(1+parseFloat(taxrate.value)));
            if (isNaN(new_tax)) return;
            new_tax = new_tax.toFixed(2);
            tax.value = new_tax;
            
            new_cost = parseFloat(total.value) - parseFloat(new_tax);
            if (isNaN(new_cost)) return;
            new_cost = new_cost.toFixed(2);
            cost.value = new_cost;
            
            new_basecost = parseFloat(new_cost) / parseFloat(quantity.value);
            if (isNaN(new_basecost)) return;
            new_basecost = new_basecost.toFixed(2);
            basecost.value = new_basecost;
        }
        
        invoiceRecalculateTotal();         
    }
    
    function invoiceRecalculateTotal() {
        var base_total = 0.0;
        var tax_total = 0.0;
        var grand_total = 0.0;

        document.id('order_items').getElements('input.calc').each(function(el){
            var toAdd = parseFloat(el.value);
            if (isNaN(toAdd)) return;
            
            if (el.hasClass('quantity')) {
                
            } else if (el.hasClass('basecost')) {
            } else if (el.hasClass('cost')) {
                base_total += toAdd;
            } else if (el.hasClass('taxrate')) {
            } else if (el.hasClass('tax')) {
                tax_total += toAdd;
            } else if (el.hasClass('total')) {
                grand_total += toAdd;
            } else {
                console.log(el.className);
            }
        });

        document.id('baseprice_total').value    = base_total.toFixed(2);
        document.id('tax_total').value          = tax_total.toFixed(2);
        document.id('grand_total').value        = grand_total.toFixed(2);
        return true;
    }
    
    function invoiceAddRow(e){
        e.preventDefault();
        
        var copy = document.id('order_row_pattern').clone().inject('order_items', 'bottom');
        copy.setStyle('display', '');
        copy.getElement('span.order_remove').addEvent('click', function(evt) {
            evt.preventDefault();
            var src = document.id(evt.target);
            var parent = src.getParent('tr');
            parent.destroy();
            invoiceRecalculateTotal();
        });
        
        var inputs = copy.getElements('input.calc');
        inputs.addEvent('change', function(e){
            invoiceRecalculate(e); 
        });
        
        inputs.addEvent('keyup', function(e){
            invoiceRecalculate(e); 
        });
        
        return false;
    }
    
    /*function invoiceFillUserData(user_id) {
        var recAjax = new Request({
        url: 'index.php?option=com_djusers&task=invoice.getUserData&tmpl=component&user_id=' + user_id,
        method: 'post',
        encoding: 'utf-8',
        onSuccess: function(response) {
            var recProgressBar = document.id('djc_progress_bar');
            var recProgressPercent = document.id('djc_progress_percent');
            
            if (response == 'error') {
            }
            else {
                var jsonObj = null;
                try {
                    jsonObj = JSON.decode(response);
                } catch(err) {
                    alert(err);
                }
                
                if (jsonObj) {
                    var fields = ['firstname', 'lastname', 'companyname', 'address', 'city', 'zipcode', 'country', 'vat_id', 'is_domestic', 'is_eu'];
                    for(var key in fields) {
                        if (fields.hasOwnProperty(key)) {
                            var field_name = fields[key];
                            var field = document.id(document.adminForm[field_name]);
                            if (field && typeof jsonObj[field_name] != 'undefined') {
                                field.value = jsonObj[field_name];
                            }
                        }
                    }
                }
            }
        }
    });
    recAjax.send();
    }*/

var body = document.id(document.body);
var table = document.id('order_items');
var inputs = table.getElements('input.calc');

window.addEvent('domready', function(){
    table.getElements('input').each(function(input){
        input.removeAttribute('disabled');
    });
    
    table.getElements('span.order_remove').each(function(el){
        el.addEvent('click', function(e) {
            e.preventDefault();
            var src = document.id(e.target);
            var parent = src.getParent('tr');
            parent.destroy();
            
            invoiceRecalculateTotal();
        });
    });
    
    document.id('order_add').addEvent('click', invoiceAddRow);
    
});

inputs.addEvent('change', function(e){
    invoiceRecalculate(e); 
});

inputs.addEvent('keyup', function(e){
    invoiceRecalculate(e); 
});
  
</script>
