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
class UserxtdModelUser extends \Windwalker\Model\CrudModel
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
	 * The model (base) name
	 *
	 * @var  string
	 */
	protected $name = 'user';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'user';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'users';

	/**
	 * Property data.
	 *
	 * @var  null
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
	 * Method to get a single record.
	 *
	 * @param	integer	$pk The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : $this->state->get($this->getName() . '.id');

		$user = UXFactory::getUser($pk);

		if($user)
		{
			return (object) get_object_vars($user);
		}

		return false;
	}

	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState()
	{
		// Get the application object.
		$user_params	= ExtensionHelper::getParams('com_users');
		$userxtd_params	= ExtensionHelper::getParams('com_userxtd');
		$user_params->merge($userxtd_params);

		// Get the user id.
		$userId = JFactory::getApplication()->getUserState('com_userxtd.edit.profile.id');
		$userId = !empty($userId) ? $userId : (int) JFactory::getUser()->get('id');

		// Set the user id.
		$this->state->set('user.id', $userId);

		// Load the parameters.
		$this->state->set('params', $user_params);
	}

	/**
	 * getFields
	 *
	 * @return  array
	 */
	public function getFields()
	{
		$fields = array('profile');

		return $fields ;
	}

	/**
	 * getProfiles
	 *
	 * @return  \Windwalker\CCK\Form
	 */
	public function getProfiles()
	{
		$catids = $this->state->get('params')->get('CoreRegistration_Categories', array()) ;
		$catids = (array) $catids ;

		if(in_array('*', $catids))
		{
			$catids = null ;
		}

		$fields = \Userxtd\Form\FormHelper::getFieldsByCategory($catids) ;

		return $fields;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @throws  RuntimeException
	 * @return  mixed  The user id on success, false on failure.
	 */
	public function save($data)
	{
		$userId = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('user.id');

		$user = new JUser($userId);

		// Prepare the data for the user object.
		$data['email']		= $data['email1'];
		$data['password']	= $data['password1'];

		// Unset the username if it should not be overwritten
		if (!JComponentHelper::getParams('com_users')->get('change_login_name'))
		{
			unset($data['username']);
		}

		// Unset the block so it does not get overwritten
		unset($data['block']);

		// Unset the sendEmail so it does not get overwritten
		unset($data['sendEmail']);

		// Bind the data.
		if (!$user->bind($data))
		{
			$this->setError(JText::sprintf('USERS_PROFILE_BIND_FAILED', $user->getError()));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Null the user groups so they don't get overwritten
		$user->groups = null;

		// Store the data.
		if (!$user->save())
		{
			throw new \RuntimeException($user->getError());
		}

		// Remove session
		$session = JFactory::getSession();
		$session->set('user', $user) ;

		return $user->id;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		JForm::addFormPath(JPATH_ROOT . '/components/com_users/models/forms') ;

		/** @var $form \JForm */
		$form = $this->loadForm('com_userxtd.profile', 'profile', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		if (!JComponentHelper::getParams('com_users')->get('change_login_name'))
		{
			$form->setFieldAttribute('username', 'class', '');
			$form->setFieldAttribute('username', 'filter', '');
			$form->setFieldAttribute('username', 'description', 'COM_USERS_PROFILE_NOCHANGE_USERNAME_DESC');
			$form->setFieldAttribute('username', 'validate', '');
			$form->setFieldAttribute('username', 'message', '');
			$form->setFieldAttribute('username', 'readonly', 'true');
			$form->setFieldAttribute('username', 'required', 'false');
		}

		// Email validate
		$form->setFieldAttribute('email1', 'unique', 'false');

		return $form ;
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
			$temp = (array) JFactory::getApplication()->getUserState('com_userxtd.edit.profile.data', array());

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
			$results = JFactory::getApplication()
				->triggerEvent('onContentPrepareData', array('com_userxtd.profile', $this->data));

			// Check for errors encountered while preparing the data.
			if (count($results) && in_array(false, $results, true))
			{
				//$this->setError($dispatcher->getError());
				$this->data = false;
			}
		}

		return $this->data;
	}

	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		if (JComponentHelper::getParams('com_users')->get('frontend_userparams'))
		{
			$form->loadFile('frontend', false);

			if (JFactory::getUser()->authorise('core.login.admin'))
			{
				$form->loadFile('frontend_admin', false);
			}
		}

		parent::preprocessForm($form, $data, $group);
	}
}
