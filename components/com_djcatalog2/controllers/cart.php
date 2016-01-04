<?php
/**
 * @version $Id: cart.php 343 2014-08-21 14:29:20Z michal $
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

class DJCatalog2ControllerCart extends JControllerLegacy
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	function add() {
		//JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$item_id = $app->input->getInt('item_id', 0);
		$quantity = max(1, $app->input->getInt('quantity', 0));
		$return = base64_decode($app->input->get('return', null, 'base64'));
		
		$is_ajax = (bool)($app->input->get('ajax', null) == '1');
		
		if (!$return) {
			$return = JRoute::_(DJCatalogHelperRoute::getCartRoute(), false);
		}
		
		if (!$item_id) {
			if ($is_ajax) {
				$response = array(
						'code' 		=> '400', 
						'error' 	=> '1', 
						'message' 	=> JText::_('COM_DJCATALOG2_UPDATE_CART_FAILED'), 
						'html'		=> '',
						'item_id'	=> $item_id
				);
				echo json_encode($response);
				$app->close();
			} else {
				$this->setRedirect($return, JText::_('COM_DJCATALOG2_UPDATE_CART_FAILED'), 'error');
				return false;
			}
		}
		
		$basket = Djcatalog2HelperCart::getInstance(true);
		
		if ($basket->addItem($item_id, $quantity) == false) {
			if ($is_ajax) {
				$response = array(
						'code' 		=> '400',
						'error' 	=> '1',
						'message' 	=> JText::_('COM_DJCATALOG2_ADD_TO_CART_FAILED'),
						'html'		=> '',
						'item_id'	=> $item_id
				);
				echo json_encode($response);
				$app->close();
			} else {
				$this->setRedirect($return, JText::_('COM_DJCATALOG2_ADD_TO_CART_FAILED'), 'error');
				return false;
			}
		}

		$basket->saveToStorage();
		
		$item_obj = $basket->getItem($item_id);
		
		$items = $basket->getItems();
		
		if ($is_ajax) {
			$response = array(
					'code' 		=> '200',
					'error' 	=> '0',
					'message' 	=> JText::sprintf('COM_DJCATALOG2_ADD_TO_CART_SUCCESS', $item_obj->name, JRoute::_(DJCatalogHelperRoute::getCartRoute())),
					'html'		=> '',
					'item_id'	=> $item_id,
					'item_name'	=> $item_obj->name,
					'basket_count' => count($items)
			);
			echo json_encode($response);
			$app->close();
		}
		
		$msg = JText::sprintf('COM_DJCATALOG2_ADD_TO_CART_SUCCESS', $item_obj->name, JRoute::_(DJCatalogHelperRoute::getCartRoute()));
		$this->setRedirect($return, $msg, 'message');
		return true;
	}
	
	public function update() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		$item_id = $app->input->getInt('item_id', 0);
		$quantity = max(0, $app->input->getInt('quantity', 0));
		$return = base64_decode($app->input->get('return', null, 'base64'));
		if (!$return) {
			$return = JRoute::_(DJCatalogHelperRoute::getCartRoute(), false);
		}
		
		if (!$item_id) {
			$this->setRedirect($return, JText::_('COM_DJCATALOG2_UPDATE_CART_FAILED'), 'error');
			return false;
		}

		$msg = JText::_('COM_DJCATALOG2_UPDATE_CART_SUCCESS');
		$basket = Djcatalog2HelperCart::getInstance(true);
		
		if (!$quantity) {
			$basket->removeItem($item_id);
			$msg = JText::_('COM_DJCATALOG2_PRODUCT_REMOVED_FROM_CART');
		}
		else if ($basket->updateQuantity($item_id, $quantity) == false) {
			$this->setRedirect($return, JText::_('COM_DJCATALOG2_UPDATE_CART_FAILED'), 'error');
			return false;
		}
		
		$basket->saveToStorage();

		$this->setRedirect($return, $msg, 'message');
		return true;
		
	}
	
	public function remove() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
		$item_id = $app->input->getInt('item_id', 0);

		$return = base64_decode($app->input->get('return', null, 'base64'));
		if (!$return) {
			$return = JRoute::_(DJCatalogHelperRoute::getCartRoute(), false);
		}
	
		if (!$item_id) {
			$this->setRedirect($return, JText::_('COM_DJCATALOG2_UPDATE_CART_FAILED'), 'error');
			return false;
		}
	
		$msg = JText::_('COM_DJCATALOG2_PRODUCT_REMOVED_FROM_CART');
		$basket = Djcatalog2HelperCart::getInstance(true);
	
		if ($basket->removeItem($item_id) == false) {
			$this->setRedirect($return, JText::_('COM_DJCATALOG2_UPDATE_CART_FAILED'), 'error');
			return false;
		}
	
		$basket->saveToStorage();
	
		$this->setRedirect($return, $msg, 'message');
		return true;
	
	}
	
	public function checkout() {
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app = JFactory::getApplication();
		
		if ($this->allowCheckout() == false) {
			return false;
		}
		
		$app->redirect(JRoute::_(DJCatalogHelperRoute::getCheckoutRoute(), false));
		return true;
	}
	
	public function query() {
	
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$app = JFactory::getApplication();
	
		if ($this->allowQuery() == false) {
			return false;
		}
	
		$app->redirect(JRoute::_(DJCatalogHelperRoute::getQueryRoute(), false));
		return true;
	}
	
	public function confirm() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		if ($this->allowCheckout() == false) {
			return false;
		}
		
		$app = JFactory::getApplication();
		$date = JFactory::getDate();
		$model = $this->getModel('Order');
		$db = JFactory::getDbo();
		
		$post_data  = $this->input->post->get('jform', array(), 'array');
		
		$basket = Djcatalog2HelperCart::getInstance();
		$items = $basket->getItems();
		
		$user = Djcatalog2Helper::getUser();
		$user_data = Djcatalog2Helper::getUserProfile($user->id);
		$user_data = JArrayHelper::fromObject($user_data);
		
		$form = $model->getForm(array(), false);
		
		if (!$form) {
			$app->enqueueMessage($model->getError(), 'error');
			return false;
		}
		
		$form_data = array();
		$fields = $form->getFieldset('basicprofile');
		foreach ($fields as $field) {
			if (isset($user_data[$field->fieldname])) {
				$form_data[$field->fieldname] = $user_data[$field->fieldname];
			}
				
			if (isset($post_data['djcatalog2profile'][$field->fieldname])) {
				$form_data[$field->fieldname] = $post_data['djcatalog2profile'][$field->fieldname];
			}
				
			if (!isset($form_data[$field->fieldname])) {
				$form_data[$field->fieldname] = null;
			}
		}
		
		$data = array('djcatalog2profile' => $form_data);
		
		if (empty($data) || empty($data['djcatalog2profile'])) {
			$data = array('djcatalog2profile' => $user_data);
		}
		
		// Test whether the data is valid.
		$validData = $model->validate($form, $data);
		
		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();
		
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
		
			// Save the data in the session.
			$app->setUserState('com_djcatalog2.order.data', $data);
		
			// Redirect back to the quote screen.
			$this->setRedirect(JRoute::_(DJCatalogHelperRoute::getQueryRoute(), false));
		
			return false;
		}

		$totals = $basket->getTotal();
		$tax_rules = $user_data->tax_rules;
		
		$app->setUserState('com_djcatalog2.order.data', $validData);
		
		$orderData = $validData['djcatalog2profile'];
		
		$order = array();
		
		$order['id'] 				= null;
		$order['user_id'] 			= $user->id;
		
		if (($user->guest || empty($user->email)) && !empty($orderData['email'])) {
			$quote['email'] 		= $orderData['email'];
		} else {
			$quote['email'] 		= $user->email;
		}
		
		$order['order_number']	 	= null;
		$order['invoice_number'] 	= null;
		$order['created_date'] 		= $date->toSql(true);
		
		$order['total'] 			= $totals['net'];
		$order['tax'] 				= $totals['tax'];
		$order['grand_total'] 		= $totals['gross'];
		
		$order['payment_method'] 	= '';
		$order['currency'] 			= '';
		$order['status'] 			= $user->guest ? 'N' : 'A';
		
		$quote['firstname'] 		= $orderData['firstname'];
		$quote['lastname'] 			= $orderData['lastname'];
		$quote['company'] 			= $orderData['company'];
		$quote['address'] 			= $orderData['address'];
		$quote['city'] 				= $orderData['city'];
		$quote['postcode'] 			= $orderData['postcode'];
		
		$quote['country_id'] 		= $orderData['country_id'];
		
		if ((empty($orderData['country_name']) || $orderData['country_name'] == '*') && !empty($orderData['country_id'])) {
			$db->setQuery('select country_name from #__djc2_countries where id='.(int)$orderData['country_id']);
			$country = $db->loadResult();
			$quote['country'] = $country ? $country : '';
		} else {
			$quote['country'] = $orderData['country_name'];
		}
		
		$quote['vat_id'] 			= $orderData['vat_id'];
		
		$quote['customer_note'] 	= $orderData['customer_note'];
		
		
		$order_items = array();
		foreach($items as $item) {
			$record = array();
			$record['id'] = 0;
			$record['item_id'] 		= $item->id;
			$record['item_name'] 	= $item->name;
			$record['quantity'] 	= $item->_quantity;
			$record['cost'] 		= $item->_prices['total']['net'];
			$record['base_cost'] 	= $item->_prices['base']['net'];
			$record['tax'] 			= $item->_prices['total']['tax'];
			$record['total'] 		= $item->_prices['total']['gross'];
			$record['tax_rate'] 	= (isset($tax_rules[$item->tax_rate_id]) && $item->tax_rate_id > 0 ) ? round(($tax_rules[$item->tax_rate_id]/100), 4) : 0;
			
			$order_items[] = $record;
		}
		
		$order['order_items'] = array();
		
		foreach ($order_items as $pos => $rec) {
			foreach ($rec as $key => $value) {
				if (!isset($order['order_items'][$key])) {
					$order['order_items'][$key] = array();
				}
				
				$order['order_items'][$key][] = $value;
			}
		}
		
		if ($model->save($order) == false) {
			$msg = $model->getError();
			$app->redirect(JRoute::_(DJCatalogHelperRoute::getCheckoutRoute(), false), JText::_('COM_DJCATALOG2_ORDER_STORE_ERRROR').' '.$msg);
			return false;
		}
		
		$basket->clear();
		
		$order['id'] = $model->getState('order.id');
		$order['order_number'] = $model->getState('order.number');
		
		$order['items'] = $order_items;
		
		if ($this->_sendEmail($order, 'order') == false) {
			$app->enqueueMessage(JText::_('COM_DJCATLAOG2_ORDER_NOTIFICATION_ERROR'), 'error');
		}
		
		$app->redirect(JRoute::_(DJCatalogHelperRoute::getOrderRoute($order['id']), false), JText::_('COM_DJCATALOG2_ORDER_SENT'));
		return true;
		
	}
	
	public function query_confirm() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		if ($this->allowQuery() == false) {
			return false;
		}
	
		$app = JFactory::getApplication();
		$date = JFactory::getDate();
		$model = $this->getModel('Query');
		$db = JFactory::getDbo();
		
		$post_data  = $app->input->post->get('jform', array(), 'array');
		
		$basket = Djcatalog2HelperCart::getInstance();
		$items = $basket->getItems();
		
		$user = Djcatalog2Helper::getUser();
		$user_data = Djcatalog2Helper::getUserProfile($user->id);
		$user_data = JArrayHelper::fromObject($user_data);
		
		$form = $model->getForm(array(), false);
		
		if (!$form) {
			$app->enqueueMessage($model->getError(), 'error');
			return false;
		}

		$form_data = array();
		$fields = $form->getFieldset('basicprofile');
		foreach ($fields as $field) {
			if (isset($user_data[$field->fieldname])) {
				$form_data[$field->fieldname] = $user_data[$field->fieldname];
			}
			
			if (isset($post_data['djcatalog2profile'][$field->fieldname])) {
				$form_data[$field->fieldname] = $post_data['djcatalog2profile'][$field->fieldname];
			} 
			
			if (!isset($form_data[$field->fieldname])) {
				$form_data[$field->fieldname] = null;
			}
		}
		
		$data = array('djcatalog2profile' => $form_data);
		
		if (empty($data) || empty($data['djcatalog2profile'])) {
			$data = array('djcatalog2profile' => $user_data);
		}
		
		// Test whether the data is valid.
		$validData = $model->validate($form, $data);
		
		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_djcatalog2.query.data', $data);

			// Redirect back to the quote screen.
			$this->setRedirect(JRoute::_(DJCatalogHelperRoute::getQueryRoute(), false));

			return false;
		}
		
		$app->setUserState('com_djcatalog2.query.data', $validData);
		
		$quoteData = $validData['djcatalog2profile'];
		$quote = array();

		$quote['id'] 				= null;
		$quote['user_id'] 			= $user->id;
		
		if (($user->guest || empty($user->email)) && !empty($quoteData['email'])) {
			$quote['email'] 		= $quoteData['email'];
		} else {
			if (!empty($quoteData['email'])) {
				$quote['email'] 		= $quoteData['email'];
			} else {
				$quote['email'] 		= $user->email;
			}
		}
		
		$quote['created_date'] 		= $date->toSql(true);
	
		$quote['firstname'] 		= !empty($quoteData['firstname']) ? $quoteData['firstname'] : '';
		$quote['lastname'] 			= !empty($quoteData['firstname']) ? $quoteData['lastname'] : '';
		$quote['company'] 			= !empty($quoteData['company']) ? $quoteData['company'] : '';
		$quote['address'] 			= !empty($quoteData['address']) ? $quoteData['address'] : '';
		$quote['city'] 				= !empty($quoteData['city']) ? $quoteData['city'] : '';
		$quote['postcode'] 			= !empty($quoteData['postcode']) ? $quoteData['postcode'] : '';
		
		$quote['country_id'] 		= !empty($quoteData['country_id']) ? $quoteData['country_id'] : '';
		
		if ((empty($quoteData['country_name']) || $quoteData['country_name'] == '*') && !empty($quoteData['country_id'])) {
			$db->setQuery('select country_name from #__djc2_countries where id='.(int)$quoteData['country_id']);
			$country = $db->loadResult();
			$quote['country'] = $country ? $country : '';
		} else {
			$quote['country'] = $quoteData['country_name'];
		}
		
		$quote['vat_id'] 			= !empty($quoteData['vat_id']) ? $quoteData['vat_id'] : '';
		
		$quote['customer_note'] 	= !empty($quoteData['customer_note']) ? $quoteData['customer_note'] : '';
	
		$quote_items = array();
		foreach($items as $item) {
			$record = array();
			$record['id'] = 0;
			$record['item_id'] 		= $item->id;
			$record['item_name'] 	= $item->name;
			$record['quantity'] 	= $item->_quantity;
				
			$quote_items[] = $record;
		}
	
		$quote['quote_items'] = array();
	
		foreach ($quote_items as $pos => $rec) {
			foreach ($rec as $key => $value) {
				if (!isset($quote['quote_items'][$key])) {
					$quote['quote_items'][$key] = array();
				}
	
				$quote['quote_items'][$key][] = $value;
			}
		}
	
		if ($model->save($quote) == false) {
			$msg = $model->getError();
			$app->redirect(JRoute::_(DJCatalogHelperRoute::getQueryRoute(), false), JText::_('COM_DJCATALOG2_QUOTE_STORE_ERRROR').' '.$msg);
			return false;
		}

		$basket->clear();
	
		$quote['id'] = $model->getState('query.id');
	
		$quote['items'] = $quote_items;
	
		if ($this->_sendEmail($quote, 'query') == false) {
			$app->enqueueMessage(JText::_('COM_DJCATLAOG2_QUOTE_NOTIFICATION_ERROR'), 'error');
		}
	
		//TODO: redirect to proper screen
		$app->redirect(JRoute::_(DJCatalogHelperRoute::getCartRoute(), false), JText::_('COM_DJCATALOG2_QUOTE_SENT'));
		return true;
	
	}
	
	protected function allowCheckout() {
		$app = JFactory::getApplication();
		
		$user_profile = Djcatalog2Helper::getUserProfile();
		$user = Djcatalog2Helper::getUser();
		
		if ($user->guest) {
			$return_url = base64_encode(DJCatalogHelperRoute::getCheckoutRoute());
			$app->redirect(JRoute::_('index.php?option=com_users&view=login&return='.$return_url, false), JText::_('COM_DJCATALOG2_PLEASE_LOGIN'));
			return false;
		}
		
		if (empty($user_profile) || empty($user_profile->id)) {
			$return_url = base64_encode(DJCatalogHelperRoute::getCheckoutRoute());
			$app->setUserState('com_users.edit.profile.redirect', DJCatalogHelperRoute::getCheckoutRoute());
			$app->redirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&return='.$return_url, false), JText::_('COM_DJCATALOG2_PLEASE_FILLIN_BILLING_DETAILS'));
			return false;
		}
		
		$basket = Djcatalog2HelperCart::getInstance(true);
		
		$basket->recalculate();
		
		if (empty($basket) || !$basket->getItems()) {
			$app->redirect(JUri::base(), JText::_('COM_DJCATALOG2_CART_IS_EMPTY'));
			return false;
		}
		
		return true;
	}
	
	protected function allowQuery() {
		$app = JFactory::getApplication();
	
		$user_profile = Djcatalog2Helper::getUserProfile();
		$user = Djcatalog2Helper::getUser();
	
		$params 	= JComponentHelper::getParams('com_djcatalog2');
		
		if ($params->get('cart_query_enabled', '1') != '1') {
			throw new Exception(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		$auth = ($params->get('cart_query_registered', '1') == '1' && $user->guest) ? false : true;
		
		if (!$auth) {
			$return_url = base64_encode(DJCatalogHelperRoute::getQueryRoute());
			$app->redirect(JRoute::_('index.php?option=com_users&view=login&return='.$return_url, false), JText::_('COM_DJCATALOG2_PLEASE_LOGIN'));
			return false;
		}
	
		$basket = Djcatalog2HelperCart::getInstance(true);
	
		$basket->recalculate();
	
		if (empty($basket) || !$basket->getItems()) {
			$app->redirect(JUri::base(), JText::_('COM_DJCATALOG2_CART_IS_EMPTY'));
			return false;
		}
	
		return true;
	}
	
	private function _sendEmail($data, $type)
	{
		$app		= JFactory::getApplication();
		$params 	= JComponentHelper::getParams('com_djcatalog2');
		$user = JFactory::getUser();
			
		$mailfrom	= $app->getCfg('mailfrom');
		$fromname	= $app->getCfg('fromname');
		$sitename	= $app->getCfg('sitename');
			
		$contact_list = $params->get('contact_list', false);
		$recipient_list = array();
		if ($contact_list !== false) {
			$recipient_list = explode(PHP_EOL, $params->get('contact_list', ''));
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
		
		$subject = null;
		$admin_body = null;
		$client_body = null;
		
		switch($type) {
			case 'order' : 
				$subject = JText::sprintf('COM_DJCATALOG2_EMAIL_NEW_ORDER_SUBJECT', $data['order_number'], $sitename);
				$admin_body = DJCatalog2HtmlHelper::getEmailTemplate($data, 'admin.order');
				$client_body = DJCatalog2HtmlHelper::getEmailTemplate($data, 'order');
				break;
			case 'query' :
				$subject = JText::sprintf('COM_DJCATALOG2_EMAIL_NEW_QUOTE_SUBJECT', $sitename);
				$admin_body = DJCatalog2HtmlHelper::getEmailTemplate($data, 'admin.quote');
				$client_body = DJCatalog2HtmlHelper::getEmailTemplate($data, 'quote');
				break;
		}
		
		if (!$admin_body) {
			return false;
		}
		
		// Send admin's email first
		$mail = JFactory::getMailer();
	
		//$mail->addRecipient($mailfrom);
		foreach ($recipient_list as $recipient) {
			$mail->addRecipient(trim($recipient));
		}

		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($subject);
		$mail->setBody($admin_body);
		$mail->isHtml(true);
		$admin_sent = $mail->Send();
		
		// Send an email to customer
		$mail = JFactory::getMailer();
		
		//$mail->addRecipient($mailfrom);
		$mail->addRecipient($data['email']);
		
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($subject);
		$mail->setBody($client_body);
		$mail->isHtml(true);
		$mail->Send();
	
		return $admin_sent;
	}

}