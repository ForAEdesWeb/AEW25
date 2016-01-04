<?php
/**
 * @version $Id: import.php 143 2013-10-02 14:36:44Z michal $
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

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.helper');
jimport( 'joomla.application.component.model');


class DJCatalog2ModelImport extends JModelLegacy {

	protected $_categories;
	protected $_producers;
	protected $_users;
	protected $_fieldgroups;
	
	function __construct()
	{
		parent::__construct();
	}
	public function getCategories(){
		if(empty($this->_categories)) {
			$query = "SELECT * FROM #__djc2_categories ORDER BY name";
			$this->_categories = $this->_getList($query,0,0);
		}
		return $this->_categories;
	}
	
	public function getProducers(){
		if(empty($this->_producers)) {
			$query = "SELECT * FROM #__djc2_producers ORDER BY name";
			$this->_producers = $this->_getList($query,0,0);
		}
		return $this->_producers;
	}
	public function getUsers() {
		if(empty($this->_users)) {
			$query = "SELECT * FROM #__users ORDER BY name";
			$this->_users = $this->_getList($query,0,0);
		}
		return $this->_users;
	}
	public function getFieldgroups() {
		if(empty($this->_fieldgroups)) {
			$query = "SELECT * FROM #__djc2_items_extra_fields_groups ORDER BY name";
			$this->_fieldgroups = $this->_getList($query,0,0);
		}
		return $this->_fieldgroups;
	}
}
?>
