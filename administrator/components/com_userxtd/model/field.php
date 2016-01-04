<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Model\AdminModel;

// No direct access
defined('_JEXEC') or die;

/**
 * Userxtd Field model
 *
 * @since 1.0
 */
class UserxtdModelField extends AdminModel
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
	protected $name = 'field';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'field';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'fields';

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A reference to a JTable object.
	 *
	 * @return  void
	 */
	protected function prepareTable(\JTable $table)
	{
		parent::prepareTable($table);

		$cck = $this->getContainer()->get('cck');

		/** @var $cck \Windwalker\CCK\CCKEngine */
		$cck->setFieldTable($table);
	}

	/**
	 * loadFormData
	 *
	 * @return  array
	 */
	protected function loadFormData()
	{
		$data = (array) parent::loadFormData();

		$app   = $this->container->get('app');

		// Check the session for previously entered form data.
		$attrs = $app->getUserState("com_userxtd.edit.field.attrs", array());

		if ($attrs)
		{
			$data['attrs'] = json_encode($attrs);
		}

		return $data;
	}

	/**
	 * Post save hook.
	 *
	 * @param JTable $table The table object.
	 *
	 * @return  void
	 */
	public function postSaveHook(\JTable $table)
	{
		parent::postSaveHook($table);
	}

	/**
	 * Method to set new item ordering as first or last.
	 *
	 * @param   JTable $table    Item table to save.
	 * @param   string $position 'first' or other are last.
	 *
	 * @return  void
	 */
	public function setOrderPosition($table, $position = 'last')
	{
		parent::setOrderPosition($table, $position);
	}
}
