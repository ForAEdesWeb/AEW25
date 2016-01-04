<?php
/**
 * @version $Id: default_addtocart.php 276 2014-05-23 09:50:49Z michal $
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

$return_url = base64_encode(JUri::getInstance()->__toString());
$button_value = ($this->params->get('cart_enabled', false)) ? JText::_('COM_DJCATALOG2_ADD_TO_CART') : JText::_('COM_DJCATALOG2_ADD_TO_QUOTE_CART');

?>
<div class="djc_addtocart">
	<form action="index.php" method="post" class="djc_form_addtocart">
		<input type="submit" class="button btn" value="<?php echo $button_value; ?>" />
		<input type="hidden" name="option" value="com_djcatalog2" />
		<input type="hidden" name="task" value="cart.add" />
		<input type="hidden" name="quantity" value="1" />
		<input type="hidden" name="return" value="<?php echo $return_url; ?>" />
		<input type="hidden" name="item_id" value="<?php echo (int)$this->item->id; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>
</div>