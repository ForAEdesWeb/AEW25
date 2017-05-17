<?php
/**
 * @version $Id: categories.php 191 2013-11-03 07:15:27Z michal $
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
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');


class Djcatalog2ControllerCategories extends JControllerAdmin
{
	public function getModel($name = 'Category', $prefix = 'Djcatalog2Model', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function recreateThumbnails() {
		if (!JSession::checkToken('post') && !JSession::checkToken('get')) {
			jexit( 'COM_DJCATALOG2_INVALID_TOKEN' );
		}
		$app = JFactory::getApplication();
		
		$user = JFactory::getUser();
		if (!$user->authorise('core.edit', 'com_djcatalog2')){
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories' );
			return false;
		}

		$cid = $app->input->get( 'cid', array(), 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_DJCATALOG2_SELECT_ITEM_TO_RECREATE_THUMBS ' ) );
		}
		
		$tmp = array();
		$tmp[0] = $cid[0];
		unset($cid[0]);

		$model = $this->getModel('categories');
		if(!$model->recreateThumbnails($tmp)) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories',$model->getError() );
		}
		if (count( $cid ) < 1) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories' );	
		} else {
			$cids = null;
			foreach ($cid as $value) {
				$cids .= '&cid[]='.$value; 
			}
			echo '<h3>'.JTEXT::_('COM_DJCATALOG2_RESIZING_CATEGORY').' [id = '.$tmp[0].']... '.JTEXT::_('COM_DJCATALOG2_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djcatalog2&task=categories.recreateThumbnails'.$cids.'&'.JSession::getFormToken().'=1');
		}
	}
	
	public function export_filtered($cid = array()) {
	
		if (!JSession::checkToken('post') && !JSession::checkToken('get')) {
			jexit( 'COM_DJCATALOG2_INVALID_TOKEN' );
		}
		$app = JFactory::getApplication();
		$task 	= $this->getTask();
	
		$path = JPATH_ROOT.DS.'media'.DS.'djcatalog2'.DS.'export'.DS.'category';
		if (!JFolder::exists($path)) {
			JFolder::create($path);
		}
	
		if (!is_writable($path)) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories', JText::_('COM_DJCATALOG2_FOLDER_NOT_WRITABLE').' '.$path, 'error' );
			return false;
		}
	
		$enclosure = "\"";
		$separator = ",";
		$newline = PHP_EOL;
	
		$user = JFactory::getUser();
		if (!$user->authorise('core.manage', 'com_djcatalog2')){
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories' );
			return false;
		}
	
		jimport('joomla.application.component.modellist');
	
		$model = JModelList::getInstance('Categories', 'Djcatalog2Model', array('ignore_request'=>true));
	
		$state = $model->getState();
		$context = 'com_djcatalog2.categories';
	
		$start = $app->input->get('start', 0);
		$limit = 1000;
	
		$model->setState('list.select', 'a.*, uc.name as author');
		
		/*if ($task == 'export_selected' && count($cid) > 0) {
			$limit = $start = 0;
			JArrayHelper::toInteger($cid);
			$model->setState('filter.ids', implode(',',$cid));
		} else {*/
			$search = $model->getUserStateFromRequest($context.'.filter.search', 'filter_search');
			$model->setState('filter.search', $search);
		/*}*/
	
		$model->setState('list.start', $start);
		$model->setState('list.limit', $limit);
	
		$params = JComponentHelper::getParams('com_djcatalog2');
		$model->setState('params', $params);
	
		$items = $model->getItems();

		$db = JFactory::getDbo();
		$db->setQuery('SHOW COLUMNS FROM #__djc2_categories');
		$columns = $db->loadColumn(0);
		$columns = array_merge($columns, array('author'));

		$filename = $app->input->get('export_file', 'category-export-'.date("Y-m-d_H-i-s").'.csv', 'raw');
		$fp = fopen($path.DS.$filename, 'a');
		if (!empty($columns) && (!$start || $start == 0)) {
			fputcsv($fp, $columns, $separator, $enclosure);
		}
	
		foreach ($items as $id => $item) {
			 
			$itemRow = array();
			 
			foreach($columns as $colname) {
				 
				if (!empty($items[$id]->$colname)) {
					$itemRow[$colname] = $items[$id]->$colname;
				}
				if (!isset($itemRow[$colname])) {
					$itemRow[$colname] = '';
				}
			}
			 
			fputcsv($fp, $itemRow, $separator, $enclosure);
		}
		fclose($fp);
	
		$pagination = $model->getPagination();
		if ($pagination->get('pages.total') > $pagination->get('pages.current')) {
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djcatalog2&task=categories.export_filtered&start='.($start+=$limit).'&export_file='.$filename.'&'.JSession::getFormToken().'=1');
			echo '<p>'.$pagination->get('pages.current').' / '.$pagination->get('pages.total').'</p>';
		} else {
			//header("refresh: 0; url=".JURI::base().'index.php?option=com_djcatalog2&view=items');
			$file_link = '<a href="'.JRoute::_('index.php?option=com_djcatalog2&task=download_file&path='.base64_encode('media/djcatalog2/export/category/'.$filename)).'">'.$filename.'</a>';
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories', JText::_('COM_DJCATALOG2_EXPORT_SUCCESFULL').' '.$file_link );
		}
	
		return true;
	}
	
	public function import() {
		if (!JSession::checkToken('post') && !JSession::checkToken('get')) {
			jexit( 'COM_DJCATALOG2_INVALID_TOKEN' );
		}
		
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'import.php');

		$app = JFactory::getApplication();
		$model = $this->getModel('category');
		$files = $app->input->files;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$date = JFactory::getDate();
		
		
		$file = $files->get('csvfile');
		
		$enclosure = ($app->input->get('enclosure', 0) == 0) ? "\"" : "'";
		$separator = ($app->input->get('separator', 0) == 0) ? "," : ";";
		
		$defaults = array();
		$defaults['parent_id'] = $app->input->get('parent_id', 0);
		$defaults['published'] = $app->input->get('published', 0);
		$defaults['created_by'] = $app->input->get('created_by', $user->id);
		$defaults['created'] = $app->input->get('created',  $date->toSql());
		
		if ($defaults['parent_id'] > 0) {
			$db->setQuery('select count(*) from #__djc2_categories where id ='. $defaults['parent_id']);
			$result = (bool)$db->loadResult();
			if ($result == false ){
				$defaults['parent_id'] = 0;
			}
		}
		
		$messages = array();
		
		if(!empty($file)) {
			if(!$file['error']) {
				$tempname = $file['tmp_name'];
				
				if (mb_check_encoding(file_get_contents($file['tmp_name']), 'UTF-8') == false) {
					$this->setRedirect(JRoute::_('index.php?option=com_djcatalog2&view=import', false), JText::_('COM_DJCATALOG2_ERROR_INVALID_ENCODING'), 'error');
					return false;
				}
				
				$rows = Djcatalog2ImportHelper::parseCSV(realpath($tempname), $separator, $enclosure);
				if (!empty($rows)) {
					$messages = Djcatalog2ImportHelper::storeRecords($rows, $model, 'category', $defaults);
				}
			}
		}
		
		foreach($messages as $type => $arr) {
			if (!empty($arr)){
				foreach($arr as $message) {
					$app->enqueueMessage($message, $type);
				}
			}
		}
		
		$this->setRedirect(JRoute::_('index.php?option=com_djcatalog2&view=import', false));
		return true;
	}
	
}