<?php
/**
 * @version $Id: items.php 272 2014-05-21 10:25:49Z michal $
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
// No direct access
defined('_JEXEC') or die;

class Djcatalog2TableItems extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djc2_items', 'id', $db);
	}
	function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
			
		if(empty($array['alias'])) {
			$array['alias'] = $array['name'];
		}
		$array['alias'] = JApplication::stringURLSafe($array['alias']);
		if(trim(str_replace('-','',$array['alias'])) == '') {
			$array['alias'] = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
		
		return parent::bind($array, $ignore);
	}
	/*
	public function load($keys=null, $reset=true) {
		if ($ret = parent::load($keys, $reset)) {
			if (!isset($this->categories)){
				$this->_db->setQuery('SELECT category_id FROM #__djc2_items_categories WHERE item_id=\''.$this->id.'\'');
				$this->categories = $this->_db->loadResultArray();
			}
			return $ret;
		} else {
			return false;
		}
	}*/
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		
		if (!$this->id) {
			if (!intval($this->created)) {
				$this->created = $date->toSql();
			}
			
			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}	
		$table = JTable::getInstance('Items', 'Djcatalog2Table');
		
		if ($app->isSite() || $app->input->get('task') == 'import') {
			if ($table->load(array('alias'=>$this->alias)) && ($table->id != $this->id || $this->id==0)) {
				$db->setQuery('select alias from #__djc2_items where id != '.$this->id.' and alias like '.$db->quote($db->escape($this->alias).'%').' order by alias asc');
				$aliases = $db->loadColumn();
				$suffix = 2;
				while(in_array($this->alias.'-'.$suffix, $aliases)) {
					$suffix++;
				}
				$this->alias = $this->alias.'-'.$suffix;
			}
		} else {
			if ($table->load(array('alias'=>$this->alias,'cat_id'=>$this->cat_id)) && ($table->id != $this->id || $this->id==0)) {
				$this->setError(JText::_('COM_DJCATALOG2_ERROR_UNIQUE_ALIAS'));
				return false;
			}
		}
		
		return parent::store($updateNulls);
	}
}
