<?php
/**
 * @version $Id: controller.php 303 2014-07-07 07:22:11Z michal $
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
defined ('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

class DJCatalog2Controller extends JControllerLegacy
{

	function __construct($config = array())
	{
		parent::__construct($config);
		$lang = JFactory::GetLanguage();
		$lang->load('com_djcatalog2');
		$this->registerTask( 'modfp',  'getFrontpageXMLData' );
	}

	function display($cachable = true, $urlparams = null)
	{
		$app = JFactory::getApplication();
		$view = $app->input->get('view');
		
		$id = $app->input->getInt('id');
		
		if ($view == 'itemform' && !$this->checkEditId('com_djcatalog2.edit.itemform', $id)) {
			$app->redirect(JRoute::_(DJCatalogHelperRoute::getMyItemsRoute(), false), JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			return true;
			
		}
		
		$noncachable = array('itemform', 'myitems', 'cart', 'order', 'orders', 'quote');
		
		if (in_array($view, $noncachable)) {
			$cachable = false;
		}
		
		DJCatalog2ThemeHelper::setThemeAssets();
		
		$urlparams = array(
				'id' => 'STRING',
				'cid' => 'STRING',
				'pid' => 'STRING',
				'aid' => 'STRING',
				'search' => 'STRING',
				'task' => 'STRING',
				'order' => 'STRING',
				'dir' => 'STRING',
				'cm' => 'INT',
				'l' => 'STRING',
				'Itemid' => 'INT',
				'limit' => 'UINT', 
				'limitstart' => 'UINT',
				'start' => 'UINT',
				'lang' => 'CMD',
				'tmpl' => 'CMD',
				'ind' => 'RAW',
				'template' => 'STRING',
				'price_from' => 'STRING',
				'price_to' => 'STRING',
				'type' => 'STRING',
				'print' => 'INT',
				'pdf'	=> 'INT',
				'layout'=> 'STRING'
		);
		
		$db = JFactory::getDbo();
		$db->setQuery('select alias from #__djc2_items_extra_fields where type=\'checkbox\' or type=\'radio\' or type=\'select\'');
		$extra_fields = $db->loadColumn();
		if (count($extra_fields) > 0) {
			foreach($extra_fields as $extra_field) {
				$urlparams['f_'.$extra_field] = 'STRING';
				
				// stupid, stupid, stupid me
				$urlparams[str_replace('-', '_', 'f_'.$extra_field)] = 'STRING';
			}
		}

		parent::display($cachable, $urlparams);
	}
	
	function getFrontpageXMLData() {
		$model = $this->getModel('modfrontpage');
		$xml = $model->getXml();
		echo $xml;
		return true;
	}
	function search() {
		$app = JFactory::getApplication();
		//$post = JRequest::get('post');
		$post = $app->input->getArray($_POST);
		$params = array();
		foreach($post as $key => $value) {
			if ($key != 'task' && $key != 'option' && $key != 'view' && $key != 'cid' && $key != 'pid' && $key != 'Itemid') {
				if ($key == 'search') {
					$params[] = $key.'='.urlencode($value);
				}
				else if (is_array($value)) {
					foreach ($value as $k => $v) {
						$params[] = $key.'[]='.$v;
					}
				}
				else {
					$params[] = $key.'='.$value;
				}
			}
		}
		
		
		if (!array_key_exists('cm', $post)) {
			$params[] = 'cm=0';
		}
		
		$menu = JFactory::getApplication('site')->getMenu('site');
		$uri = DJCatalogHelperRoute::getCategoryRoute( $app->input->get( 'cid','0','string' ), $app->input->get( 'pid',null,'string' ));
		if (strpos($uri,'?') === false ) {
			$get = (count($params)) ? '?'.implode('&',$params) : '';
		} else {
			$get = (count($params)) ? '&'.implode('&',$params) : '';
		}
		$app->redirect( JRoute::_($uri.$get, false).'#tlb' );
	}
	function download() {
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$db			= JFactory::getDbo();
		$file_id = 	$app->input->getInt('fid',0);
		
		$query = 'select i.created_by '.
				 'from #__djc2_items as i, '.
				 '#__djc2_files as f where f.item_id = i.id and f.id='.(int)$file_id
		;
		$db->setQuery($query);
		$owner = $db->loadResult();
		
		$authorised = ($user->authorise('djcatalog2.filedownload', 'com_djcatalog2') || $owner == $user->id) ? true : false;
		
		if ($authorised !== true) {
			if ($user->guest) {
				$return = base64_encode(JRoute::_('index.php?option=com_djcatalog2&format=raw&task=download&fid='.$file_id, false));
				$app->redirect(JRoute::_('index.php?option=com_users&view=login&return='.$return, false), JText::_('COM_DJCATALOG2_LOGIN_FIRST'));
				return true;
			} else {
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
				return false;
			}
		}

		if (!DJCatalog2FileHelper::getFile($file_id)){
			throw new Exception('', 404);
            return false;
        }
        
        // Close the application instead of returning from it.
        $app->close();
        //return true;
	}
	public function multiupload() {
	
		// todo: secure upload from injections
		$user = JFactory::getUser();
		if (!$user->authorise('core.manage', 'com_djcatalog2') && !$user->authorise('core.create', 'com_djcatalog2') && !$user->authorise('core.edit', 'com_djcatalog2') && !$user->authorise('core.edit.own', 'com_djcatalog2')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
	
		DJCatalog2UploadHelper::upload();
	
		return true;
	}
}