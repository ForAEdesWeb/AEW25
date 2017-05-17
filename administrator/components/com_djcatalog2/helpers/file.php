<?php
/**
 * @version $Id: file.php 342 2014-08-21 14:17:37Z michal $
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

class DJCatalog2FileHelper extends JObject {
	
	static $plUpload_scripts_included = false; 
	
	public static function renderInput($itemtype, $itemid=null, $multiple_upload = false) {
		if (!$itemtype) {
			return false;
		}
		
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		
		$count_limit = $app->isAdmin() ? -1 : (int)$params->get('fed_max_files', 6);
		$total_files = 0;
		
		$whitelist = explode(',', $params->get('allowed_attachment_types', 'jpg,png,bmp,gif,pdf,tif,tiff,txt,csv,doc,docx,xls,xlsx,xlt,pps,ppt,pptx,ods,odp,odt,rar,zip,tar,bz2,gz2,7z'));
		foreach($whitelist as $key => $extension) {
			$whitelist[$key] = strtolower(trim($extension));
		}
		
		// KBs
		$size_limit = $app->isAdmin() ? 0 : (int)$params->get('fed_max_file_size', 2048);
		
		$files = array();
		if ($itemid) {
			$db->setQuery('SELECT * '.
						' FROM #__djc2_files '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
			$files = $db->loadObjectList();
		}
		
		$record_type = 'file';
		
		return self::getUploader($record_type, $itemtype, $itemid, $count_limit, $size_limit, $whitelist, $files, $multiple_upload);
	}
	public static function getFiles($itemtype, $itemid) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$db = JFactory::getDbo();
		$atts = array();
		$db->setQuery('SELECT * '.
						' FROM #__djc2_files '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
		$atts = $db->loadObjectList();

		if (count($atts)) {
			foreach ( $atts as $key=>$att) {
				$path = (empty($att->path)) ? DJCATATTFOLDER : DJCATATTFOLDER.DS.str_replace('/', DS, $att->path);
				if (JFile::exists($path.DS.$att->fullname)) {
					$atts[$key]->size = self::formatBytes(filesize($path.DS.$att->fullname));
				} else {
					unset($atts[$key]);
				}
			}
		}

		return $atts;

	}
	public static function getFile($fileid) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT * '.
						' FROM #__djc2_files '.
						' WHERE id='.intval($fileid));
		$file=$db->loadObject();

		$path = (empty($file->path)) ? DJCATATTFOLDER : DJCATATTFOLDER.DS.str_replace('/', DS, $file->path);
		$filename = $path.DS.$file->fullname;
		
		if ($file && JFile::exists($filename)) {
				// hit file
				$db->setQuery('UPDATE #__djc2_files SET hits='.($file->hits+1).' WHERE id='.$fileid);
				$db->query();
				
				$attachment_name = '';
				if (!empty($file->caption)) {
					$attachment_name = $file->caption;
					if (!empty($file->ext)) {
						$attachment_name .= '.'.$file->ext;
					}
				}
				
				return self::getFileByPath($filename, $attachment_name);
		} else {
			return false;
		}
	}
	public static function getFileByPath($filename, $caption = null) {
		if (!JFile::exists($filename)) {
			return false;
		}
		$document = JFactory::getDocument();
		$filesize = filesize($filename);
		/*if ($filesize === 0) {
			return false;
		}*/
		$parts = pathinfo($filename);
		$ext = strtolower($parts["extension"]);
		//ob_start();
		
		// Required for some browsers
		if(ini_get('zlib.output_compression'))
			ini_set('zlib.output_compression', 'Off');
		
		// Determine Content Type
		switch ($ext) {
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			case "txt": $ctype="text/plain"; break;
			case "csv": $ctype="text/csv"; break;

			default: $ctype="application/force-download";
		}

		$document->setMimeEncoding($ctype);
		
		$attachment_name = (!empty($caption)) ? $caption : $parts["basename"];

		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header("Content-Type: ".$ctype);
		header("Content-Disposition: filename=\"".$attachment_name."\";" );
		//header("Content-Disposition: attachment; filename=\"".$parts["basename"]."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$filesize);
		
		return self::readFileChunked($filename);
	}
	private static function readFileChunked($filename, $retbytes = true) {
        $chunksize = 1024*1024;
        $buffer = '';
        $cnt = 0;
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt;
        }
        return $status;
    }
	public static function deleteFiles($itemtype, $itemid) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$db = JFactory::getDbo();
		$atts = array();
		$db->setQuery('SELECT id, fullname, path, fullpath '.
						' FROM #__djc2_files '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
		$atts = $db->loadObjectList();

		$atts_to_remove = array();
		if (count($atts)) {
			foreach ($atts as $key=>$attachment) {
				$path = (empty($attachment->path)) ? DJCATATTFOLDER : DJCATATTFOLDER.DS.str_replace('/', DS, $attachment->path);
				if (JFile::exists($path.DS.$attachment->fullname)) {
					if (JFile::delete($path.DS.$attachment->fullname)) {
						$atts_to_remove[] = $attachment->id;
					}
				}
			}
		}
		if (count($atts_to_remove)) {
			JArrayHelper::toInteger($atts_to_remove);
			$ids = implode(',',$atts_to_remove);
			$db->setQuery('DELETE FROM #__djc2_files WHERE id IN ('.$ids.')');
			$db->query();
		}

		return true;

	}
	
	public static function saveFiles($itemtype, $item, &$params, $isNew) {
		if (!$itemtype || !$item || empty($params)) {
			return false;
		}
	
		$itemid = $item->id;
		if (!($itemid) > 0) {
			return false;
		}
	
		$prefix = $suffix = 'file_';//.$itemtype.'_';
	
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
	
		$count_limit = $app->isAdmin() ? -1 : (int)$params->get('fed_max_files', 6);
		$total_imgs = 0;
	
		// given in KB
		$size_limit = $app->isAdmin() ? 0 : (int)$params->get('fed_max_file_size', 2048);
	
		// given in Bytes
		$size_limit *= 1024;
	
		$whitelist = explode(',', $params->get('allowed_attachment_types', 'jpg,png,bmp,gif,pdf,tif,tiff,txt,csv,doc,docx,xls,xlsx,xlt,pps,ppt,pptx,ods,odp,odt,rar,zip,tar,bz2,gz2,7z'));
		foreach($whitelist as $key => $extension) {
			$whitelist[$key] = strtolower(trim($extension));
		}
	
		$ids = $app->input->get($prefix.'file_id', array(),'array');
		$names = $app->input->get($prefix.'file_name', array(),'array');
		$captions = $app->input->get($prefix.'caption', array(),'array');
		$hits = $app->input->get($prefix.'hits', array(),'array');
	
		$files_to_update = array();
		$files_to_save = array();
	
		$update_ids = array();
		$files = array();
	
		$destination = self::getDestinationFolder(DJCATATTFOLDER, $itemid, $itemtype);
		$sub_path = self::getDestinationPath($itemid, $itemtype);
		if (!JFolder::exists($destination)) {
			$destExist = JFolder::create($destination, 0755);
		} else {
			$destExist = true;
		}
		
		$additional_ids = (count($ids) > 0) ? 'OR id IN ('.implode(',', $ids).')' : '';
		$db->setQuery('select * from #__djc2_files where type='.$db->quote($itemtype).' and (item_id='.(int)$itemid.' '.$additional_ids.')');
		$existing_files = $db->loadObjectList('id');
		
		$ordering = 1;
	
		if (!empty($ids)) {
			foreach($ids as $key => $id) {
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
				//$file->hits = (!empty($hits[$key])) ? $hits[$key] : 0;
				
				if (empty($file->hits)) {
					$file->hits = 0;
				}
				
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
				$file->hits = 0;
	
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
				
			$db->setQuery('SELECT id, fullname, path, fullpath FROM #__djc2_files '.$condition);
			$files_to_delete = $db->loadObjectList();
				
			foreach ($files_to_delete as $row) {
				$dir = DJCATATTFOLDER.DS.str_replace('/', DS, $row->path);
				$path = $dir.DS.$row->fullname;
					
				if (!JFile::delete($path)) {
					JLog::add(JText::_('COM_DJCATALOG2_FILE_DELETE_ERROR'), JLog::WARNING, 'jerror');
				}
			}
				
			$db->setQuery('DELETE FROM #__djc2_files '.$condition);
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
					$source = (empty($file->path)) ? DJCATATTFOLDER : DJCATATTFOLDER.DS.str_replace('/',DS,$file->path);
					$source .= DS.$file->fullname;
						
					$file->id = 0;
					$file->fullname = self::createFileName($file->fullname, $destination);
						
					if (!JFile::copy($source, $destination.DS.$file->fullname)) {
						JLog::add(JText::_('COM_DJCATALOG2_FILE_COPY_ERROR'), JLog::WARNING, 'jerror');
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
						
					//$files_to_save[] = $file;
						
				} else if (empty($file->id) && !$copy) {
						
					$tmp_name = $file->fullname;
					$realname = empty($captions[$k]) ? $tmp_name : $captions[$k];

					$source = $file->_temp_name;
						
					unset($file->_temp_name);
					$newname = JString::substr($realname.'_'.$item->alias, 0, 200).'.'.self::getExtension($file->fullname);
					//$newname = utf8_substr($item->alias, 0, 200).'.'.self::getExtension($file->fullname);
					$file->fullname = self::createFileName($newname, $destination);
						
					if (filesize($source) > $size_limit && $size_limit) {
						$app->enqueueMessage(JText::sprintf('COM_DJCATALOG2_FILE_IS_TOO_BIG', $realname), 'error');
						unset($files[$k]);
						continue;
					}
						
					if ($file->_uploaded === 1) {
						if (!JFile::copy($source, $destination.DS.$file->fullname)) {
							JLog::add(JText::_('COM_DJCATALOG2_FILE_COPY_ERROR'), JLog::WARNING, 'jerror');
							unset($files[$k]);
							continue;
						}
					} else if ($file->_uploaded === -1) {
						if (!JFile::upload($source, $destination.DS.$file->fullname)) {
							JLog::add(JText::_('COM_DJCATALOG2_FILE_COPY_ERROR'), JLog::WARNING, 'jerror');
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
				$ret = $db->updateObject( '#__djc2_files', $v, 'id', false);
			} else {
				$ret = $db->insertObject( '#__djc2_files', $v, 'id');
			}
			if( !$ret ){
				unset($files[$k]);
				JLog::add(JText::_('COM_DJCATALOG2_FILE_STORE_ERROR').$db->getErrorMsg(), JLog::WARNING, 'jerror');
				continue;
			}
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


	protected static function stripExtension($filename) {
		$fileParts = preg_split("/\./", $filename);
		$no = count($fileParts);
		if ($no > 0) {
			unset ($fileParts[$no-1]);
		}
		$filenoext = implode('.',$fileParts);
		return $filenoext;
	}

	protected static function getExtension($filename) {
		$arr = explode(".", $filename);
		$ext = end($arr);
		return $ext;
	}

	protected static function addSuffix($filename, $suffix) {
		return self::stripExtension($filename).$suffix.'.'.self::getExtension($filename);
	}
	public static function setOrdering($file1, $file2){
		return (int)($file1['ordering'] - $file2['ordering']);
	}
	public static function formatBytes($size) {
		$units = array(' B', ' KB', ' MB', ' GB', ' TB');
		for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
		return round($size, 2).$units[$i];
	}
	public static function getDestinationFolder($path, $itemid, $itemtype) {
		return $path.DS.str_replace('/', DS, self::getDestinationPath($itemid, $itemtype));
	}
	public static function getDestinationPath($itemid, $itemtype){
		$items_per_dir = 100;
		$directory = (string) floor($itemid / $items_per_dir);
	
		return $itemtype.'/'.$directory;
	}
	public static function getUploader($uploader_type, $record_type, $item_id, $limit, $size_limit, $whitelist, $files = array(), $multiple_upload) {
		
		$prefix = $suffix = $uploader_type;//.'_'.$record_type;
		
		$uploader_id 	= 'multiuploader_'.$suffix;
		$wrapper_id 	= 'djc_uploader_'.$suffix;
		$wrapper_class 	= 'djc_uploader_'.$uploader_type;
				
		$document = JFactory::getDocument();
		
		if (self::$plUpload_scripts_included == false) {
			$document->addScript(JURI::root(false).'components/com_djcatalog2/assets/upload/upload.js');
			
			$script_vars = array();
			$script_vars['url'] = JUri::root(false);
			$script_vars['client'] = JFactory::getApplication()->isAdmin() ? 1 : 0;
			$script_vars['lang'] = array();
			$script_vars['lang']['remove'] = JText::_('COM_DJCATALOG2_DELETE_BTN');
			$script_vars['lang']['limitreached'] = JText::_('COM_DJCATALOG2_UPLOADER_LIMIT_REACHED');
			
			$document->addScriptDeclaration('var DJCatalog2UploaderVars = '.json_encode($script_vars));
			
			self::$plUpload_scripts_included = true;
		}
		
		$app = JFactory::getApplication();
		
		$settings = array();
		$settings['max_file_size'] = ($size_limit > 0) ? $size_limit.'kb' : '102400kb';
		$settings['chunk_size'] = '1024kb';
		$settings['resize'] = true;
		$settings['width'] = '2880';
		$settings['height'] = '2880';
		$settings['quality'] = '90';
		$settings['filter'] = implode(',',$whitelist);
		
		$settings['onUploadedEvent'] = 'DJC2PlUploadInjectUploaded'.ucfirst($uploader_type);//.ucfirst($record_type);
		$settings['onAddedEvent'] = 'DJC2PlUploadStartUpload'.ucfirst($uploader_type);//.ucfirst($record_type);
		$settings['debug'] = false;
		
		$layoutFile = dirname(__FILE__).DS.'layouts'.DS.$uploader_type.'.php';
		
		if (JFile::exists($layoutFile) == false) {
			$layoutFile = dirname(__FILE__).DS.'layouts'.DS.'file.php';
		}
		
		ob_start();
		include $layoutFile;
		$layoutOutput = ob_get_contents();
		ob_end_clean();
		
		return $layoutOutput;
	}
}