<?php
/**
 * @version $Id: import.php 282 2014-06-09 08:22:41Z michal $
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

defined('_JEXEC') or die();

class Djcatalog2ImportHelper {
	
	public static function parseCSV($filename, $separator = ",", $enclosure = "\"") {
		$rows = array();
		if(($handle = fopen($filename, "r")) !== FALSE) {
			$headers = fgetcsv($handle, 0, $separator, $enclosure);
			if($headers !== FALSE) {
				while(($data = fgetcsv($handle, 0, $separator, $enclosure)) !== false) {
					$row = array();
					for($i = 0; $i < count($headers); $i++) {
						if(array_key_exists($i, $data)) {
							$row[$headers[$i]] = $data[$i];
						}
					}
					$rows[] = $row;
				}
			}
			fclose($handle);
		}
		return $rows;
	}
	
	public static function storeRecords($rows, $model, $type, $defaults = array()) {
		$db = JFactory::getDbo();
		
		$img_import_source = JPATH_ROOT.DS.'media'.DS.'djcatalog2'.DS.'import'.DS.'images';
		$att_import_source = JPATH_ROOT.DS.'media'.DS.'djcatalog2'.DS.'import'.DS.'files';
		
		$inserted = 0;
		$updated = 0;
		$ignored = 0;
		$failed = 0;
		
		$messages = array('info'=>array(), 'warning' => array(), 'error'=>array());
		
		$messages['info'][] = JText::_('COM_DJCATALOG2_IMPORT_SUMMARY_'.strtoupper($type));
		
		foreach ($rows as $key=>$row) {
			// By default, initially, each record is considered as new 
			$new = true;
			
			// If ID has been supplied and is greater than 0,
			// let's load it first into $old_row variable
			$old_row = null;
			if ((int)$row['id'] > 0) {
				$old_row = $model->getItem($row['id']);
			}
			
			// If the record exists in DB,
			// fill-in any missing data in CSV record
			if (!empty($old_row))
			{
				$new = false;
				foreach($old_row as $k=>$v) {
					if (empty($row[$k])) {
						$row[$k] = $v;
					}
				}
			} 
			else if ((int)$row['id'] > 0) {
				$new = false;
			}
			else {
				$row['id'] = 0;
				$row['alias'] = !isset($row['alias']) ? null : $row['alias'];
			}
		
			if (empty($row['name'])) {
				$ignored++;
				continue;
			}
		
			foreach($defaults as $column => $value) {
				if (empty($row[$column])) {
					$row[$column] = $value;
				}
			}
		
			$img_list = null;
			if (isset($row['images'])) {
				$img_list = explode(',', $row['images']);
				unset($row['images']);
			}
			
			$att_list = null;
			if (isset($row['files'])) {
				$att_list = explode(',', $row['files']);
				unset($row['files']);
			}
		
			if (!$model->save($row)) {
				$messages['error'][] = JText::_('COM_DJCATALOG2_IMPORT_ERROR_ROW').': ['.($key+1).', '.$row['name'].']. '.$model->getError();
				$failed++;
				continue;
			}
		
			$last_id = $model->getState($model->getName() . '.id');
			if ($last_id > 0) {
				if (!empty($img_list)) {
					self::storeMedias($last_id, $type, $img_list, $img_import_source, DJCATIMGFOLDER, '#__djc2_images');
				}
				if (!empty($att_list)) {
					self::storeMedias($last_id, $type, $att_list, $att_import_source, DJCATATTFOLDER, '#__djc2_files');
				}
			}
		
			if ($new) {
				$inserted++;
			} else {
				$updated++;
			}
		}
		
		$messages['info'][] = JText::_('COM_DJCATALOG2_IMPORT_INSERTED').': '.$inserted;
		$messages['info'][] = JText::_('COM_DJCATALOG2_IMPORT_UPDATED').': '.$updated;
		
		if ($ignored > 0) {
			$messages['warning'][] = JText::_('COM_DJCATALOG2_IMPORT_IGNORED').': '.$ignored;
		}
		if ($failed > 0) {
			$messages['error'][] = JText::_('COM_DJCATALOG2_IMPORT_FAILED').': '.$failed;
		}
		
		return $messages;
	}
	
	public static function storeMedias($item_id, $type, $files, $source_path, $target_path, $table_name) {
		$db = JFactory::getDbo();
		
		$destination = DJCatalog2FileHelper::getDestinationFolder($target_path, $item_id, $type);
		$sub_path = DJCatalog2FileHelper::getDestinationPath($item_id, $type);
		if (!JFolder::exists($destination)) {
			$destExist = JFolder::create($destination, 0755);
		} else {
			$destExist = true;
		}
		if ($destExist && !empty($files)) {
			$ordering = 1;
			foreach($files as $file) {
				$file = trim($file);
				if ($file && JFile::exists($source_path.DS.$file)){
					$obj = new stdClass();
					$obj->id = null;
					$obj->fullname = DJCatalog2FileHelper::createFileName($file, $destination);
					$obj->name = JFile::stripExt($obj->fullname);
					$obj->ext = JFile::getExt($obj->fullname);
					$obj->item_id = $item_id;
					$obj->path = $sub_path;
					$obj->fullpath = $sub_path.'/'.$obj->fullname;
					$obj->type = $type;
					$obj->caption = JFile::stripExt($file);
					$obj->ordering = $ordering++;
		
					if (JFile::copy($source_path.DS.$file, $destination.DS.$obj->fullname)) {
						$db->insertObject( $table_name, $obj, 'id');
					}
				}
			}
		}
	}
	
}