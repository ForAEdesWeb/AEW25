<?php
/**
 * @version		$Id: style.php 16582 2010-04-29 05:35:04Z infograf768 $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Template style controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_profiler
 * @since		1.6
 */
class FaviconControllerAssign extends JControllerLegacy
{
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Apply, Save & New, and Save As copy should be standard on forms.
		$this->registerTask('apply',		'save');
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param	array	An array of input data.
	 * @param	string	The name of the key for the primary key.
	 *
	 * @return	boolean
	 */
	protected function allowEdit()
	{
		return JFactory::getUser()->authorise('core.edit', 'com_profiler');
	}

	/**
	 * Method to check if you can save a new or existing record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param	array	An array of input data.
	 * @param	string	The name of the key for the primary key.
	 *
	 * @return	boolean
	 */
	protected function allowSave()
	{
		return $this->allowEdit();
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional (note, the empty array is atypical compared to other models).
	 *
	 * @return	object	The model.
	 */
	public function &getModel($name = 'Assign', $prefix = 'FaviconModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	/**
	 * This controller does not have a display method. Redirect back to the list view of the component.
	 *
	 * @return	void
	 */
	public function display()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_favicon&view=assign', false));
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @return	void
	 */
	public function edit()
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$model		= $this->getModel();
		$context	= 'com_profiler.edit.style';

		// Access check.
		if (!$this->allowEdit()) {
			return JError::raiseWarning(403, 'JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED');
		}

		// Check-out succeeded, push the new record id into the session.
		$app->setUserState($context.'.data', null);
		$this->setRedirect('index.php?option=com_profiler&view=style&layout=edit');
		return true;
	}

	/**
	 * Method to cancel an edit
	 *
	 * @return	void
	 */
	public function cancel()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app		= JFactory::getApplication();
		$model		= $this->getModel();
		$context	= 'com_profiler.edit.style';

		// Clean the session data and redirect.
		$app->setUserState($context.'.data',	null);
		$this->setRedirect(JRoute::_('index.php?option=com_favicon', false));
	}

	/**
	 * Saves a template style file.
	 */
	public function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app		= JFactory::getApplication();
		$data		= JRequest::getVar('jform', array(), 'post', 'array');
		$context	= 'com_favicon.edit.assign';
		$task		= $this->getTask();
		$model		= $this->getModel();

		// Access check.
		if (!$this->allowSave()) {
			return JError::raiseWarning(403, 'JERROR_SAVE_NOT_PERMITTED');
		}

                $model->save($data);
                $this->setRedirect(JRoute::_('index.php?option=com_favicon&view=assign&layout=edit&id='.$data['iconid'].'&closewindow=true',false));
	}
}