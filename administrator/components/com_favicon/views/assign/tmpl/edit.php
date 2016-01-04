<?php
/**
 * @copyright	Copyright (C) 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
$doc = &JFactory::getDocument();
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<form action="<?php JRoute::_('index.php?option=com_favicon'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::sprintf('COM_FAVICON_ASSIGN', $this->id); ?></legend>
                <label><?php echo JText::_('COM_FAVICON_ICON_ID').' '.$this->id;?></label>
                <img src="<?php echo JURI::base();?>index.php?option=com_favicon&task=favicon.image&id=<?php echo $this->id;?>key=<?php echo $this->iconkey;?>"/>
                <?php echo $this->form->getLabel('menus'); ?>
		<div class="clr"></div>
		<?php echo $this->form->getInput('menus'); ?>
		<input type="hidden" name="task" value="" />
		<?php echo $this->form->getInput('iconid'); ?>
		<?php echo JHtml::_('form.token'); ?>
                <input type="submit" value="<?php echo JText::_('COM_FAVICON_CLEAR_ALL');?>" onclick="document.id('jform_menus').getElements('option').each(function(el){ el.selected = false; });return false;"/>
                <input type="submit" value="<?php echo JText::_('COM_FAVICON_ASSIGN');?>" onclick="Joomla.submitbutton('assign.save');return false;" />
                <input type="submit" value="<?php echo JText::_('COM_FAVICON_CANCEL');?>" onclick="window.parent.location.reload(true);return false;" />
	</fieldset>
</form>
