<?php
/**
 * @version $Id: default.php 272 2014-05-21 10:25:49Z michal $
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

<?php //if ($this->params->get( 'show_page_heading', 1)) { ?>
<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ) ?>">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php //} ?>

<div id="djcatalog" class="djc_order<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default') ?>">

<div class="djc_attributes">
	<h2><?php echo JText::_('COM_DJCATALOG2_ORDER_DETAILS'); ?></h2>
	
	<table width="100%" cellpadding="0" cellspacing="0"
		class="djc_order_details_table jlist-table table-condensed table"
		id="djc_order_details_table">
		<tr class="djc_attribute">
			<td class="djc_label"><?php echo JText::_('COM_DJCATALOG2_ORDER_NUMBER'); ?>
			</td>
			<td class="djc_value"><?php echo str_pad($this->item->order_number, 5, '0', STR_PAD_LEFT); ?>
			</td>
		</tr>
		<tr class="djc_attribute">
			<td class="djc_label"><?php echo JText::_('COM_DJCATALOG2_ORDER_DATE'); ?>
			</td>
			<td class="djc_value"><?php echo JHtml::_('date', $this->item->created_date, 'd-m-Y'); ?>
			</td>
		</tr>
		<tr class="djc_attribute">
			<td class="djc_label"><?php echo JText::_('COM_DJCATALOG2_ORDER_STATUS'); ?>
			</td>
			<td class="djc_value"><?php echo JText::_('COM_DJCATALOG2_ORDER_STATUS_'.$this->item->status); ?>
			</td>
		</tr>
		<tr class="djc_attribute">
			<td class="djc_label"><?php echo JText::_('COM_DJCATALOG2_ORDER_FINAL_PRICE'); ?>
			</td>
			<td class="djc_value"><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->grand_total, $this->params); ?>
			</td>
		</tr>
		<tr class="djc_attribute">
			<td class="djc_label"><?php echo JText::_('COM_DJCATALOG2_ORDER_BUYER'); ?>
			</td>
			<td class="djc_value">
			<?php if ($this->item->company) { ?>
				<strong><?php echo $this->item->company?></strong><br />
			<?php }?>
			<strong><?php echo $this->item->firstname.' '.$this->item->lastname; ?></strong><br />
			<?php echo $this->item->postcode.', '.$this->item->city; ?><br />
			<?php echo $this->item->address; ?>
			</td>
		</tr>
		<?php if ($this->item->customer_note) {?>
			<tr class="djc_attribute">
				<td class="djc_label"><?php echo JText::_('COM_DJCATALOG2_ORDER_NOTES'); ?>
				</td>
				<td class="djc_value"><?php echo nl2br($this->item->customer_note); ?>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>

<h2><?php echo JText::_('COM_DJCATALOG2_ORDER_ITEMS'); ?></h2>

<div class="djc_order_items djc_clearfix">
	<?php echo $this->loadTemplate('table'); ?>
</div>

<a class="button btn djc_back_to_orders_btn" href="<?php echo JRoute::_(DJCatalogHelperRoute::getOrdersRoute());?>"><span><?php echo JText::_('COM_DJCATALOG2_BACK_TO_ORDERS'); ?></span></a>

<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
?>
</div>
