<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Templates list controller class.
 */
class ModulesmanagerckControllerModules extends JControllerLegacy
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Module', $prefix = 'ModulesmanagerckModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	public function saveTitleAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pk   = $input->post->get('id', 0, 'int');
		$title = $input->post->get('title', '', 'string');

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->savetitle($pk, $title);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	public function savePositionAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pk   = $input->post->get('id', 0, 'int');
		$position = $input->post->get('position', '', 'string');

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveposition($pk, $position);

		if ($return)
		{
			echo $return;
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	public function addModuleAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$extensionid   = $input->get('extensionid', 0, 'int');
		$position = $input->get('position', '', 'string');
		$ordering = $input->get('ordering', 0, 'int');
		$type = $input->get('type', '', 'string');

		// Get the model
		$model = $this->getModel('Module');

		// Save the ordering
		$return = $model->addmodule($extensionid, $position, $ordering, $type);

		if ($return)
		{
			if (!class_exists('CKModulesManager')) {
				include_once JPATH_ROOT . '/components/com_modulesmanagerck/helpers/modulesmanager.php';
			}
			$ckmodulesmanager = new CKModulesManager();
			echo( $ckmodulesmanager->createModule($return) );
			return $ckmodulesmanager->createModule($return);
		}

		return false;

		// Close the application
		JFactory::getApplication()->close();
	}

	public function removeModuleAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$id   = $input->post->get('id', 0, 'int');

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->delete($id);

		if ($return)
		{
			echo '1';
		}

		return false;

		// Close the application
		JFactory::getApplication()->close();
	}

	public function editModuleAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$id   = $input->post->get('id', 0, 'int');

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->getmoduleOptions($id);
// var_dump($return);
		if ($return)
		{
			echo '1';
		}

		return false;

		// Close the application
		JFactory::getApplication()->close();
	}

	public function publishAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$id   = $input->post->get('id', 0, 'int');
		$state   = $input->post->get('state', 0, 'int');

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->publish($id, 1-$state);

		if ($return)
		{
			echo (int) 1-$state;
		}

		return false;

		// Close the application
		JFactory::getApplication()->close();
	}
}