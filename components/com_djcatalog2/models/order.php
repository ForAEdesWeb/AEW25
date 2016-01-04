<?php
/**
 * @version $Id: order.php 294 2014-06-28 19:59:07Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class Djcatalog2ModelOrder extends JModelForm
{
	protected $_item = null;

	protected $_context = 'com_djcatalog2.order';

	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	protected function populateState()
	{
		$table = $this->getTable();
		$key = 'oid';
	
		// Get the pk of the record from the request.
		$pk = JFactory::getApplication()->input->getInt($key);
		$this->setState($this->getName() . '.id', $pk);
	
		// Load the parameters.
		$value = JComponentHelper::getParams('com_djcatalog2');
		$this->setState('params', $value);
	}

	public function getTable($type = 'Orders', $prefix = 'Djcatalog2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();

		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);
	
			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}
		}
	
		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		
		$item = JArrayHelper::toObject($properties, 'JObject');
		
		if (!is_array($item->items)) {
			if (isset($item->id)) {
				$this->_db->setQuery('SELECT * FROM #__djc2_order_items WHERE order_id=\''.$item->id.'\'');
				$item->items = $this->_db->loadObjectList();
			} else {
				$item->items = array();
			}
		}

		if (property_exists($item, 'params'))
		{
			$registry = new JRegistry;
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}
	
		return $item;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_djcatalog2.userprofile', 'userprofile', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
	
		return $form;
	}
	
	protected function preprocessForm(JForm $form, $data, $group = 'content') {
		if (!($form instanceof JForm))
		{
			$this->setError('JERROR_NOT_A_FORM');
			return false;
		}
		
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_djcatalog2');
		$user = JFactory::getUser();
		
		
		$plugin = JFactory::getApplication()->getParams()->get('cart_order_captcha', JFactory::getConfig()->get('captcha'));
		
		if ($user->guest == false || ($plugin === 0 || $plugin === '0' || $plugin === '' || $plugin === null)) {
			$form->removeField('captcha', 'captcha');
		} else {
			$form->setFieldAttribute('captcha', 'plugin', $plugin, 'captcha');
		}
		
		$form->removeField('client_type', 'djcatalog2profile');
		$form->removeField('customer_group_id', 'djcatalog2profile');
		
		$fields = array('company', 'position', 'address', 'city', 'postcode', 'country_id', 'vat_id', 'phone', 'fax', 'www', 'customer_note');
		
		$group = 'djcatalog2profile';
		
		foreach ($fields as $field) {
			// in case config is broken - using defaults from XML file
			if ($params->get('cart_orderfield_'.$field, false) === false) {
				continue;
			}
			
			if ($params->get('cart_orderfield_'.$field, '0') == '0') {
				$form->removeField($field, $group);
			} else {
				if ($params->get('cart_orderfield_'.$field, '0') == '2') {
					$form->setFieldAttribute($field, 'required', 'required', $group);
					$form->setFieldAttribute($field, 'class', $form->getFieldAttribute($field, 'class').' required', $group);
				} else {
					$form->setFieldAttribute($field, 'required', false, $group);
					
					$class = $form->getFieldAttribute($field, 'class', '', $group);
					$class = str_replace('required', '', $class);
					
					$form->setFieldAttribute($field, 'class', $class, $group);
				}
			}	
		}
	}
	
	protected function loadFormData()
	{
		$data = Djcatalog2Helper::getUserProfile(JFactory::getUser()->id);
		$data = JArrayHelper::fromObject($data, false);
		$data= array('djcatalog2profile'=> $data);
	
		$post_data = (array)JFactory::getApplication()->getUserState('com_djcatalog2.order.data', array());
	
		if (!empty($post_data)) {
			foreach($post_data as $k=>$v) {
				$data[$k] = $v;
			}
		}
	
		$this->preprocessData('com_djcatalog2.order', $data);
	
		return $data;
	}
	
	protected function prepareTable(&$table)
	{
		$db = JFactory::getDbo();
		
		if (empty($table->order_number)) {
			$db->setQuery('select max(order_number) from #__djc2_orders');
			$current = (int)$db->loadResult();
			$table->order_number = $current + 1;
		}
	}

	protected function getReorderConditions($table = null)
	{
		$condition = array();
		return $condition;
	}

	public function save($data)
	{
		
		$table = $this->getTable();

		$key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Allow an exception to be thrown.
		try
		{
			// Load the row if saving an existing record.
			if ($pk > 0)
			{
				$table->load($pk);
				$isNew = false;
			}

			// Bind the data.
			if (!$table->bind($data))
			{
				$this->setError($table->getError());

				return false;
			}

			// Prepare the row for saving
			$this->prepareTable($table);

			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Clean the cache.
			$this->cleanCache();

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$pkName = $table->getKeyName();

		if (isset($table->$pkName))
		{
			$this->setState($this->getName() . '.id', $table->$pkName);
		}
		$this->setState($this->getName() . '.number', $table->order_number);
		$this->setState($this->getName() . '.new', $isNew);

		return true;
	}
}