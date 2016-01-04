<?php
/**
 * @version $Id: default.php 276 2014-05-23 09:50:49Z michal $
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

$basket = Djcatalog2HelperCart::getInstance(true);

$items = $basket->getItems();
$total = $basket->getTotal();

$cparams = Djcatalog2Helper::getParams();

?>

<div class="mod_djc2cart">
	<?php if (empty($items)) { ?>
		<p class="mod_djc2cart_is_empty"><?php echo JText::_('MOD_DJC2CART_EMPTY_CART');?></p>
	<?php } ?>
	
	<div class="mod_djc2_cart_contents" style="display: <?php echo (empty($items)) ? 'none' : 'block'; ?>;">
		<p class="mod_djc2cart_info">
			<?php echo JText::sprintf('MOD_DJC2CART_YOU_HAVE_ITEMS', count($items)); ?>
		</p>
		<p class="mod_djc2cart_button">
			<a class="btn button" href="<?php echo JRoute::_(DJCatalogHelperRoute::getCartRoute()); ?>"><span><?php echo JText::_('MOD_DJC2CART_SHOW_CART');?></span></a>
		</p>
	</div>
</div>
