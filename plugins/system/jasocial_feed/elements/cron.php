<?php
/**
 * ------------------------------------------------------------------------
 * JA System Social Feed Plugin for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.filesystem.file');
/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldCron extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Cron';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

			
		$token = '0';
		
		$cacheFile = dirname(__FILE__).'/../cache/cache.txt';
		if(JFile::exists($cacheFile)) {
			$content = JFile::read($cacheFile);
			preg_match('/last_run\s*\:\s*([0-9]+)/i', $content, $matches);
			if(isset($matches[1])) {
				$token = $matches[1];
			}
		} else {
			$content = '';
			JFile::write($cacheFile, $content);
			$token = 0;
		}
		
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		
		$input = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . '/>';
		$button = '<a class="btn" id="run_now" href="'.JURI::root().'index.php?jasocial_feed_cron=1&jatoken='.$token.'" title="'.JText::_('RUN_NOW').'" target="_blank">'.JText::_('RUN_NOW').'</a>';
		
		if(version_compare(JVERSION, '3.0', '>=')) {
			$html = sprintf('%s %s', $input, $button);
		} else {
			$html = sprintf('<div class="fltlft">%s</div><div class="button2-left"><div class="blank">%s</div></div>', $input, $button);
		}
		return $html;
	}
}
