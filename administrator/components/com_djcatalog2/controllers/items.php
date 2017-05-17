<?php
/**
 * @version $Id: items.php 312 2014-07-16 08:10:07Z michal $
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


class Djcatalog2ControllerItems extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unfeatured',	'featured');
		$this->registerTask('inavailable',	'available');
	}
	
	public function getModel($name = 'Item', $prefix = 'Djcatalog2Model', $config = array())
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
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=items' );	
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

		$model = $this->getModel('items');
		if(!$model->recreateThumbnails($tmp)) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=items',$model->getError() );
		}
		if (count( $cid ) < 1) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=items' );	
		} else {
			$cids = null;
			foreach ($cid as $value) {
				$cids .= '&cid[]='.$value; 
			}
			echo '<h3>'.JTEXT::_('COM_DJCATALOG2_RESIZING_ITEM').' [id = '.$tmp[0].']... '.JTEXT::_('COM_DJCATALOG2_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djcatalog2&task=items.recreateThumbnails'.$cids.'&'.JSession::getFormToken().'=1');
		}
		
	}
	function featured()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		
		$session	= JFactory::getSession();
		$registry	= $session->get('registry');

		// Get items to publish from the request.
		$cid	= $app->input->get('cid', array(), 'array');
		$task 	= $this->getTask();
		$value = ($task == 'featured') ? 1 : 0;

		if (empty($cid)) {
			JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($cid);

			// Publish the items.
			if (!$model->changeFeaturedState($cid, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}
		$extension = $app->input->get('extension', null, 'cmd');
		$extensionURL = ($extension) ? '&extension=' . $app->input->get('extension', null, 'cmd') : '';
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$extensionURL, false));
	}
	
	function available()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
	
		$session	= JFactory::getSession();
		$registry	= $session->get('registry');
	
		// Get items to publish from the request.
		$cid	= $app->input->get('cid', array(), 'array');
		$task 	= $this->getTask();
		$value = ($task == 'available') ? 1 : 0;
	
		if (empty($cid)) {
			JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel();
	
			// Make sure the item ids are integers
			JArrayHelper::toInteger($cid);
	
			// Publish the items.
			if (!$model->changeAvailableState($cid, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}
		$extension = $app->input->get('extension', null, 'cmd');
		$extensionURL = ($extension) ? '&extension=' . $app->input->get('extension', null, 'cmd') : '';
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$extensionURL, false));
	}
	
	public function export_selected() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		
		// Get items to publish from the request.
		$cid	= $app->input->get('cid', array(), 'array');
		
		if (empty($cid)) {
			JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}
		else {
			return $this->export_filtered($cid);
		}
	}
	
	public function export_filtered($cid = array()) {
		
		if (!JSession::checkToken('post') && !JSession::checkToken('get')) {
			jexit( 'COM_DJCATALOG2_INVALID_TOKEN' );
		}
		$app = JFactory::getApplication();
		$task 	= $this->getTask();
		
		$path = JPATH_ROOT.DS.'media'.DS.'djcatalog2'.DS.'export'.DS.'item';
		if (!JFolder::exists($path)) {
			JFolder::create($path);
		}
		
		if (!is_writable($path)) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=items', JText::_('COM_DJCATALOG2_FOLDER_NOT_WRITABLE').' '.$path, 'error' );
			return false;
		}
		
		$enclosure = "\"";
		$separator = ",";
		$newline = PHP_EOL;

		$user = JFactory::getUser();
		if (!$user->authorise('core.manage', 'com_djcatalog2')){
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=items' );
			return false;
		}
		
		jimport('joomla.application.component.modellist');
		
		$model = JModelList::getInstance('Items', 'Djcatalog2Model', array('ignore_request'=>true));

		$state = $model->getState();
		$context = 'com_djcatalog2.items';
		
		$start = $app->input->get('start', 0);
		$limit = 1000;
		
		$model->setState('list.select', 'a.*, c.name as category_name, p.name as producer_name, uc.name AS author');
		if ($task == 'export_selected' && count($cid) > 0) {
			$limit = $start = 0;
			JArrayHelper::toInteger($cid);
			$model->setState('filter.ids', implode(',',$cid));
		} else {
			$search = $model->getUserStateFromRequest($context.'.filter.search', 'filter_search');
			$model->setState('filter.search', $search);
			
			$published = $model->getUserStateFromRequest($context.'.filter.published', 'filter_published', '');
			$model->setState('filter.published', $published);
			
			$category = $model->getUserStateFromRequest($context.'.filter.category', 'filter_category', '');
			$model->setState('filter.category', $category);
			
			$producer = $model->getUserStateFromRequest($context.'.filter.producer', 'filter_producer', '');
			$model->setState('filter.producer', $producer);
		}
		
		$model->setState('list.start', $start);
		$model->setState('list.limit', $limit);
		
		$params = JComponentHelper::getParams('com_djcatalog2');
		$model->setState('params', $params);
		
		$items = $model->getItems();
		
		$db = JFactory::getDbo();
		$db->setQuery('SHOW COLUMNS FROM #__djc2_items');
		$columns = $db->loadColumn(0);
		$columns = array_merge($columns, array('category_name', 'producer_name', 'author'));
		
		$filename = $app->input->get('export_file', 'product-export-'.date("Y-m-d_H-i-s").'.csv', 'raw');
		
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
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djcatalog2&task=items.export_filtered&start='.($start+=$limit).'&export_file='.$filename.'&'.JSession::getFormToken().'=1');
			echo '<p>'.$pagination->get('pages.current').' / '.$pagination->get('pages.total').'</p>';
		} else {
			//header("refresh: 0; url=".JURI::base().'index.php?option=com_djcatalog2&view=items');
			$file_link = '<a href="'.JRoute::_('index.php?option=com_djcatalog2&task=download_file&path='.base64_encode('media/djcatalog2/export/item/'.$filename)).'">'.$filename.'</a>';
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=items', JText::_('COM_DJCATALOG2_EXPORT_SUCCESFULL').' '.$file_link );
		}
		
		return true;
	}
	public function import() {
		if (!JSession::checkToken('post') && !JSession::checkToken('get')) {
			jexit( 'COM_DJCATALOG2_INVALID_TOKEN' );
		}
	
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'import.php');
	
		$app = JFactory::getApplication();
		$model = $this->getModel('item');
		$files = $app->input->files;
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$date = JFactory::getDate();
	
	
		$file = $files->get('csvfile');
		
		$enclosure = ($app->input->get('enclosure', 0) == 0) ? "\"" : "'";
		$separator = ($app->input->get('separator', 0) == 0) ? "," : ";";
	
		$defaults = array();
		$defaults['cat_id'] = $app->input->get('cat_id', 0);
		$defaults['producer_id'] = $app->input->get('producer_id', 0);
		$defaults['group_id'] = $app->input->get('group_id', 0);
		$defaults['price'] = $app->input->get('price', 0.0);
		$defaults['special_price'] = $app->input->get('special_price', 0.0);
		$defaults['published'] = $app->input->get('published', 0);
		$defaults['created_by'] = $app->input->get('created_by', $user->id);
		$defaults['created'] = $app->input->get('created',  $date->toSql());
	
		if ($defaults['cat_id'] > 0) {
			$db->setQuery('select count(*) from #__djc2_categories where id ='. $defaults['cat_id']);
			$result = (bool)$db->loadResult();
			if ($result == false ){
				$defaults['cat_id'] = 0;
			}
		}
		if ($defaults['producer_id'] > 0) {
			$db->setQuery('select count(*) from #__djc2_producers where id ='. $defaults['producer_id']);
			$result = (bool)$db->loadResult();
			if ($result == false ){
				$defaults['producer_id'] = 0;
			}
		}
		if ($defaults['group_id'] > 0) {
			$db->setQuery('select count(*) from #__djc2_items_extra_fields_groups where id ='. $defaults['group_id']);
			$result = (bool)$db->loadResult();
			if ($result == false ){
				$defaults['group_id'] = 0;
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
					$messages = Djcatalog2ImportHelper::storeRecords($rows, $model, 'item', $defaults);
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