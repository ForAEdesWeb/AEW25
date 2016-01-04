<?php
/**
 * @version $Id: djcatalog2.script.php 134 2013-06-04 08:56:29Z michal $
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

class com_djcatalog2InstallerScript {
	function update($parent) {
		$config = JFactory::getConfig();
		$db = JFactory::getDbo();
		$db->setQuery('show tables');
		$tables = $db->loadColumn();
		$db_prefix = $config->get('dbprefix');
		
		/* In v.2.3.rc.3 we added category params feature, 
		 * but forgot to add 'params' column declaration to the SQL installation script.
		 * Since there were few schema updates since that modification
		 * and Joomla is not able to 'go back', we need to make sure that 'params' column exists
		 */
		
		if (count($tables) && in_array($db_prefix.'djc2_categories', $tables)) {		
			$db->setQuery('SHOW COLUMNS FROM #__djc2_categories');
			$category_columns = $db->loadColumn(0);
			if (!in_array('params', $category_columns)) {
				$db->setQuery('ALTER TABLE #__djc2_categories ADD `params` TEXT');
				$db->query();
			}
		}
		
		/*
		 * since v.3.2.beta.1
		 * splitting field values from old single table into separate tables
		 */
		$old_table 	= $db_prefix.'djc2_items_extra_fields_values';
		$text_table = $db_prefix.'djc2_items_extra_fields_values_text';
		$int_table 	= $db_prefix.'djc2_items_extra_fields_values_int';
		
		
		// if all three table exist then we should perform the upgrade
		if (in_array($old_table, $tables) && in_array($text_table, $tables) && in_array($int_table, $tables)) {
			$db->setQuery('select count(*) from #__djc2_items_extra_fields_values');
			$old_count = $db->loadResult();
			
			$db->setQuery('select count(*) from #__djc2_items_extra_fields_values_text');
			$text_count = $db->loadResult();
			
			$db->setQuery('select count(*) from #__djc2_items_extra_fields_values_int');
			$int_count = $db->loadResult();
			
			$errors = array();
			
			// is there anything to migrate?
			if ($old_count > 0) {
				/* 
				 * if the new _text table isn't empty then probably something went wrong before
				 * so we don't migrate any data
				 */
				if ($text_count == 0) {
					$db->setQuery('insert ignore into #__djc2_items_extra_fields_values_text '
								.' (`id`, `item_id`, `field_id`, `value`)'
								.' select v.id, v.item_id, v.field_id, v.value'
								.' from #__djc2_items_extra_fields_values as v '
								.' inner join #__djc2_items_extra_fields as f on f.id=v.field_id '
								.' where (type=\'html\' or type=\'text\' or type=\'textarea\')');
					$success = $db->query();
					if(!$success && $db->getErrorNum() != 1060) {
						$errors[] = $db->getErrorMsg(true);
					}
				}
				
				/* 
				 * if the new _int table isn't empty then probably something went wrong before
				 * so we don't migrate any data
				 */
				if ($int_count == 0) {
					$db->setQuery('insert ignore into #__djc2_items_extra_fields_values_int '
								.' (`id`, `item_id`, `field_id`, `value`)'
								.' select v.id, v.item_id, v.field_id, v.value'
								.' from #__djc2_items_extra_fields_values as v '
								.' inner join #__djc2_items_extra_fields as f on f.id=v.field_id '
								.' where (type=\'select\' or type=\'radio\' or type=\'checkbox\')');
					$db->query();
					$success = $db->query();
					if(!$success && $db->getErrorNum() != 1060) {
						$errors[] = $db->getErrorMsg(true);
					}
				}
			}
			
			// if during the migration there haven't occurred any errors, remove the old table.
			if (count($errors) == 0) {
				$db->setQuery('drop table #__djc2_items_extra_fields_values');
				$db->query();
			}
		}
	}   
}