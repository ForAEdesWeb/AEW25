<?php
/**
 * @version $Id: default_legacy.php 272 2014-05-21 10:25:49Z michal $
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

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';

$db = JFactory::getDbo();

$params = JComponentHelper::getParams('com_djcatalog2');

$net_prices = (bool)((int)$params->get('price_including_tax', 1) == 0);

$has_group = (bool)$this->state->get('filter.customergroup', false);

?>
<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=prices');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<label for="filter_customergroup"><?php echo JText::_('COM_DJCATALOG2_FILTER_CUSTOMERGROUP')?></label>
	        <?php 
	        $groups = array();
	        $groups[] = JHtml::_('select.option', '0', '- '.JText::_('COM_DJCATALOG2_GLOBAL_PRICES').' -');
	        $db->setQuery('select id, name from #__djc2_customer_groups order by name asc');
	        $db_groups = $db->loadObjectList();
	
	        foreach ($db_groups as $group) {
	            $groups[] = JHtml::_('select.option', $group->id, $group->name);
	        }
	        ?>
	        <select id="filter_cusomtergroup" name="filter_customergroup" class="inputbox" onchange="this.form.submit()">
	            <?php 
	            echo JHtml::_('select.options', $groups, 'value', 'text', ($this->state->get('filter.customergroup')), true);?>
	        </select>
		
			<?php echo JHTML::_('select.genericlist', $this->categories, 'filter_category', 'class="inputbox" onchange="this.form.submit()"', 'value', 'text', $this->state->get('filter.category')); ?>
			<?php 
				$producers_first_option = new stdClass();
				$producers_first_option->id = '';
				$producers_first_option->name = '- '.JText::_('COM_DJCATALOG2_SELECT_PRODUCER').' -';
				$producers_first_option->published = null;
				$producers = count($this->producers) ? array_merge(array($producers_first_option),$this->producers) : array($producers_first_option);
				echo JHTML::_('select.genericlist', $producers, 'filter_producer', 'class="inputbox" onchange="this.form.submit()"', 'id', 'name', $this->state->get('filter.producer'));
			?>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
                <th>
                    <?php echo JHtml::_('grid.sort', 'COM_DJCATALOG2_NAME', 'a.name', $listDirn, $listOrder); ?>
                </th>
                <th width="15%"  class="title">
                    <?php echo JHTML::_('grid.sort',  'COM_DJCATALOG2_CATEGORY', 'category_name', $listDirn, $listOrder ); ?>
                </th>
                
                <?php if ($has_group) { ?>
                	<th width="20%" class="center">
	                    <?php echo JText::_('COM_DJCATALOG2_BASE_PRODUCT_PRICE'); ?>
	                    <p class="smallsub"><small>[<?php echo JText::_(($net_prices) ? 'COM_DJCATALOG2_PRICE_EXCL_TAX' : 'COM_DJCATALOG2_PRICE_INCL_TAX'); ?>]</small></p>
	                </th>
                <?php } ?>
                
                <th width="20%" class="center">
                    <?php echo JText::_('COM_DJCATALOG2_PRICE_EXCL_TAX'); ?>
                </th>
                <th width="20%" class="center">
                    <?php echo JText::_('COM_DJCATALOG2_PRICE_INCL_TAX'); ?>
                </th>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo ($has_group) ? '6' : '5'; ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
                $product_price = null;
                if ($has_group && $item->group_price > 0) {
					$product_price = $item->group_price;
				} else if (!$has_group) {
					$product_price = $item->price ? $item->price : 0.00;
				}
                ?>
			<tr class="row<?php echo $i % 2; ?>">
                <td>
                    <?php echo $this->escape($item->name); ?>
                    <p class="smallsub"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
                </td>
                <td>
                    <?php echo $this->escape($item->category_name); ?>
                </td>
                <?php if ($has_group) { ?>
                	<td class="center">
	                	<input class="input input-mini inputbox readonly" type="text" readonly="readonly" value="<?php echo $item->price > 0.0 ? $item->price : 0.00; ?>" />
	                </td>
                <?php } ?>
                
                <?php if ($net_prices) { ?>
                	<td class="center">
	                    <input data-taxrate="<?php echo floatval($item->tax_rate); ?>" data-type="net" data-target="djc_tax_price_<?php echo $item->id; ?>" class="input input-mini inputbox djc_price" type="text" value="<?php echo $product_price; ?>" name="djc_prices[<?php echo $item->id ?>][new]" />
	                    <input type="hidden" name="djc_prices[<?php echo $item->id ?>][old]" value="<?php echo $product_price; ?>" />
	                </td>
	                <td class="center">
	                	<input id="djc_tax_price_<?php echo $item->id; ?>" class="input input-mini inputbox djc_tax_price readonly" type="text" readonly="readonly" />
	                </td>
                <?php } else { ?>
                	<td class="center">
                		<input id="djc_tax_price_<?php echo $item->id; ?>" class="input input-mini inputbox djc_tax_price readonly" type="text" readonly="readonly" />
	                </td>
	                <td class="center">
	                	<input data-taxrate="<?php echo floatval($item->tax_rate); ?>" data-type="gross" data-target="djc_tax_price_<?php echo $item->id; ?>" class="input input-mini inputbox djc_price" type="text" value="<?php echo $product_price; ?>" name="djc_prices[<?php echo $item->id ?>][new]" />
	                    <input type="hidden" name="djc_prices[<?php echo $item->id ?>][old]" value="<?php echo $product_price; ?>" />
	                </td>
                <?php } ?>
                <td class="center">
                    <?php echo (int) $item->id; ?>
                </td>
            </tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<script type="text/javascript">
	function djValidatePrice(priceInput) {
		//var r = new RegExp("\,", "i");
		//var t = new RegExp("[^0-9\,\.]+", "i");
		//priceInput.setProperty('value', priceInput.getProperty('value').replace(r, "."));
		//priceInput.setProperty('value', priceInput.getProperty('value').replace(t, ""));
	
	
		var price = priceInput.getProperty('value');
		
		// valid format
		var valid_price = new RegExp(/^(\d+|\d+\.\d+)$/);
		
		// comma instead of do
		var wrong_decimal = new RegExp(/\,/g);
		
		// non allowed characters
		var restricted = new RegExp(/[^\d+\.]/g);
		
		// replace comma with a dot
		price = price.replace(wrong_decimal, ".");
		
		if (valid_price.test(price) == false) {
			// remove illegal chars
			price = price.replace(restricted, '');
		}
		
		if (valid_price.test(price) == false) {
			// too many dots in here
			parts = price.split('.');
			if (parts.length > 2 ) {
				price = parts[0] + '.' + parts[1];
			}
		}
		
		priceInput.setProperty('value', price);

		var inputType = priceInput.getProperty('data-type');
		var taxRate = priceInput.getProperty('data-taxrate');

		if (inputType == 'gross') {
			djPriceFromGross(document.id(priceInput.getProperty('data-target')), price, taxRate);
		} else if (inputType == 'net') {
			djPriceFromNet(document.id(priceInput.getProperty('data-target')), price, taxRate);
		}
		
	}

	function djPriceFromGross(element, price, taxrate) {
		price = parseFloat(price);
		taxrate = parseFloat(taxrate);
		if (!price || !(taxrate >= 0)) {
			element.value = '';
			return;
		}

		var netPrice = (price * 100) / (100 + taxrate);
		element.value = netPrice.toFixed(2);
	}

	function djPriceFromNet(element, price, taxrate) {
		price = parseFloat(price);
		taxrate = parseFloat(taxrate);

		if (!price || !(taxrate >= 0)) {
			element.value = '';
			return;
		}

		var grossPrice = price * ((100 + taxrate)/100) ;
		element.value = grossPrice.toFixed(2);
	}
	
	
    window.addEvent('domready', function(){
        var price_fields = document.id(document.body).getElements('input.djc_price');
        if (price_fields.length >0) {
            price_fields.each(function(element){
                element.addEvents({
                    'keyup' : function(e){djValidatePrice(element);},
                    'change' : function(e){djValidatePrice(element);},
                    'click' : function(e){djValidatePrice(element);}
                });
                element.fireEvent('change', element);
            });
        }
    });
</script>