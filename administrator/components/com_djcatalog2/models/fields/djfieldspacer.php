<?php
/**
* @version $Id: djfieldspacer.php 277 2014-05-23 13:38:20Z michal $
* @package DJ-Catalog2
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer $Author: michal $ Michal Olczyk - michal.olczyk@design-joomla.eu
*
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


defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Provides spacer markup to be used in form layouts.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldDJFieldSpacer extends JFormField
{
	protected $type = 'DJFieldSpacer';
	
	protected static $load_assets = true;
	
	public function __construct($form = null)
	{
		$document = JFactory::getDocument();
		$version = new JVersion;
		$lang = JFactory::getLanguage();
		
		if (self::$load_assets) {
			if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
				$document->addStylesheet(JURI::base(true).'/components/com_djcatalog2/assets/css/djfieldspacer_legacy.css');
			} else {
				$document->addStylesheet(JURI::base(true).'/components/com_djcatalog2/assets/css/djfieldspacer.css');
			}
			if ($lang->get('lang') != 'en-GB') {
				$lang = JFactory::getLanguage();
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR, 'en-GB', false, false);
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2', 'en-GB', false, false);
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR, null, true, false);
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2', null, true, false);
			}
			self::$load_assets = false;
		}
		
		return parent::__construct($form);
	}
	
	protected function getInput()
	{
		return ' ';
	}

	protected function getLabel()
	{
		$html = array();
		$class = $this->element['class'] ? (string) $this->element['class'] : '';
		$class .= ' djspacer';

		$html[] = '<span class="spacer">';
		$html[] = '<span class="before"></span>';
		$html[] = '<span class="' . $class . '">';

		$label = '';

		// Get the label text from the XML element, defaulting to the element name.
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = $this->translateLabel ? JText::_($text) : $text;

		// Build the class for the label.
		//$class = !empty($this->description) ? 'hasTip' : '';

		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->id . '-lbl">' . $text ;

		// If a description is specified, use it to build a tooltip.
		if (!empty($this->description))
		{
			$label .= ' <div class="small">'
					. ($this->translateDescription ? JText::_($this->description) : $this->description)
					. '</div> ';
		}

		// Add the label text and closing tag.
		$label .= '</label>';

		$html[] = $label;
		$html[] = '</span>';
		$html[] = '<span class="after"></span>';
		$html[] = '</span>';

		return implode('', $html);
	}

	/**
	 * Method to get the field title.
	 *
	 * @return  string  The field title.
	 *
	 * @since   11.1
	 */
	protected function getTitle()
	{
		return $this->getLabel();
	}
}