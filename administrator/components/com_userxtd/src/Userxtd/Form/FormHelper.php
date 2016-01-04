<?php
/**
 * Part of joomla330 project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Userxtd\Form;

use Windwalker\CCK\Form;
use Windwalker\DI\Container;

// No direct access
defined('_JEXEC') or die;

/**
 * Class FormHelper
 *
 * @since 1.0
 */
class FormHelper
{
	/**
	 * A cache to store Table columns.
	 *
	 * @var    array
	 */
	static public $columns = array();

	/**
	 * getFieldsByCategory
	 *
	 * @param integer[] $catid
	 * @param \JForm    $form
	 * @param array     $option
	 *
	 * @return  null|Form
	 */
	public static function getFieldsByCategory($catid = array(), $form = null, $option = array())
	{
		if (!$catid || count($catid) == 0)
		{
			return self::getFields(null, $form, $option);
		}

		if (is_string($catid))
		{
			$catid = array($catid);
		}

		$catid = implode(',', $catid);

		return self::getFields("catid IN ({$catid})", $form, $option);
	}

	/**
	 * getFields
	 *
	 * @param array   $condition
	 * @param \JForm  $form
	 * @param array   $option
	 *
	 * @return  \JForm
	 */
	public static function getFields($condition = null, $form = null, $option = array())
	{
		$name = \JArrayHelper::getValue($option, 'name', 'UserXTD');
		$control = \JArrayHelper::getValue($option, 'control', null);
		$hide_in_reg = \JArrayHelper::getValue($option, 'hide_in_registration', null);

		if (!$form)
		{
			$form = new Form($name, array('control' => $control));
		}

		$app = \JFactory::getApplication();
		$db  = \JFactory::getDbo();
		$q   = $db->getQuery(true);

		if ($condition)
		{
			$q->where($condition);
		}

		// Get FormFields
		// ============================================================================
		$tables = array(
			'a' => '#__userxtd_fields',
			'b' => '#__categories'
		);

		$select = static::getSelectList($tables);

		$q->select($select)
			->from("#__userxtd_fields AS a")
			->join('LEFT', '#__categories AS b ON a.catid = b.id')
			->where('a.published > 0')
			->order(isset($option['order']) ? $option['order'] : "b.lft, a.ordering");

		$db->setQuery($q);
		$fields = $db->loadObjectList('a_id');

		// Separate Categories
		// ============================================================================
		$field_groups = array();

		foreach ($fields as $key => $field)
		{
			// If hide_in registration exists, do not show this field.
			$attrs = json_decode($field->a_attrs, true);

			if ($hide_in_reg && \JArrayHelper::getValue($attrs, 'hide_in_registration'))
			{
				continue;
			}

			// init array
			if (!isset($field_groups[$field->a_catid]))
			{
				$field_groups[$field->a_catid] = array();
			}

			$field_groups[$field->a_catid][] = $field;
		}

		/** @var $cck \Windwalker\CCK\CCKEngine */
		$cck = Container::getInstance('com_userxtd')->get('cck');

		// Build Form
		// ============================================================================
		foreach ($field_groups as $group)
		{
			$elements = array();

			foreach ($group as &$field)
			{
				$elements[] = $cck->buildElement($field->a_field_type, $field->a_attrs);
			}

			$elements = implode("\n", $elements);
			$elements = $cck->buildFormXML($elements, 'userxtd-cat-' . $group[0]->a_catid, 'profile', $group[0]->b_title);

			$field_list = \JArrayHelper::getColumn($group, 'name');
			$form->load($elements, false);
		}

		return $form;
	}

	/**
	 * Get select query from tables array.
	 *
	 * @param    array    $tables   Tables name to get columns.
	 * @param    boolean  $all      Contain a.*, b.* etc.
	 *
	 * @return   array    Select column list.
	 */
	public static function getSelectList( $tables = array() , $all = true )
	{
		$db = \JFactory::getDbo();

		$select = array();
		$fields = array();
		$i = 'a';

		foreach ($tables as $k => $table)
		{
			if (empty(self::$columns[$table]))
			{
				self::$columns[$table] = $db->getTableColumns($table);
			}

			$columns = self::$columns[$table];

			if ($all)
			{
				$select[] = "`{$k}`.*";
			}

			foreach ($columns as $key => $var)
			{
				$fields[] = $db->qn("{$k}.{$key}", "{$k}_{$key}");
			}

			$i = ord($i);
			$i++;
			$i = chr($i);
		}

		return $final = implode(",", $select) . ",\n" . implode(",\n", $fields);
	}
}
 