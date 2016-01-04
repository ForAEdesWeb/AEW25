<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR.'/components/com_modules/helpers/modules.php';
require_once JPATH_ADMINISTRATOR.'/includes/toolbar.php';

/**
 * View to edit a module.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 * @since       1.6
 */
class ModulesmanagerckViewModule extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		echo '<div id="toolbar" class="toolbar-list" style="float:left;"><ul>';

		echo '<li id="toolbar-save" class="button">'
			.'<button class="toolbar" onclick="cksubmitbutton(\'module.apply\')" href="#">'
			.'<span class="icon-32-save"> </span>'
			.JText::_('JSAVE')
			.'</button>'
			.'</li>';


		echo '<li id="toolbar-cancel" class="button">'
			.'<button class="toolbar" onclick="cksubmitbutton(\'module.cancel\')" href="#">'
			.'<span class="icon-32-cancel"> </span>'
			.JText::_('JCANCEL')
			.'</button>'
			.'</li>';

		echo '</ul></div><div style="clear:both;"></div>';

	}
}