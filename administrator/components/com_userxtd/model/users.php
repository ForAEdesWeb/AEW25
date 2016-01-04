<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Userxtd\Table\UXTable;
use Windwalker\DI\Container;
use Windwalker\Joomla\DataMapper\DataMapper;
use Windwalker\Model\Filter\FilterHelper;
use Windwalker\Model\ListModel;

// No direct access
defined('_JEXEC') or die;

/**
 * Userxtd Users model
 *
 * @since 1.0
 */
class UserxtdModelUsers extends ListModel
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
	protected $name = 'users';

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
	 * Property fields.
	 *
	 * @var  \Windwalker\Data\DataSet
	 */
	protected $fields = null;

	/**
	 * Configure tables through QueryHelper.
	 *
	 * @return  void
	 */
	protected function configureTables()
	{
		$queryHelper = $this->getContainer()->get('model.users.helper.query', Container::FORCE_NEW);

		$queryHelper->addTable('user', '#__users')
			->addTable('profile',  '#__userxtd_profiles', 'user.id = profile.user_id');

		$this->filterFields = array_merge($this->filterFields, $queryHelper->getFilterFields());
	}

	/**
	 * getFields
	 *
	 * @return  mixed
	 */
	public function getFields()
	{
		// Get a storage key.
		if ($this->fields)
		{
			return $this->fields;
		}

		// Get Field Filter
		$app = JFactory::getApplication() ;
		$filter_fields = $app->getUserStateFromRequest($this->context . '.field.fields', 'fields');

		$fields = with(new DataMapper(UXTable::FIELDS))
			->find(array('published > 0'), 'catid, ordering');

		// Set All Fields
		$this->state->set('allFields', iterator_to_array($fields));

		// if is default
		if(!$filter_fields)
		{
			$filter_fields = $fields->name;
			$filter_fields = array_slice($filter_fields, 0, 5);
		}

		// Set Filtered Fields
		$filtered = array();

		foreach($fields as $row)
		{
			if(in_array($row->name, $filter_fields))
			{
				$filtered[] = $row;
			}
		}

		$this->state->set('filteredFields', $filtered);

		// Set Keys into State
		$this->state->set('profileKeys', $filter_fields);

		// Set Keys into Filter Fields
		$keys = $fields->name;
		$this->filterFields = array_merge($this->filterFields, $fields->name);

		// Add the items to the internal cache.
		return $this->fields = $fields;
	}

	/**
	 * The post getQuery object.
	 *
	 * @param JDatabaseQuery $query The db query object.
	 *
	 * @return  void
	 */
	protected function postGetQuery(\JDatabaseQuery $query)
	{
		$keys = $this->state->get('profileKeys');

		// Build SQL Pivot
		// ========================================================================
		foreach ($keys as $key)
		{
			if ($key)
			{
				/*
				 * Use MySQL Pivot query:
				 * MAX(IF(profile.key = 'foo', profile.value, NULL)) AS foo
				 */
				$query->select($query->format("MAX(IF(profile.key = %q, profile.value, NULL)) AS %e", $key, $key));
			}
		}

		$query->group('user.id');
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method will only called in constructor. Using `ignore_request` to ignore this method.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'user.id', $direction = 'DESC')
	{
		$this->getFields();

		parent::populateState($ordering, $direction);
	}

	/**
	 * Get search fields from form xml.
	 *
	 * @return  array Search fields.
	 */
	public function getSearchFields()
	{
		return array_merge(parent::getSearchFields(), $this->state->get('profileKeys'));
	}

	/**
	 * Process the query filters.
	 *
	 * @param JDatabaseQuery $query   The query object.
	 * @param array          $filters The filters values.
	 *
	 * @return  JDatabaseQuery The db query object.
	 */
	protected function processFilters(\JDatabaseQuery $query, $filters = array())
	{
		return parent::processFilters($query, $filters);
	}

	/**
	 * Configure the filter handlers.
	 *
	 * Example:
	 * ``` php
	 * $filterHelper->setHandler(
	 *     'user.date',
	 *     function($query, $field, $value)
	 *     {
	 *         $query->where($field . ' >= ' . $value);
	 *     }
	 * );
	 * ```
	 *
	 * @param FilterHelper $filterHelper The filter helper object.
	 *
	 * @return  void
	 */
	protected function configureFilters($filterHelper)
	{
	}

	/**
	 * Configure the search handlers.
	 *
	 * Example:
	 * ``` php
	 * $searchHelper->setHandler(
	 *     'user.title',
	 *     function($query, $field, $value)
	 *     {
	 *         return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
	 *     }
	 * );
	 * ```
	 *
	 * @param SearchHelper $searchHelper The search helper object.
	 *
	 * @return  void
	 */
	protected function configureSearches($searchHelper)
	{
		foreach ($this->getSearchFields() as $key)
		{
			$searchHelper->setHandler(
				$key,
				function($query, $field, $value)
				{
					/** @var $query \JDatabaseQuery */
					$query->having($query->format('%n LIKE "%%%E%%"', $field, $value), 'OR');
				}
			);
		}
	}
}
