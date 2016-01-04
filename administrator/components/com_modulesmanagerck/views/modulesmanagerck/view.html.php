<?php

/**
 * @name		Modules Manager CK
 * @package		com_templateck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * About View
 */
class ModulesmanagerckViewModulesmanagerck extends JViewLegacy {

	/**
	 * About view display method
	 * @return void
	 * */
	function display($tpl = null) {
		JToolBarHelper::title(JText::_('COM_MODULESMANAGERCK'), 'home_modulesmanagerck');
		$this->ckversion = $this->getParam('version');
		$this->testplugin = $this->testplugin();
		parent::display($tpl);
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */

	function getParam($name) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_modulesmanagerck"');
		$manifest = json_decode($db->loadResult(), true);
		return $manifest[$name];
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */

	function testPlugin() {
		// $db = JFactory::getDbo();
		// $db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_modulesmanagerck"');
		// $manifest = json_decode( $db->loadResult(), true );
		// return $manifest[ $name ];
	}

}
