<?php
/**
 * @version $Id: image.php 342 2014-08-21 14:17:37Z michal $
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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

require_once (dirname(__FILE__).DS.'file.php');

class DJCatalog2ImageHelper extends DJCatalog2FileHelper {

	static $images = null;

	public static function renderInput($itemtype, $itemid=null, $multiple_upload = false) {
		if (!$itemtype) {
			return false;
		}
		
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$document = JFactory::getDocument();
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		
		$count_limit = $app->isAdmin() ? -1 : (int)$params->get('fed_max_images', 6);
		$total_imgs = 0;
		
		// given in KB
		$size_limit = $app->isAdmin() ? 0 : (int)$params->get('fed_max_image_size', 2048);
		
		$whitelist = explode(',', $params->get('allowed_image_types', 'jpg,png,gif'));
		foreach($whitelist as $key => $extension) {
			$whitelist[$key] = strtolower(trim($extension));
		}
		
		$images = array();
		if ($itemid) {
			$db->setQuery('SELECT * '.
						' FROM #__djc2_images '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->quote( $itemtype).
						' ORDER BY ordering ASC, name ASC ');
			$images = $db->loadObjectList();
		}
		
		
		$record_type = 'image';
		
		return self::getUploader($record_type, $itemtype, $itemid, $count_limit, $size_limit, $whitelist, $images, $multiple_upload);
	}
	public static function getImages($itemtype, $itemid, $excluded = false) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$hash = $itemtype.'.'.$itemid;
		if (isset(self::$images[$hash])) {
			return self::$images[$hash];
		}
		$db = JFactory::getDbo();
		$images = array();
		
		$include_excluded = ($excluded) ? '' : ' AND exclude=0';
		
		$db->setQuery('SELECT * '.
						' FROM #__djc2_images '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote($itemtype) . $include_excluded .
						' ORDER BY ordering ASC, name ASC ');
		$images = $db->loadObjectList();

		if (count($images)) {
			foreach ( $images as $key=>$image) {
				$images[$key]->original = self::getImageUrl($image->fullpath);
				$images[$key]->fullscreen = self::getImageUrl($image->fullpath,'fullscreen');
				$images[$key]->frontpage = self::getImageUrl($image->fullpath,'frontpage');
				$images[$key]->large = self::getImageUrl($image->fullpath,'large');
				$images[$key]->medium = self::getImageUrl($image->fullpath,'medium');
				$images[$key]->small = self::getImageUrl($image->fullpath,'small');
				$images[$key]->thumb = self::getImageUrl($image->fullpath,'thumb');
			}
		}
		self::$images[$hash] = $images;

		return self::$images[$hash];

	}

	public static function getImageUrl($fullpath, $size = null) {
		$suffix = '';
		switch($size) {
			case 'fullscreen': $suffix = '_f'; break;
			//case 'frontpage': $suffix = '_fp'; break;
			case 'large': $suffix = '_l'; break;
			case 'medium': $suffix = '_m'; break;
			case 'small': $suffix = '_t'; break;
			case 'thumb': $suffix = '_s'; break;
			case 'original':
			default: $suffix = ''; break;
		}
		return DJCATIMGURLPATH.'/'.self::addSuffix($fullpath, $suffix);
	}
	
	public static function deleteFiles($itemtype, $itemid) {
		return self::deleteImages($itemtype, $itemid);
	}
	
	public static function deleteImages($itemtype, $itemid) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$db = JFactory::getDbo();
		$images = array();
		$db->setQuery('SELECT id, fullname, path, fullpath '.
						' FROM #__djc2_images '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
		$images = $db->loadObjectList();

		$images_to_remove = array();
		if (count($images)) {
			foreach ($images as $key=>$image) {
				$image_dir = DJCATIMGFOLDER.DS.str_replace('/', DS, $image->path);
				$image_path = $image_dir.DS.$image->fullname;
				
				if (JFile::exists($image_path)) {
					if (JFile::delete($image_path)) {
						$images_to_remove[] = $image->id;
						if (JFile::exists($image_dir.DS.self::addSuffix($image->fullname, '_s'))) {
							JFile::delete($image_dir.DS.self::addSuffix($image->fullname, '_s'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($image->fullname, '_f'))) {
							JFile::delete($image_dir.DS.self::addSuffix($image->fullname, '_f'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($image->fullname, '_t'))) {
							JFile::delete($image_dir.DS.self::addSuffix($image->fullname, '_t'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($image->fullname, '_m'))) {
							JFile::delete($image_dir.DS.self::addSuffix($image->fullname, '_m'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($image->fullname, '_l'))) {
							JFile::delete($image_dir.DS.self::addSuffix($image->fullname, '_l'));
						}
					}
				}
			}
		}
		if (count($images_to_remove)) {
			JArrayHelper::toInteger($images_to_remove);
			$ids = implode(',',$images_to_remove);
			$db->setQuery('DELETE FROM #__djc2_images WHERE id IN ('.$ids.')');
			$db->query();
		}

		return true;

	}
	
	public static function saveFiles($itemtype, $item, &$params, $isNew) {
		return self::saveImages($itemtype, $item, $params, $isNew);
	}


	public static function saveImages($itemtype, $item, &$params, $isNew) {
		if (!$itemtype || !$item || empty($params)) {
			return false;
		}

		$itemid = $item->id;
		if (!($itemid) > 0) {
			return false;
		}
		
		$prefix = $suffix = 'image_';//.$itemtype.'_';
		
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		
		$count_limit = $app->isAdmin() ? -1 : (int)$params->get('fed_max_images', 6);
		$total_imgs = 0;
		
		// given in KB
		$size_limit = $app->isAdmin() ? 0 : (int)$params->get('fed_max_image_size', 2048);
		
		// given in Bytes
		$size_limit *= 1024;
		
		$whitelist = explode(',', $params->get('allowed_image_types', 'jpg,png,gif'));
		foreach($whitelist as $key => $extension) {
			$whitelist[$key] = strtolower(trim($extension));
		}
		
		$ids = $app->input->get($prefix.'file_id', array(),'array');
		$names = $app->input->get($prefix.'file_name', array(),'array');
		$captions = $app->input->get($prefix.'caption', array(),'array');
		$excludes = $app->input->get($prefix.'exclude', array(),'array');
		
		$files_to_update = array();
		$files_to_save = array();
		
		$update_ids = array();
		$files = array();
		
		$destination = self::getDestinationFolder(DJCATIMGFOLDER, $itemid, $itemtype);
		$sub_path = self::getDestinationPath($itemid, $itemtype);
		if (!JFolder::exists($destination)) {
			$destExist = JFolder::create($destination, 0755);
		} else {
			$destExist = true;
		}
		
		$additional_ids = (count($ids) > 0) ? 'OR id IN ('.implode(',', $ids).')' : '';
		$db->setQuery('select * from #__djc2_images where type='.$db->quote($itemtype).' and (item_id='.(int)$itemid.' '.$additional_ids.')');

		$existing_files = $db->loadObjectList('id');
		$ordering = 1;

		if (!empty($ids)) {
			foreach($ids as $key => $id) {
				
				/*if ($count_limit >= 0 && $total_imgs >= $count_limit) {
					break;
				}*/
				
				$id = (int)$id;
				$file = new stdClass();
				
				if ($id > 0 && array_key_exists($id, $existing_files)) {
					$file = clone $existing_files[$id];
				} 
				
				$file->id = (int)$id;
				$file->item_id = $itemid;
				$file->type = $itemtype;
				
				if (!empty($names[$key]) && !isset($file->fullname)) {
					$file->fullname = $names[$key];
				} else if (!isset($file->fullname)) {
					$file->fullname = '';
				}
				
				$file->caption = (!empty($captions[$key])) ? $captions[$key] : null;
				$file->exclude = (!empty($excludes[$key])) ? $excludes[$key] : 0;
				$file->ordering = $ordering++;
				$file->_uploaded = 0;				
				
				if ($id > 0) {
					$update_ids[] = $id;
				} else {
					$file->fullname = (!empty($names[$key])) ? $names[$key] : '';
					$file->path = null;
					$file->fullpath = null;
					$file->name = null;
					$file->ext = null;
					$file->_temp_name = JPATH_ROOT.DS.'tmp'.DS.'djc2upload'.DS.$file->fullname;
					
					$file->_uploaded = 1;
				}
				
				$files[] = $file;
				$total_imgs++;
			}
		}
		
		// fetch files from POST
		$post_files = $app->input->files->get($prefix.'file_upload', array());
		
		foreach ($post_files as $key => $post_file) {
			if (!empty($post_file['name']) && !empty($post_file['tmp_name']) && $post_file['error'] == 0 && $post_file['size'] > 0) {
				$file = new stdClass();
				
				$file->id = 0;
				$file->item_id = $itemid;
				$file->type = $itemtype;
				$file->fullname = $post_file['name'];
				$file->caption 	= JFile::stripExt($post_file['name']);
				$file->ordering = $ordering++;
				$file->exclude = 0;
				
				$file->path = null;
				$file->fullpath = null;
				$file->name = null;
				$file->ext = null;
				
				$file->_temp_name = $post_file['tmp_name'];
				$file->_uploaded = -1;
				
				$files[] = $file;
				$total_imgs++;
			}
		}

		// delete files, unless saveToCopy action is performed
		if (!$isNew && $app->input->get('task') != 'import') {
			
			$condition = 'WHERE item_id='.(int)$itemid.' AND type='.$db->quote($itemtype);
			if (count($update_ids) > 0) {
				JArrayHelper::toInteger($update_ids);

				$condition .= ' AND id NOT IN ('.implode(',', $update_ids).')';
			}
			
			$db->setQuery('SELECT id, fullname, path, fullpath FROM #__djc2_images '.$condition);
			$files_to_delete = $db->loadObjectList();
			
			foreach ($files_to_delete as $row) {
				$dir = DJCATIMGFOLDER.DS.str_replace('/', DS, $row->path);
				$path = $dir.DS.$row->fullname;
			
				if (!JFile::delete($path)) {
					JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_DELETE_ERROR'), JLog::WARNING, 'jerror');
				}
				if (JFile::exists($dir.DS.self::addSuffix($row->fullname, '_s'))) {
					JFile::delete($dir.DS.self::addSuffix($row->fullname, '_s'));
				}
				if (JFile::exists($dir.DS.self::addSuffix($row->fullname, '_f'))) {
					JFile::delete($dir.DS.self::addSuffix($row->fullname, '_f'));
				}
				if (JFile::exists($dir.DS.self::addSuffix($row->fullname, '_t'))) {
					JFile::delete($dir.DS.self::addSuffix($row->fullname, '_t'));
				}
				if (JFile::exists($dir.DS.self::addSuffix($row->fullname, '_m'))) {
					JFile::delete($dir.DS.self::addSuffix($row->fullname, '_m'));
				}
				if (JFile::exists($dir.DS.self::addSuffix($row->fullname, '_l'))) {
					JFile::delete($dir.DS.self::addSuffix($row->fullname, '_l'));
				}
			}
			
			$db->setQuery('DELETE FROM #__djc2_images '.$condition);
			$db->query();
		}
		
		// update existing files and move new ones from temporary
		
		$gd_info = gd_info();
		
		if (count($files)) {
			if ($count_limit >= 0) {
				$files = array_slice($files, 0, $count_limit);
			}
			
			foreach($files as $k => &$file) {
				$copy = (bool)($isNew && $file->id > 0);
				
				if ($copy) {
					$source = (empty($file->path)) ? DJCATIMGFOLDER : DJCATIMGFOLDER.DS.str_replace('/',DS,$file->path);
					$source .= DS.$file->fullname;

					$file->id = 0;
					$file->fullname = self::createFileName($file->fullname, $destination);
					
					if (!JFile::copy($source, $destination.DS.$file->fullname)) {
						JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_COPY_ERROR'), JLog::WARNING, 'jerror');
						unset($files[$k]);
						continue;
					}
					
					$file->name = self::stripExtension($file->fullname);
					
					if (empty($file->caption)) {
						$file->caption = $file->name;
					}
					
					$file->ext = self::getExtension($file->fullname);
					$file->path = $sub_path;
					$file->fullpath = $sub_path.'/'.$file->fullname;
					
				} else if (empty($file->id) && !$copy) {
					$tmp_name = $file->fullname;
					$realname = empty($captions[$k]) ? $tmp_name : $captions[$k];
					
					$source = $file->_temp_name;
					
					unset($file->_temp_name);
					
					$newname = JString::substr($item->alias, 0, 200).'.'.self::getExtension($file->fullname);
					$file->fullname = self::createFileName($newname, $destination);
					
					$imgAttrs = getimagesize($source);
					
					if (in_array('gif', $whitelist) && $imgAttrs[2] == 1 && array_key_exists('GIF Create Support',$gd_info) && $gd_info['GIF Create Support'] == 1) {
						$file->ext = 'gif';
					} else if (in_array('jpg', $whitelist) && $imgAttrs[2] == 2 && ((array_key_exists('JPEG Support',$gd_info) && $gd_info['JPEG Support'] == 1) || (array_key_exists('JPG Support',$gd_info) && $gd_info['JPG Support'] == 1))) {
						$file->ext = 'jpg';
					} else if (in_array('png', $whitelist) && $imgAttrs[2] == 3 && array_key_exists('PNG Support',$gd_info) && $gd_info['PNG Support'] == 1) {
						$file->ext = 'png';
					} else {
						$app->enqueueMessage(JText::sprintf('COM_DJCATALOG2_IMAGE_WRONG_TYPE', $realname), 'error');
						JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_WRONG_TYPE'), JLog::WARNING, 'jerror');
						continue;
					}
					
					if (filesize($source) > $size_limit && $size_limit) {
						$app->enqueueMessage(JText::sprintf('COM_DJCATALOG2_FILE_IS_TOO_BIG', $realname), 'error');
						unset($files[$k]);
						continue;
					}
					
					if ($file->_uploaded === 1) {
						if (!JFile::copy($source, $destination.DS.$file->fullname)) {
							JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_COPY_ERROR'), JLog::WARNING, 'jerror');
							unset($files[$k]);
							continue;
						}
					} else if ($file->_uploaded === -1) {
						if (!JFile::upload($source, $destination.DS.$file->fullname)) {
							JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_COPY_ERROR'), JLog::WARNING, 'jerror');
							unset($files[$k]);
							continue;
						}
					} else {
						unset($files[$k]);
						continue;
					}
					
					unset($file->_uploaded);
					
					$file->name = self::stripExtension($file->fullname);
					$file->ext = self::getExtension($file->fullname);
					$file->path = $sub_path;
					$file->fullpath = $sub_path.'/'.$file->fullname;
				}
			}
			unset($file);
		}
		
		// update DB & process
		
		foreach ($files as $k=>$v) {
			$ret = false;
			if ($v->id) {
				$ret = $db->updateObject( '#__djc2_images', $v, 'id', false);
			} else {
				$ret = $db->insertObject( '#__djc2_images', $v, 'id');
			}
			if( !$ret ){
				unset($files[$k]);
				JLog::add(JText::_('COM_DJCATALOG2_IMAGE_STORE_ERROR').$db->getErrorMsg(), JLog::WARNING, 'jerror');
				continue;
			}
			
			self::processImage($destination, $v->fullname, $itemtype, $params);
		}
		return true;
	}
	
	
	public static function saveImagesPlain($itemtype, $item, &$params, $isNew) {
	//public static function saveImages($itemtype, $item, &$params, $isNew) {
		if (!$itemtype || !$item || empty($params)) {
			return false;
		}
		
		$itemid = $item->id;
		if (!($itemid) > 0) {
			return false;
		}
		
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		
		$count_limit = $app->isAdmin() ? -1 : (int)$params->get('fed_max_images', 6);
		$total_imgs = 0;
		
		// given in KB
		$size_limit = $app->isAdmin() ? 0 : (int)$params->get('fed_max_image_size', 2048);
		
		// given in Bytes
		$size_limit *= 1024;
		
		$whitelist = explode(',', $params->get('allowed_image_types', 'jpg,png,gif'));
		foreach($whitelist as $key => $extension) {
			$whitelist[$key] = strtolower(trim($extension));
		}
		
		$image_id = $app->input->get('image_id_'.$itemtype, array(),'array');
		$caption = $app->input->get('caption_'.$itemtype, array(),'array');
		$delete = $app->input->get('delete_'.$itemtype, array(),'array');
		$order = $app->input->get('order_'.$itemtype, array(),'array');
		$files = $app->input->files;
		
		$multiple_images = array();
		$multiple_upload_count = $app->input->get('multiuploader_'.$itemtype.'_count', 0, 'int');

		if ($multiple_upload_count > 0) {
			for ($mi = 0; $mi < $multiple_upload_count; $mi++) {
				$mi_row = array();
				if ($app->input->get('multiuploader_'.$itemtype.'_'.$mi.'_status', '', 'string') == 'done') {
					$mi_row['tmp_name'] = $app->input->get('multiuploader_'.$itemtype.'_'.$mi.'_tmpname', '', 'string');
					if (!empty($mi_row['tmp_name'])) {
						$mi_row['tmp_name'] = JPATH_ROOT.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'djc2upload'.DIRECTORY_SEPARATOR.$mi_row['tmp_name'];
						$mi_row['name'] = $app->input->get('multiuploader_'.$itemtype.'_'.$mi.'_name', '', 'string');
						$mi_row['error'] = 0;
						$mi_row['size'] = filesize($mi_row['tmp_name']);
						$mi_row['multiupload'] = true;
						$multiple_images[] = $mi_row;
					}
				}
			}
		}
		
		$images_to_update = array();
		$images_to_save = array();
		$images_to_copy = array();

		$orderingCounter = 0;


		//delete files
		if (count($delete) && !$isNew) {
			$cids = implode(',', array_keys($delete));
			$db->setQuery('SELECT id, fullname, path, fullpath FROM #__djc2_images WHERE id IN ('.$cids.')');
			$images_to_delete = $db->loadObjectList();
			foreach ($images_to_delete as $row) {
				$image_dir = DJCATIMGFOLDER.DS.str_replace('/', DS, $row->path);
				$image_path = $image_dir.DS.$row->fullname;
				
				if (JFile::exists($image_path)) {
					if (!JFile::delete($image_path)) {
						JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_DELETE_ERROR'), JLog::WARNING, 'jerror');
						unset($delete[$row->id]);
					} else {
						if (JFile::exists($image_dir.DS.self::addSuffix($row->fullname, '_s'))) {
							JFile::delete($image_dir.DS.self::addSuffix($row->fullname, '_s'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($row->fullname, '_f'))) {
							JFile::delete($image_dir.DS.self::addSuffix($row->fullname, '_f'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($row->fullname, '_t'))) {
							JFile::delete($image_dir.DS.self::addSuffix($row->fullname, '_t'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($row->fullname, '_m'))) {
							JFile::delete($image_dir.DS.self::addSuffix($row->fullname, '_m'));
						}
						if (JFile::exists($image_dir.DS.self::addSuffix($row->fullname, '_l'))) {
							JFile::delete($image_dir.DS.self::addSuffix($row->fullname, '_l'));
						}
					}
				}
			}
			$cids = implode(',', array_keys($delete));
			$db->setQuery('DELETE FROM #__djc2_images WHERE id IN ('.$cids.')');
			$db->query();
			foreach ($delete as $key => $value) {
				if ($value == 1) {
					$idx = array_search($key, $image_id);
					if (array_key_exists($idx, $image_id)) {
						unset($image_id[$idx]);
					}
				}
			}
		}

		// fetch images that need to be updated/copied to the new item
		if (count($image_id)) {
			JArrayHelper::toInteger($image_id);
			$ids = implode(',', $image_id);
			$db->setQuery('SELECT * FROM #__djc2_images WHERE id IN ('.$ids.') ORDER BY ordering ASC, name ASC');
			$images = $db->loadObjectList();
			foreach ($image_id as $key) {
				foreach ($images as $image) {
					if ($image->id == $key && !array_key_exists($key, $delete)) {
						$obj = array();
						$obj['id'] = ($isNew) ? null:$key;
						if (isset($caption[$key])) {
							$obj['caption'] = $caption[$key];
						} else {
							$obj['caption'] = '';
						}
						if (isset($order[$key])) {
							$obj['ordering'] = intval($order[$key]);
						} else {
							$obj['ordering'] = $image->ordering;
						}
						$obj['name'] = $image->name;
						$obj['fullname'] = $image->fullname;
						$obj['ext'] = $image->ext;
						$obj['item_id'] = $itemid;
						$obj['type'] = $itemtype;
						$obj['path'] = $image->path;
						$obj['fullpath'] = $image->fullpath;

						if ($obj['id']) {
							$images_to_update[] = $obj;
							$total_imgs++;
						} else {
							$images_to_copy[] = $obj;
						}
					}
				}
			}
			usort($images_to_update, array('DJCatalog2ImageHelper', 'setOrdering'));
		}

		$destExist = false;
		$destination = self::getDestinationFolder(DJCATIMGFOLDER, $itemid, $itemtype);
		$sub_path = self::getDestinationPath($itemid, $itemtype);
		if (!JFolder::exists($destination)) {
			$destExist = JFolder::create($destination, 0755);
		} else {
			$destExist = true;
		}

		if ($destExist) {
			// copy images
			if (count($images_to_copy)) {
				foreach ($images_to_copy as $key => $copyme) {
					$source = (empty($copyme['path'])) ? DJCATIMGFOLDER : DJCATIMGFOLDER.DS.str_replace('/',DS,$copyme['path']);
					
					$new_file_name = self::createFileName($copyme['fullname'], $destination);
					if (!JFile::copy($source.DS.$copyme['fullname'], $destination.DS.$new_file_name)) {
						JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_COPY_ERROR'), JLog::WARNING, 'jerror');
						unset($images_to_copy[$key]);
					} else {
						$images_to_copy[$key]['fullname'] = $new_file_name;
						$images_to_copy[$key]['name'] = self::stripExtension($new_file_name);
						$images_to_copy[$key]['ext'] = self::getExtension($new_file_name);
						$images_to_copy[$key]['path'] = $sub_path;
						$images_to_copy[$key]['fullpath'] = $sub_path.'/'.$new_file_name;
					}
				}
			}
			
			// save uploaded files
			$file_caption = $app->input->get('file_caption_'.$itemtype,array(),'array');
			$file_arr = $files->get('file_'.$itemtype, array());
			$file_arr = array_merge($file_arr, $multiple_images);

			if(!empty($file_arr)) {
				$gd_info = gd_info();
				foreach ($file_arr as $key => $file) {
					if (!empty($file['name']) && !empty($file['tmp_name']) && $file['error'] == 0) {
						$name = $file['name'];
						$imgAttrs = getimagesize($file['tmp_name']);
						$obj = array();
						$obj['id'] = null;
						if (in_array('gif', $whitelist) && $imgAttrs[2] == 1 && array_key_exists('GIF Create Support',$gd_info) && $gd_info['GIF Create Support'] == 1) {
							$obj['ext'] = 'gif';
						} else if (in_array('jpg', $whitelist) && $imgAttrs[2] == 2 && ((array_key_exists('JPEG Support',$gd_info) && $gd_info['JPEG Support'] == 1) || (array_key_exists('JPG Support',$gd_info) && $gd_info['JPG Support'] == 1))) {
							$obj['ext'] = 'jpg';
						} else if (in_array('png', $whitelist) && $imgAttrs[2] == 3 && array_key_exists('PNG Support',$gd_info) && $gd_info['PNG Support'] == 1) {
							$obj['ext'] = 'png';
						} else {
							$app->enqueueMessage(JText::sprintf('COM_DJCATALOG2_IMAGE_WRONG_TYPE', $name), 'error');
							JLog::add(JText::_('COM_DJCATALOG2_IMAGE_FILE_WRONG_TYPE'), JLog::WARNING, 'jerror');
							continue;
						}
						
						if ($file['size'] > $size_limit && $size_limit) {
							$app->enqueueMessage(JText::sprintf('COM_DJCATALOG2_FILE_IS_TOO_BIG', $name), 'error');
							continue;
						}
						
						if ($count_limit >= 0 && $total_imgs >= $count_limit) {
							continue;
						}
						
						$newname = substr($item->alias, 0, 200).'.'.self::getExtension($name);
						$obj['fullname'] = self::createFileName($newname, $destination);
						$obj['ordering'] = 0;
						$obj['name'] = self::stripExtension($obj['fullname']);
						$obj['item_id'] = $itemid;
						$obj['type'] = $itemtype;
						$obj['path'] = $sub_path;
						$obj['fullpath'] = $sub_path.'/'.$obj['fullname'];
						if (isset($file_caption[$key]) && $file_caption[$key] != '') {
							$obj['caption'] = $file_caption[$key];
						} else {
							$obj['caption'] = $obj['name'];
						}
						
						if (isset($file['multiupload']) && $file['multiupload']) {
							JFile::move($file['tmp_name'], $destination.DS.$obj['fullname']);
							$images_to_save[] = $obj;
							$total_imgs++;
						}
						else if (JFile::upload($file['tmp_name'], $destination.DS.$obj['fullname'])) {
							$images_to_save[] = $obj;
							$total_imgs++;
						}
						else {
							JLog::add(JText::_('COM_DJCATALOG2_IMAGE_UPLOAD_ERROR'), JLog::WARNING, 'jerror');
						}
					}
				}
			}
		}

		// order images
		$ordering = 1;
		foreach ($images_to_update as $k=>$v) {
			$images_to_update[$k]['ordering'] = $ordering++;
			$obj = new stdClass();
			foreach ($images_to_update[$k] as $key=>$data) {
				$obj->$key = $data;
			}
			if ($isNew) {
				$ret = $db->insertObject( '#__djc2_images', $obj, 'id');
			} else {
				$ret = $db->updateObject( '#__djc2_images', $obj, 'id', false);
			}
			if( !$ret ){
				JLog::add(JText::_('COM_DJCATALOG2_IMAGE_STORE_ERROR').$db->getErrorMsg(), JLog::WARNING, 'jerror');
				continue;
			}
		}

		$images_to_process = array_merge($images_to_copy, $images_to_save);
		foreach ($images_to_process as $k=>$v) {
			$images_to_process[$k]['ordering'] = $ordering++;
			$obj = new stdClass();
			foreach ($images_to_process[$k] as $key=>$data) {
				$obj->$key = $data;
			}
			$ret = $db->insertObject( '#__djc2_images', $obj, 'id');
			if( !$ret ){
				unset($images_to_process[$k]);
				JLog::add(JText::_('COM_DJCATALOG2_IMAGE_STORE_ERROR').$db->getErrorMsg(), JLog::WARNING, 'jerror');
				continue;
			}
			self::processImage($destination, $v['fullname'], $itemtype, $params);
		}
		return true;
	}

	public static function createFileName($filename, $path, $ext = null) {
		$lang = JFactory::getLanguage();
		
		$hash = md5($filename);
		
		$namepart = self::stripExtension($filename);
		$extpart = ($ext) ? $ext : self::getExtension($filename);
		
		$namepart = $lang->transliterate($namepart);
		$namepart = strtolower($namepart);
		$namepart = JFile::makeSafe($namepart);
		$namepart = str_replace(' ', '_', $namepart);
		
		if ($namepart == '') {
			$namepart = $hash;
		}
		
		$filename = $namepart.'.'.$extpart;
		
		if (JFile::exists($path.DS.$filename)) {
			if (is_numeric(self::getExtension($namepart)) && count(explode(".", $namepart))>1) {
				$namepart = self::stripExtension($namepart);
			}
			$iterator = 1;
			$newname = $namepart.'.'.$iterator.'.'.$extpart;
			while (JFile::exists($path.DS.$newname)) {
				$iterator++;
				$newname = $namepart.'.'.$iterator.'.'.$extpart;
			}
			$filename = $newname;
		}

		return $filename;
	}

	public static function processImage($path, $filename, $itemtype, &$params) {
		$resize = intval($params->get($itemtype.'_resize', $params->get('resize', 0)));

		$width = $params->get($itemtype.'_width', $params->get('width', 300));
		$height = $params->get($itemtype.'_height', $params->get('height', 300));

		//$fp_width = $params->get($itemtype.'_fp_width', $params->get('fp_width', 300));
		//$fp_height = $params->get($itemtype.'_fp_height', $params->get('fp_height', 300));

		$medium_width = $params->get($itemtype.'_th_width', $params->get('th_width', 120));
		$medium_height = $params->get($itemtype.'_th_height', $params->get('th_height', 120));

		$small_width = $params->get($itemtype.'_smallth_width', $params->get('smallth_width', 92));
		$small_height = $params->get($itemtype.'_smallth_height', $params->get('smallth_height', 92));


		if (JFile::exists($path.DS.self::addSuffix($filename, '_s'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_s'));
		}
		if (JFile::exists($path.DS.self::addSuffix($filename, '_f'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_f'));
		}
		self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_s'), 75, 45, true);

		self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_f'), 1920, 1920, true, false);

		if (JFile::exists($path.DS.self::addSuffix($filename, '_t'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_t'));
		}
		if (JFile::exists($path.DS.self::addSuffix($filename, '_m'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_m'));
		}
		if (JFile::exists($path.DS.self::addSuffix($filename, '_l'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_l'));
		}
		/*if (JFile::exists($path.DS.self::addSuffix($filename, '_fp'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_fp'));
		}*/

		switch ($resize) {
			case 1: {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), $width, 0);
				//self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), $fp_width, 0);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, 0);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, 0);
				break;
			}

			case 2 : {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), 0, $height);
				//self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), 0, $fp_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), 0, $medium_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), 0, $small_height);
				break;
			}

			case 3 : {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), $width, $height);
				//self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), $fp_width, $fp_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, $medium_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, $small_height);
				break;
			}

			case 0 :
			default: {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), $width, $height, true);
				//self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), $fp_width, $fp_height, true);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, $medium_height, true);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, $small_height, true);

				break;
			}

			/*case 0:
			 default: {
			 JFile::copy($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'));
			 self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, 0);
			 self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, 0);
			 break;
			 }*/
		}

		return true;
	}

	public static function resizeImage($path, $newpath, $nw = 0, $nh = 0, $keep_ratio = false, $enlarge = true) {

		$params = JComponentHelper::getParams( 'com_djcatalog2' );

		if (!$path || !$newpath)
		return false;
		
		if (!JFile::exists($path)) {
			return false;
		}
		
		$w = $h = $type = $attr = null;
		if (! list ($w, $h, $type, $attr) = getimagesize($path)) {
			return false;
		}

		$OldImage = null;

		switch($type)
		{
			case 1:
				$OldImage = imagecreatefromgif($path);
				break;
			case 2:
				$OldImage = imagecreatefromjpeg($path);
				break;
			case 3:
				$OldImage = imagecreatefrompng($path);
				break;
			default:
				return  false;
				break;
		}

		if ($nw == 0 && $nh == 0) {
			$nw = 75;
			$nh = (int)(floor(($nw * $h) / $w));
		}
		elseif ($nw == 0) {
			$nw = (int)(floor(($nh * $w) / $h));
		}
		elseif ($nh == 0) {
			$nh = (int)(floor(($nw * $h) / $w));
		}
		if ($keep_ratio) {
			$x_ratio = $nw / $w;
			$y_ratio = $nh / $h;

			if (($x_ratio * $h) < $nh){
				$nh = floor($x_ratio * $h);
			}else{
				$nw = floor($y_ratio * $w);
			}
		}

		if ( ($nw > $w || $nh > $h) && !$enlarge) {
			$nw = $w;
			$nh = $h;
		}

		// check if ratios match
		$_ratio=array($w/$h, $nw/$nh);
		if ($_ratio[0] != $_ratio[1]) { // crop image

			// find the right scale to use
			$_scale=min((float)($w/$nw),(float)($h/$nh));

			// coords to crop
			$cropX=(float)($w-($_scale*$nw));
			$cropY=(float)($h-($_scale*$nh));

			// cropped image size
			$cropW=(int)($w-$cropX);
			$cropH=(int)($h-$cropY);

			$crop = null;
				
			/*if ($type == 2 || $type == 3) {
				$crop = imagecreatetruecolor($cropW,$cropH);
			} else {
				$crop = imagecreate($cropW,$cropH);
			}*/
			
			$crop = imagecreatetruecolor($cropW,$cropH);
				
			if ($type == 3 || $type == 1) {
				$bg = imagecolortransparent($crop, imagecolorallocatealpha($crop, 0, 0, 0, 127));
				imagealphablending($crop, false);
				imagefill($crop, 0, 0, $bg);
				imagesavealpha($crop, true);
			} /*else if ($type == 1) {
				$bg = imagecolortransparent($crop, imagecolorallocate($crop, 0, 0, 0));
				imagefill($crop, 0, 0, $bg);
			} */else {
				$bg = imagecolorallocate($crop, 255, 255, 255);
				imagefill($crop, 0, 0, $bg);
			}
				
				
			$cropCoeffsX = array('l' => 0, 'm' => 0.5, 'r' => 1);
			$cropCoeffsY = array('t' => 0, 'm' => 0.5, 'b' => 1);
				
			$cropAlignmentX = $params->get('crop_alignment_h', 'm');
			$cropAlignmentY = $params->get('crop_alignment_v', 'm');
				
			if (!array_key_exists($cropAlignmentX, $cropCoeffsX)) {
				$cropAlignmentX = 'm';
			}
				
			if (!array_key_exists($cropAlignmentY, $cropCoeffsY)) {
				$cropAlignmentY = 'm';
			}
			/*
			 imagecopyresampled(
				$crop,
				$OldImage,
				0,
				0,
				(int)($cropX * $cropCoeffsX[$cropAlignmentX]),
				(int)($cropY * $cropCoeffsY[$cropAlignmentY]),
				$cropW,
				$cropH,
				$cropW,
				$cropH
				);
				*/
				
			imagecopy($crop, $OldImage, 0, 0, (int)($cropX * $cropCoeffsX[$cropAlignmentX]), (int)($cropY * $cropCoeffsY[$cropAlignmentY]), $cropW, $cropH);
		}

		// do the thumbnail
		$NewThumb = imagecreatetruecolor($nw,$nh);

		if ($type == 3 || $type == 1) {
			$bg = imagecolortransparent($NewThumb, imagecolorallocatealpha($NewThumb, 0, 0, 0, 127));
			imagealphablending($NewThumb, false);
			imagefill($NewThumb, 0, 0, $bg);
			imagesavealpha($NewThumb, true);
		} /*else if ($type == 1) {
			$bg = imagecolortransparent($NewThumb, imagecolorallocate($NewThumb, 0, 0, 0));
			imagefill($NewThumb, 0, 0, $bg);
		} */else {
			$bg = imagecolorallocate($NewThumb, 255, 255, 255);
			imagefill($NewThumb, 0, 0, $bg);
		}

		if (isset($crop)) { // been cropped
			imagecopyresampled($NewThumb, $crop, 0, 0, 0, 0, $nw, $nh, $cropW, $cropH);
			imagedestroy($crop);
		} else { // ratio match, regular resize
			imagecopyresampled($NewThumb, $OldImage, 0, 0, 0, 0, $nw, $nh, $w, $h);
		}

		$thumb_path = $newpath;

		if (is_file($thumb_path)) {
			unlink($thumb_path);
		}
		
		$jpg_quality = (int)$params->get('image_jpg_quality', 85);
		$jpg_quality = min(array(100, max(array(1, $jpg_quality))));

		switch($type)
		{
			case 1:
				imageinterlace($NewThumb, 1);
				imagegif($NewThumb, $thumb_path);
				break;
			case 2:
				imageinterlace($NewThumb, 1);
				imagejpeg($NewThumb, $thumb_path, $jpg_quality);
				break;
			case 3:
				imageinterlace($NewThumb, 1);
				imagepng($NewThumb, $thumb_path);
				break;
		}

		imagedestroy($NewThumb);
		imagedestroy($OldImage);

		return true;
	}
	
	public static function getProcessedImage($fullname, $width=0, $height=0, $keep_ratio = true, $path = '') {
		if (!($width > 0 || $height > 0)) {
			return false;
		}
		$suffix = '_'.(int)$width.'x'.(int)$height.'-'.(($keep_ratio) ? 'r' : 'c');
		
		$sub_path = $path != '' ? str_replace('/', DS, $path).DS : '';
		$path = $path != '' ? '/'.$path : '';
		$imgPath	= DJCATIMGFOLDER.DS.$sub_path.$fullname;
		$thPath		= DJCATIMGFOLDER.DS.$sub_path.'custom'.DS.self::addSuffix($fullname, $suffix);
		$thUrl		= DJCATIMGURLPATH.$path.'/custom/'.self::addSuffix($fullname, $suffix);
		
		if (!JFolder::exists( DJCATIMGFOLDER.DS.$sub_path.'custom')) {
			JFolder::create( DJCATIMGFOLDER.DS.$sub_path.'custom', 0755);
		}
		
		if (!JFile::exists($thPath) && JFile::exists($imgPath)) {
			$customFolderExist = false;
			if (!JFolder::exists( DJCATIMGFOLDER.DS.$sub_path.'custom')) {
				$customFolderExist = JFolder::create( DJCATIMGFOLDER.DS.$sub_path.'custom', 0755);
			} else {
				$customFolderExist = true;
			}
			if ($customFolderExist) {
				if (!self::resizeImage($imgPath, $thPath, $width, $height, $keep_ratio)) {
					return false;
				}
			}
		}
		if (!JFile::exists($thPath)) {
			return false;
		}
		return $thUrl;
	}

	protected static function stripExtension($filename) {
		return parent::stripExtension($filename);
	}

	protected static function getExtension($filename) {
		return parent::getExtension($filename);
	}

	protected static function addSuffix($filename, $suffix) {
		return parent::addSuffix($filename, $suffix);
	}
	public static function setOrdering($file1, $file2){
		return parent::setOrdering($file1, $file2);
	}
	public static function getDestinationFolder($path, $itemid, $itemtype) {
		return parent::getDestinationFolder($path, $itemid, $itemtype);
	}
	public static function getDestinationPath($itemid, $itemtype){
		return parent::getDestinationPath($itemid, $itemtype);
	}
}