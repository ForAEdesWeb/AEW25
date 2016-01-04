<?php

/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

/**
 * View to edit
 */
class ModulesmanagerckViewModules extends JViewLegacy {

	protected $state;
	protected $item;
	protected $form;
	protected $params;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {

		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->state = $this->get('State');
		$this->items = $this->get('Items'); 
		$this->form = $this->get('Form'); //var_dump($this->form);
		$this->params = $app->getParams('com_modulesmanagerck');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// if ($this->_layout == 'edit' || !$this->item->id) {
			// $authorised = $user->authorise('core.edit.own', 'com_templateck');

			// if ($authorised !== true) {
				// Redirect to the edit screen.
				// $app->redirect(JURI::root() . 'index.php?option=com_templateck&view=login&template=templatecreatorck&tmpl=login&id=' . $this->item->id);
				// return false;
			// }
		// }

		parent::display($tpl);
	}


}