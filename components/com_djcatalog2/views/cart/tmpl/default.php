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

<div id="djcatalog" class="djc_cart<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default') ?>">

<?php if (count($this->items) > 0){ ?>
	<div class="djc_cart djc_clearfix">
		<?php echo $this->loadTemplate('table'); ?>
	</div>
<?php } else { ?>
<p class="djc_empty_cart"><?php echo JText::_('COM_DJCATALOG2_CART_IS_EMPTY'); ?></p>
<?php } ?>

<?php if (count($this->items) > 0){ ?>
	<?php /* ?>
	<form action="<?php echo JRoute::_(DJCatalogHelperRoute::getCheckoutRoute());?>" method="post">
		<input type="submit" value="<?php echo JText::_('COM_DJCATALOG2_CONFIRM_CART');?>" class="btn button btn-success djc_checkout_btn" />
		<input type="hidden" name="option" value="com_djcatalog2" />
		<input type="hidden" name="task" value="cart.checkout" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>
	<?php */ ?>
	
	<?php if ($this->params->get('cart_query_enabled', '1') == '1') { ?>
		<form action="<?php echo JRoute::_(DJCatalogHelperRoute::getQueryRoute());?>" method="post">
			<input type="submit" value="<?php echo JText::_('COM_DJCATALOG2_PROCEED_TO_CONTACT_FORM');?>" class="btn button btn-success djc_query_btn" />
			<input type="hidden" name="option" value="com_djcatalog2" />
			<input type="hidden" name="task" value="cart.query" />
			<?php echo JHtml::_( 'form.token' ); ?>
		</form>
	<?php } ?>
<?php } ?>

<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
?>
</div>
