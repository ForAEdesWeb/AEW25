<?php
/**
 * @version $Id: default_table.php 272 2014-05-21 10:25:49Z michal $
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

defined ('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();

?>

<table width="100%" cellpadding="0" cellspacing="0"
	class="djc_cart_table djc_order_items_table jlist-table category table-striped table"
	id="djc_order_items_table">
	<thead>
		<tr>
			<th class="djc_thead djc_th_title"><?php echo JText::_('COM_DJCATALOG2_CART_NAME'); ?>
			</th>
			<th class="djc_thead djc_th_qty"><?php echo JText::_('COM_DJCATALOG2_QUANTITY'); ?>
			</th>
			<th class="djc_thead djc_th_price djc_th_price_net"><?php echo JText::_('COM_DJCATALOG2_NET_VALUE'); ?>
			</th>
			<th class="djc_thead djc_th_price djc_th_price_tax"><?php echo JText::_('COM_DJCATALOG2_TAX'); ?>
			</th>
			<th class="djc_thead djc_th_price djc_th_price_gross"><?php echo JText::_('COM_DJCATALOG2_TOTAL_VALUE'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2" class="djc_ft_total_label"><?php echo JText::_('COM_DJCATALOG2_CART_FOOTER_TOTAL'); ?>
			</td>
			<td><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->total, $this->params)?>
			</td>
			<td><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->tax, $this->params)?>
			</td>
			<td><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->grand_total, $this->params)?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php
		$k = 1;
		foreach($this->items as $item){
		$k = 1 - $k;
		?>
		<tr class="cat-list-row<?php echo $k;?> djc_row<?php echo $k;?>">
			<td class="djc_td_title"><?php 
			echo $item->item_name;
			?>
			</td>
			<td class="djc_td_qty" nowrap="nowrap"><?php echo (int)$item->quantity; ?>
			</td>
			<td class="djc_td_price djc_td_price_net" nowrap="nowrap"><?php echo DJCatalog2HtmlHelper::formatPrice($item->cost, $this->params, false)?>
			</td>
			<td class="djc_td_price djc_td_price_tax" nowrap="nowrap"><?php echo DJCatalog2HtmlHelper::formatPrice($item->tax, $this->params, false)?>
			</td>
			<td class="djc_td_price djc_td_price_gross" nowrap="nowrap"><?php echo DJCatalog2HtmlHelper::formatPrice($item->total, $this->params, false)?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
