<?php
/**
 * @version $Id: djfieldtype.php 132 2013-05-20 07:12:44Z michal $
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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldDjfieldtype extends JFormField {
	
	protected $type = 'Djfieldtype';
	
	protected function getInput()
	{
		$document = JFactory::getDocument();
		$app = JFactory::getApplication();
		JHTML::_('behavior.framework');
		
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$document->addScript(JURI::root() . "administrator/components/com_djcatalog2/models/fields/djfieldtype_legacy.js");
		} else {
			$document->addScript(JURI::root() . "administrator/components/com_djcatalog2/models/fields/djfieldtype.js");
		}
		
        $js = array();
        
        //if ($this->value == 'html') $this->value = 'textarea';
        
        $initvalue = $this->value ? $this->value : 'empty';
        $js[] = 'window.addEvent(\'domready\', function(){';
        $js[] = 'this.Djfieldtype_'.$this->id.' = new Djfieldtype(\''.$initvalue.'\', \''.$this->id.'\', \''.$app->input->get('id',0, 'int').'\');';
        $js[] = '});';
        
        $document->addScriptDeclaration(implode(PHP_EOL, $js));
        
		// Initialize some field attributes.
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';

		$options = array();
		$options[] = JHTML::_('select.option', '', '- '.JText::_('COM_DJCATALOG2_FIELD_TYPE').' -');
		$options[] = JHTML::_('select.option', 'text', JText::_('COM_DJCATALOG2_FIELD_TYPE_TEXT'));
		$options[] = JHTML::_('select.option', 'textarea', JText::_('COM_DJCATALOG2_FIELD_TYPE_TEXTAREA'));
		$options[] = JHTML::_('select.option', 'html', JText::_('COM_DJCATALOG2_FIELD_TYPE_HTML'));
		$options[] = JHTML::_('select.option', 'calendar', JText::_('COM_DJCATALOG2_FIELD_TYPE_CALENDAR'));
		$options[] = JHTML::_('select.option', 'select', JText::_('COM_DJCATALOG2_FIELD_TYPE_SELECT'));
		$options[] = JHTML::_('select.option', 'radio', JText::_('COM_DJCATALOG2_FIELD_TYPE_RADIO'));
		$options[] = JHTML::_('select.option', 'checkbox', JText::_('COM_DJCATALOG2_FIELD_TYPE_CHECKBOX'));
		
		if (!$this->value) {
			$out = JHtml::_('select.genericlist', $options, $this->name, 'class="inputbox required"', 'value', 'text', $this->value, $this->id);			
		} else {
				
			$out = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'" />';
				
			$out .= '<input type="text" id="'.$this->id.'"' .
				' value="'.JText::_('COM_DJCATALOG2_FIELD_TYPE_'.strtoupper($this->value)).'"' .
				$size.' readonly="readonly" class="readonly" '.$maxLength.'/>';
		}
		
		return ($out);
		
	}
}
?>