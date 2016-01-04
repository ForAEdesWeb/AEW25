<?php
/**
 * @version $Id: default.php 132 2013-05-20 07:12:44Z michal $
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

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('submit-item-form'));
	}
</script>

<div id="djcatalog" class="djc_mylist<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default') ?>">

<?php if ($user->authorise('core.create', 'com_djcatalog2')) { ?>
<div class="formelm-buttons djc_form_toolbar">
	<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=itemform.add'); ?>" method="post" name="submit-item-form" id="submit-item-form">
		<button type="button" onclick="Joomla.submitbutton('itemform.add')" class="button btn">
			<?php echo JText::_('COM_DJCATALOG2_ADD') ?>
		</button>
		<input type="hidden" name="task" value="" />
	</form>
	</div>
<?php } ?>

<?php if (count($this->items) > 0){ ?>
<div class="djc_order djc_clearfix">
	<?php echo $this->loadTemplate('order'); ?>
</div>
<?php } ?>

<?php if (count($this->items) > 0){ ?>
	<div class="djc_items djc_clearfix">
		<?php echo $this->loadTemplate('table'); ?>
	</div>
<?php } ?>

<?php if ($this->pagination->total > 0) { ?>
<div class="djc_pagination pagination djc_clearfix">
<?php
	echo $this->pagination->getPagesLinks();
?>
</div>
<?php } ?>

<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
?>
</div>
