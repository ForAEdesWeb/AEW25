<?php
/**
 * @version $Id: djitem.php 270 2014-04-10 06:40:48Z michal $
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

defined('_JEXEC') or die();
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldDjitem extends JFormField {
	
	protected $type = 'Djitem';
	
	
	protected function getInput(){
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			return $this->getInputJ2();
		}
		return $this->getInputJ3();
	}
	
	protected function getInputJ3()
	{
		$allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear		= ((string) $this->element['clear'] != 'false') ? true : false;
	
		// Load language
		JFactory::getLanguage()->load('com_content', JPATH_ADMINISTRATOR);
	
		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');
	
		// Build the script.
		$script = array();
	
		// Select button script
		$script[] = '	function jSelectDJCatalog2Item_'.$this->id.'(id, title, catid) {';
		$script[] = '		document.getElementById("'.$this->id.'_id").value = id;';
		$script[] = '		document.getElementById("'.$this->id.'_name").value = title;';
	
		if ($allowEdit)
		{
			$script[] = '		jQuery("#'.$this->id.'_edit").removeClass("hidden");';
		}
	
		if ($allowClear)
		{
			$script[] = '		jQuery("#'.$this->id.'_clear").removeClass("hidden");';
		}
	
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';
	
		// Clear button script
		static $scriptClear;
	
		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;
	
			$script[] = '	function jClearDJCatalog2Item(id) {';
			$script[] = '		document.getElementById(id + "_id").value = "";';
			$script[] = '		document.getElementById(id + "_name").value = "'.htmlspecialchars(JText::_('COM_DJCATALOG2_SELECT_ITEM', true), ENT_COMPAT, 'UTF-8').'";';
			$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '		if (document.getElementById(id + "_edit")) {';
			$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '		}';
			$script[] = '		return false;';
			$script[] = '	}';
		}
	
		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
	
		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_djcatalog2&amp;view=items&amp;layout=modal&amp;tmpl=component&amp;function=jSelectDJCatalog2Item_'.$this->id;
	
		if ((int) $this->value > 0)
		{
			$db	= JFactory::getDbo();
			$query = $db->getQuery(true)
			->select($db->quoteName('name'))
			->from($db->quoteName('#__djc2_items'))
			->where($db->quoteName('id') . ' = ' . (int) $this->value);
			$db->setQuery($query);
	
			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage());
			}
		}
	
		if (empty($title))
		{
			$title = JText::_('COM_DJCATALOG2_SELECT_ITEM');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
	
		// The active article id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}
	
		// The current article display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '<a class="modal btn hasTooltip" title="'.JHtml::tooltipText('COM_DJCATALOG2_CHANGE_ITEM').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> '.JText::_('JSELECT').'</a>';
		
		/*
		// Edit article button
		if ($allowEdit)
		{
			$html[] = '<a class="btn hasTooltip'.($value ? '' : ' hidden').'" href="index.php?option=com_djcatalog2&layout=modal&tmpl=component&task=item.edit&id=' . $value. '" target="_blank" title="'.JHtml::tooltipText('COM_DJCATALOG2_EDIT_ITEM_TOOLTIP').'" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
		}
		*/
	
		// Clear article button
		if ($allowClear)
		{
			$html[] = '<button id="'.$this->id.'_clear" class="btn'.($value ? '' : ' hidden').'" onclick="return jClearDJCatalog2Item(\''.$this->id.'\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}
	
		$html[] = '</span>';
	
		// class='required' for client side validation
		$class = '';
		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}
	
		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';
	
		return implode("\n", $html);
	}
	
	protected function getInputJ2()
	{
		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');
	
		// Build the script.
		$script = array();
		$script[] = '	function jSelectDJCatalog2Item_'.$this->id.'(id, title, catid) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';
	
		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
	
	
		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_djcatalog2&amp;view=items&amp;layout=modal&amp;tmpl=component&amp;function=jSelectDJCatalog2Item_'.$this->id;
	
		$db	= JFactory::getDBO();
		$db->setQuery(
				'SELECT name' .
				' FROM #__djc2_items' .
				' WHERE id = '.(int) $this->value
		);
		$title = $db->loadResult();
	
		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
		}
	
		if (empty($title)) {
			$title = JText::_('COM_DJCATALOG2_SELECT_ITEM');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
	
		// The current user display field.
		$html[] = '<div class="fltlft">';
		$html[] = '  <input type="text" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '</div>';
	
		// The user select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '	<a class="modal" title="'.JText::_('COM_DJCATALOG2_CHANGE_ITEM').'"  href="'.$link.'&amp;'.JSession::getFormToken().'=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('COM_DJCATALOG2_CHANGE_ITEM').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';
	
		// The active article id field.
		if (0 == (int)$this->value) {
			$value = '';
		} else {
			$value = (int)$this->value;
		}
	
		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}
	
		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';
	
		return implode("\n", $html);
	}
}
?>