<?php
/**
 * @version $Id: producers.php 286 2014-06-17 05:32:33Z michal $
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

class Djcatalog2TableProducers extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djc2_producers', 'id', $db);
	}
	function bind($array, $ignore = '')
	{	
		if(empty($array['alias'])) {
			$array['alias'] = $array['name'];
		}
		$array['alias'] = JApplication::stringURLSafe($array['alias']);
		if(trim(str_replace('-','',$array['alias'])) == '') {
			$array['alias'] = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
		
		return parent::bind($array, $ignore);
	}
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
		
		$table = JTable::getInstance('Producers', 'Djcatalog2Table');
		
		if ($app->isSite() || $app->input->get('task') == 'import') {
			if ($table->load(array('alias'=>$this->alias)) && ($table->id != $this->id || $this->id==0)) {
				$db->setQuery('select alias from #__djc2_producers where id != '.$this->id.' and alias like '.$db->quote($db->escape($this->alias).'%').' order by alias asc');
				$aliases = $db->loadColumn();
				$suffix = 2;
				while(in_array($this->alias.'-'.$suffix, $aliases)) {
					$suffix++;
				}
				$this->alias = $this->alias.'-'.$suffix;
			}
		} else {
			if ($table->load(array('alias'=>$this->alias)) && ($table->id != $this->id || $this->id==0)) {
				$this->setError(JText::_('COM_DJCATALOG2_ERROR_UNIQUE_ALIAS'));
				return false;
			}	
		}
		
		return parent::store($updateNulls);
	}
}
