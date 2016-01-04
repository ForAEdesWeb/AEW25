<?php
/**
 * @version $Id: itemform.php 267 2014-03-24 14:29:02Z michal $
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

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'controllerform.php');

class Djcatalog2ControllerItemform extends DJCJControllerForm {
	function __construct($config = array())
	{
		$this->view_list = 'myitems';
		$this->view_item = 'itemform';
		
		parent::__construct($config);
		
		$this->unregisterTask('save2copy');
		
	}
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$app = JFactory::getApplication();
		$tmpl   = $app->input->get('tmpl');
		
		// got rid of edit layout
		$layout = $app->input->get('layout');
		$append = '';
	
		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}
	
		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}
	
		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}
	
		return $append;
	}
	
	protected function getRedirectToListAppend()
	{
		$app = JFactory::getApplication();
		$tmpl = JFactory::getApplication()->input->get('tmpl');
		
		$append = '';
	
		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}
		
		$needles = array(
				'myitems' => array(0),
				'items' => array(0)
		);
		
		if ($item = DJCatalogHelperRoute::_findItem($needles)) {
			$append .= '&Itemid='.$item;
		}
		
		return $append;
	}
	
	protected function allowEdit($data = array(), $key = 'id') {
		// Initialise variables.
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		//$asset		= 'com_djcatalog2.item.'.$recordId;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $this->option)) {
			return true;
		}
		
		$ownerId	= (int) isset($data['created_by']) ? $data['created_by'] : 0;
		if (empty($ownerId) && $recordId) {
			// Need to do a lookup from the model.
			$record		= $this->getModel()->getItem($recordId);

			if (empty($record)) {
				return false;
			}

			$ownerId = $record->created_by;
		}

		if ($ownerId == $userId && $user->authorise('core.edit.own', $this->option)) {
			return true;
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}
	
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$params = JComponentHelper::getParams('com_djcatalog2');
		$app = JFactory::getApplication();
		
		$authorised =  ($user->authorise('core.create', $this->option));
		
		$limit = (int)$params->get('fed_max_products', 0);

		if ($user->id > 0 && $limit > 0) {
			$db = JFactory::getDbo();
			$db->setQuery('select count(*) from #__djc2_items where created_by='.(int)$user->id);
			$submitted_count = (int)$db->loadResult();
			if ($submitted_count >= $limit) {
				$authorised = false;
				$app->enqueueMessage(JText::sprintf('COM_DJCATALOG2_FED_LIMIT_REACHED', $limit), 'notice');
			}
		}
		
		return $authorised;
	}
	
	public function add() {
		$user = JFactory::getUser();
		
		if ((bool)$user->guest && !$this->allowAdd()) {
			$return_url = base64_encode(DJCatalogHelperRoute::getMyItemsRoute().'&task=itemform.add');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login&return='.$return_url, false), JText::_('COM_DJCATALOG2_PLEASE_LOGIN'));
			return true;
		}
		
		return parent::add();
	}
	
	protected function _postSaveHook($model, $validData = array()) {
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_djcatalog2');
		$user = JFactory::getUser();
		if ((empty($validData['id']) || $validData['id'] == 0) && $user->guest) {
			$this->setRedirect(JRoute::_(DJCatalogHelperRoute::getCategoryRoute(0),false));
		}
		
		if ((int)$params->get('fed_notify', 1) == 1 && (empty($validData['id']) || $validData['id'] == 0)) {
			$recordId = $model->getState($this->context . '.id');
			$item = $model->getItem($recordId);
			$this->_sendEmail($item);
		}
	}
	
	private function _sendEmail($item)
	{
		$app		= JFactory::getApplication();
		$params 	= JComponentHelper::getParams('com_djcatalog2');
		$user = JFactory::getUser();
			
		$mailfrom	= $app->getCfg('mailfrom');
		$fromname	= $app->getCfg('fromname');
		$sitename	= $app->getCfg('sitename');
		$copytext 	= JText::sprintf('COM_DJCATALOG2_COPYTEXT_OF', $item->name, $sitename);
			
		$contact_list = $params->get('fed_notify_list', false);
		$recipient_list = array();
		if ($contact_list !== false) {
			$recipient_list = explode(PHP_EOL, $params->get('fed_notify_list', ''));
		}
			
		$list_is_empty = true;
		foreach ($recipient_list as $r) {
			if (strpos($r, '@') !== false) {
				$list_is_empty = false;
				break;
			}
		}
			
		if ($list_is_empty) {
			$recipient_list[] = $mailfrom;
		}
			
		$recipient_list = array_unique($recipient_list);
		
		$name = null;
		$email = null;
		$item_name = $item->name;
		$item_id = $item->id;
		
		$subject	= JText::_('COM_DJCATALOG2_NEW_PRODUCT_SUBMITTED_SUBJECT');
		$body = '';
		if ($user->guest) {
			$body = JText::sprintf('COM_DJCATALOG2_PRODUCT_SUBMITTED_BY_GUEST', $item_id, $item_name);
		} else {
			$name		= $user->name.' ('.$user->username.')';
			$email		= $user->email;
			$body = JText::sprintf('COM_DJCATALOG2_PRODUCT_SUBMITTED', $item_id, $item_name, $name, $email);
		}

		$mail = JFactory::getMailer();
	
		//$mail->addRecipient($mailfrom);
		foreach ($recipient_list as $recipient) {
			$mail->addBCC(trim($recipient));
		}
		if ($user->guest == false) {
			$mail->addReplyTo(array($email, $name));
		}
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($sitename.': '.$subject);
		$mail->setBody($body);
		
		$sent = $mail->Send();
	
		//If we are supposed to copy the sender, do so.
		// check whether email copy function activated
		/*
		if ( array_key_exists('contact_email_copy', $data)  ) {
			$copytext		= JText::sprintf('COM_DJCATALOG2_COPYTEXT_OF', $item->name, $sitename);
			$copytext		.= "\r\n\r\n".$body;
			$copysubject	= JText::sprintf('COM_DJCATALOG2_COPYSUBJECT_OF', $subject);
	
			$mail = JFactory::getMailer();
			$mail->addRecipient($email);
			$mail->addReplyTo(array($email, $name));
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($copysubject);
			$mail->setBody($copytext);
			$sent = $mail->Send();
		}
		*/
		return $sent;
	}
	
}
?>
