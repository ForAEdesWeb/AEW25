<?php
/**
 * @version $Id: fields.php 125 2013-03-28 09:17:52Z michal $
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

class Djcatalog2TableFields extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djc2_items_extra_fields', 'id', $db);
	}
	function bind($array, $ignore = '')
	{	
		if(empty($array['alias'])) {
			$array['alias'] = $array['name'];
		}
		$array['alias'] = JFilterOutput::stringURLSafe($array['alias']);
		$array['alias'] = trim(str_replace('-','_',$array['alias']));
		if(trim(str_replace('_','',$array['alias'])) == '') {
			$array['alias'] = JFactory::getDate()->format('Y_m_d_H_i_s');
		}
		
		if (in_array($array['type'], array('checkbox', 'select', 'radio'))) {
			//$array['searchable'] = '0';
		} else {
			$array['filterable'] = '0';			
		}
		
		return parent::bind($array, $ignore);
	}
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		
		$table = JTable::getInstance('Fields', 'Djcatalog2Table');
		if ($table->load(array('alias'=>$this->alias)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError(JText::_('COM_DJCATALOG2_ERROR_UNIQUE_ALIAS'));
			return false;
		}
		return parent::store($updateNulls);
	}
}

