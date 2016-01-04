<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\CCK;

// No direct access
defined('_JEXEC') or die;

/**
 * Class Form
 *
 * @since 1.0
 */
class Form extends \JForm
{
	/**
	 * Fields group.
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * Get data and handle them for prepare save.
	 *
	 * @param   string $profile A fields group name.
	 * @param   array  $data    The data for save.
	 *
	 * @return  array   Handled tree data.
	 */
	public function getDataForSave($profile, $data = null)
	{
		if ($data)
		{
			$this->bind($data);
		}

		$fields = $this->getGroup($profile);
		$data2  = array();

		foreach ($fields as $field)
		{
			$data2[$field->fieldname] = $field->value;
		}

		return $data2;
	}

	/**
	 * Get data and set every fields' value to format for show.
	 *
	 * @param   string $profile A fields group name.
	 * @param   array  $data    The data for show.
	 *
	 * @return  array  Handled tree data.
	 */
	public function getDataForShow($profile, $data = null)
	{
		if ($data)
		{
			$this->bind($data);
		}

		$fields = $this->getGroup($profile);

		$data2  = array();

		foreach ($fields as $field)
		{
			if (method_exists($field, 'showData'))
			{
				$data2[$field->fieldname] = $field->showData();
			}
			else
			{
				$data2[$field->fieldname] = $field->value;
			}
		}

		return $data2;
	}
}
 