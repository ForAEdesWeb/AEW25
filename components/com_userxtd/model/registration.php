<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\System\ExtensionHelper;

// No direct access
defined('_JEXEC') or die;

/**
 * Userxtd User model
 *
 * @since 1.0
 */
class UserxtdModelRegistration extends \Windwalker\Model\CrudModel
{
	/**
	 * Component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'userxtd';

	/**
	 * The URL option for the component.
	 *
	 * @var  string
	 */
	protected $option = 'com_userxtd';

	/**
	 * The prefix to use with messages.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_USERXTD';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $name = 'registration';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'registration';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'registrations';

	/**
	 * Property data.
	 *
	 * @var  \stdClass
	 */
	protected $data = null;

	/**
	 * getTable
	 *
	 * @param string $name
	 * @param string $prefix
	 * @param array  $options
	 *
	 * @return  JTable
	 */
	public function getTable($name = 'User', $prefix = 'JTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	JForm	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get Registration Form from om_users
		JForm::addFormPath( \Windwalker\Helper\PathHelper::getSite('com_users') . '/models/forms' );

		$form = $this->loadForm("{$this->option}.{$this->viewItem}", 'registration', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		if ($this->data === null)
		{

			$userId = $this->state->get('user.id');

			// Initialise the table with JUser.
			$this->data	= new JUser($userId);

			// Set the base user data.
			$this->data->email1 = $this->data->get('email');
			$this->data->email2 = $this->data->get('email');

			// Override the base user data with any data in the session.
			$temp = (array) JFactory::getApplication()->getUserState('com_users.registration.data', array());

			foreach ($temp as $k => $v)
			{
				$this->data->$k = $v;
			}

			// Unset the passwords.
			unset($this->data->password1);
			unset($this->data->password2);

			$registry = new JRegistry($this->data->params);
			$this->data->params = $registry->toArray();

			// Get the dispatcher and load the users plugins.
			JPluginHelper::importPlugin('user');

			// Trigger the data preparation event.
			$results = JFactory::getApplication()->triggerEvent('onContentPrepareData', array('com_userxtd.profile', $this->data));

			// Check for errors encountered while preparing the data.
			if (count($results) && in_array(false, $results, true))
			{
				//$this->setError($dispatcher->getError());
				$this->data = false;
			}
		}

		return $this->data;
	}
}
