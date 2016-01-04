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
$return_url = base64_encode(JUri::getInstance()->__toString());

?>
<table width="100%" cellpadding="0" cellspacing="0" class="djc_cart_table jlist-table category table-condensed table" id="djc_cart_checkout_table">
	<thead>
		<tr>
			<th class="djc_thead djc_th_title" colspan="2">
				<?php echo JText::_('COM_DJCATALOG2_CART_NAME'); ?>
	        </th>
	        <th class="djc_thead djc_th_qty">
				<?php echo JText::_('COM_DJCATALOG2_QUANTITY'); ?>
	        </th>
	        <th class="djc_thead djc_th_price djc_th_price_net">
				<?php echo JText::_('COM_DJCATALOG2_NET_VALUE'); ?>
	        </th>
	        <th class="djc_thead djc_th_price djc_th_price_tax">
				<?php echo JText::_('COM_DJCATALOG2_TAX'); ?>
	        </th>
	        <th class="djc_thead djc_th_price djc_th_price_gross">
				<?php echo JText::_('COM_DJCATALOG2_TOTAL_VALUE'); ?>
	        </th>
	    </tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3" class="djc_ft_total_label">
				<?php echo JText::_('COM_DJCATALOG2_CART_FOOTER_TOTAL'); ?>
			</td>
			<td>
				<?php echo DJCatalog2HtmlHelper::formatPrice($this->total['net'], $this->params)?>
			</td>
			<td>
				<?php echo DJCatalog2HtmlHelper::formatPrice($this->total['tax'], $this->params)?>
			</td>
			<td>
				<?php echo DJCatalog2HtmlHelper::formatPrice($this->total['gross'], $this->params)?>
			</td>
		</tr>
	</tfoot>
    <tbody>
        <?php
	$k = 1;
	foreach($this->items as $item){
		$k = 1 - $k;
		?>
        <tr class="cat-list-row<?php echo $k;?> djc_row<?php echo $k; if ($item->featured == 1) echo ' featured_item'; ?>">
            <td class="djc_td_image">
                <?php if ($item->item_image) { ?>
	        	<div class="djc_image_in">
					<img class="img-polaroid" alt="<?php echo $item->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($item->image_fullpath,'small'); ?>"/>
	        	</div>
			<?php } ?>
            </td>
			<td class="djc_td_title">
           		<?php 
		        	echo $item->name;
		        ?>
            </td>
            <td class="djc_td_update_qty" nowrap="nowrap">
            	<?php echo (int)$item->_quantity; ?>
            </td>
            <td class="djc_td_price djc_td_price_net" nowrap="nowrap">
            	<?php echo DJCatalog2HtmlHelper::formatPrice($item->_prices['total']['net'], $this->params, false)?>
            </td>
            <td class="djc_td_price djc_td_price_tax" nowrap="nowrap">
            	<?php echo DJCatalog2HtmlHelper::formatPrice($item->_prices['total']['tax'], $this->params, false)?>
            </td>
            <td class="djc_td_price djc_td_price_gross" nowrap="nowrap">
            	<?php echo DJCatalog2HtmlHelper::formatPrice($item->_prices['total']['gross'], $this->params, false)?>
            </td>
        </tr>
	<?php } ?>
	</tbody>
</table>