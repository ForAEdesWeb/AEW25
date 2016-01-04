<?php
/**
 * @version $Id: quote.php 272 2014-05-21 10:25:49Z michal $
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

defined('_JEXEC') or die('Restricted access');

$params = JComponentHelper::getParams('com_djcatalog2');

?>
<div style="width: 800px; margin: 0 auto">
<p>
<?php echo JText::sprintf('COM_DJCATALOG2_EMAIL_QUOTE_CLIENT_HEADER', $data['firstname']); ?>
</p>
<br />
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%"><?php echo JText::_('COM_DJCATALOG2_DATE'); ?>
		</td>
		<td><?php echo JHtml::_('date', $data['created_date'], 'd-m-Y'); ?>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('COM_DJCATALOG2_USER_PROFILE'); ?>
		</td>
		<td><?php if ($data['company']) { ?> <strong><?php echo $data['company']?>
		</strong><br /> <?php }?> <strong><?php echo $data['firstname'].' '.$data['lastname']; ?>
		</strong><br /> <?php echo $data['postcode'].', '.$data['city']; ?><br />
			<?php echo $data['address']; ?>
		</td>
	</tr>
	<?php if ($data['customer_note']) {?>
		<tr>
			<td><?php echo JText::_('COM_DJCATALOG2_MESSAGE'); ?>
			</td>
			<td><?php echo nl2br($data['customer_note']); ?>
			</td>
		</tr>
	<?php } ?>
</table>
<br /><br />
<table width="100%" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th width="30%"><?php echo JText::_('COM_DJCATALOG2_CART_NAME'); ?>
			</th>
			<th align="center"><?php echo JText::_('COM_DJCATALOG2_QUANTITY'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($data['items'] as $item){
		?>
		<tr>
			<td><?php 
			echo '('.$item['item_id'].') '.$item['item_name'];
			?>
			</td>
			<td align="center"><?php echo (int)$item['quantity']; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<br />
<p>
<?php echo JText::_('COM_DJCATALOG2_EMAIL_QUOTE_CLIENT_FOOTER'); ?>
</p>
</div>