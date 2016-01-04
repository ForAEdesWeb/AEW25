<?php
/**
 * @version $Id: default.php 278 2014-05-27 12:51:04Z michal $
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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$user = JFactory::getUser();
?>

<?php //if ($this->params->get( 'show_page_heading', 1)) { ?>
<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ) ?>">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php //} ?>

<div id="djcatalog" class="djc_checkout<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default') ?>">

<?php if (!empty($this->user_profile) && !empty($this->user_profile->id) && $this->user_valid) { ?>
<div class="djc_checkout_header">
	<h2><?php echo JText::_('COM_DJCATALOG2_USER_PROFILE'); ?></h2>
	<p>
	<?php if ($this->user_profile->company) { ?>
		<strong><?php echo $this->user_profile->company?></strong><br />
	<?php }?>
	<strong><?php echo $this->user_profile->firstname.' '.$this->user_profile->lastname; ?></strong><br />
	<?php echo $this->user_profile->postcode.', '.$this->user_profile->city; ?><br />
	<?php echo $this->user_profile->address; ?>
	</p>
</div>
<?php } ?>

<?php if (count($this->items) > 0) { ?>
	<h2><?php echo JText::_('COM_DJCATALOG2_CART_HEADING'); ?></h2>
	<div class="djc_cart djc_cart_checkout djc_clearfix">
		<?php echo $this->loadTemplate('table'); ?>
	</div>
<?php }  ?>

<div class="djc_checkout_form">
<form action="<?php echo JRoute::_(DJCatalogHelperRoute::getCartRoute());?>" method="post" class="form-validate">
	<fieldset class="djc_checkout_notes">
	
		<?php if (empty($this->user_profile) || empty($this->user_profile->id) || $this->user_valid == false) { ?>
			<h2><?php echo JText::_('COM_DJCATALOG2_USER_PROFILE'); ?></h2>
			<?php if ($user->guest) { ?>
				<p class="djc_login_link">
				<?php 
				$return_url = base64_encode(DJCatalogHelperRoute::getQueryRoute());
				$login_url = JRoute::_('index.php?option=com_users&view=login&return='.$return_url);
				echo JText::sprintf('COM_DJCATALOG2_CLICK_TO_LOGIN', $login_url);
				?>
				</p>
			<?php } ?>
			<?php 
			$fields = $this->form->getFieldset(); 
			foreach ($fields as $field) { ?>
				<?php if ($field->fieldname == 'customer_note') { ?>
				<h2><?php echo JText::_('COM_DJCATALOG2_MESSAGE'); ?></h2>
				<div class="control-group">
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php } else {?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		
		<?php /*?>
		<div class="control-group">
			<div class="control-label">
				<label for="customer_note"><?php echo JText::_('COM_DJCATALOG2_QUERY_NOTES');?></label>
			</div>
			<div class="controls">
				<textarea id="customer_note" rows="5" cols="40" name="customer_note" class="inputbox input input-large"></textarea>
			</div>
		</div>
		<?php */ ?>
		
		<div class="control-group">
			<div class="controls">
				<a class="button btn djc_back_to_cart_btn btn-primary" href="<?php echo JRoute::_(DJCatalogHelperRoute::getCartRoute());?>"><span><?php echo JText::_('COM_DJCATALOG2_BACK_TO_CART'); ?></span></a>
				<input type="submit" value="<?php echo JText::_('COM_DJCATALOG2_CONFIRM_QUERY');?>" class="btn btn-success button validate" />
			</div>
		</div>
		
	</fieldset>
	
	
	<input type="hidden" name="option" value="com_djcatalog2" />
	<input type="hidden" name="task" value="cart.query_confirm" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
</div>
<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
?>
</div>
