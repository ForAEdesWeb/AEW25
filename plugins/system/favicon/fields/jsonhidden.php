<?php
/**
 * @version		$Id: jsonhidden.php 20196 2011-01-09 02:40:25Z mrichey $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2011 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldJSONHidden extends JFormField
{
	protected $type = 'JSONHidden';

	protected function getInput()
	{
		// Initialize some field attributes.
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		return '<input type="hidden" name="'.$this->name.'" id="'.$this->id.'"' .
				' value="'.htmlspecialchars(json_encode((object)$this->value), ENT_COMPAT, 'UTF-8').'"' .
				$class.$disabled.$onchange.' />';
	}
}