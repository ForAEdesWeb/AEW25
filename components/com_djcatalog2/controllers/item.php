<?php
/**
 * @version $Id: item.php 267 2014-03-24 14:29:02Z michal $
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

jimport('joomla.application.component.controllerform');

class Djcatalog2ControllerItem extends JControllerForm
{
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

	public function contact()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('item');
		$params = JComponentHelper::getParams('com_djcatalog2');
		$slug	= $app->input->getString('id');
		$id		= (int)$slug;

		// Get the data from POST
		$data = $app->input->get('jform', array(), 'array');

		$item = $model->getItem($id);

		// Check for a valid session cookie
		if(JFactory::getSession()->getState() != 'active'){
			JError::raiseWarning(403, JText::_('COM_CONTACT_SESSION_INVALID'));

			// Save the data in the session.
			$app->setUserState('com_djcatalog2.contact.data', $data);

			// Redirect back to the contact form.
			$this->setRedirect(JRoute::_('index.php?option=com_djcatalog2&view=item&id='.$slug.'&cid='.$item->cat_id, false).'#contactform');
			return false;
		}

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}

		$validate = $model->validate($form, $data);

		if ($validate === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_djcatalog2.contact.data', $data);

			// Redirect back to the contact form.
			$this->setRedirect(JRoute::_(DJCatalogHelperRoute::getItemRoute($slug, $item->cat_id), false).'#contactform');
			return false;
		}

		// Send the email
		$sent = $this->_sendEmail($data, $item);

		// Set the success message if it was a success
		if (!($sent instanceof Exception)) {
			$msg = JText::_('COM_DJCATALOG2_EMAIL_THANKS');
		} else {
			$msg = '' ;
		}

		// Flush the data from the session
		$app->setUserState('com_djcatalog2.contact.data', null);
		
		$this->setRedirect(JRoute::_(DJCatalogHelperRoute::getItemRoute($slug, $item->cat_id), false), $msg);

		return true;
	}

	private function _sendEmail($data, $item)
	{
			$app		= JFactory::getApplication();
			$params 	= JComponentHelper::getParams('com_djcatalog2');
			/*if ($contact->email_to == '' && $contact->user_id != 0) {
				$contact_user = JUser::getInstance($contact->user_id);
				$contact->email_to = $contact_user->get('email');
			}*/
			
			$mailfrom	= $app->getCfg('mailfrom');
			$fromname	= $app->getCfg('fromname');
			$sitename	= $app->getCfg('sitename');
			$copytext 	= JText::sprintf('COM_DJCATALOG2_COPYTEXT_OF', $item->name, $sitename);
			
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
			
			$recipient_settings = $params->get('fed_contact', 0);
			$owner = JFactory::getUser($item->created_by);
			$owner_email = null;
			if ($owner && $owner->email) {
				$owner_email = $owner->email;
			}
			
			if ((int)$recipient_settings == 1 && $owner_email) {
				$recipient_list = array($owner_email);
			} else if ((int)$recipient_settings == 2) {
				$recipient_list[] = $owner_email;
			}
			
			$recipient_list = array_unique($recipient_list);

			$name		= $data['contact_name'];
			$email		= $data['contact_email'];
			$subject	= $data['contact_subject'];
			$body		= $data['contact_message'];
			
			$additional_data = array();
			if (!empty($data['contact_company_name'])){
				$additional_data[] = array(
						'label' => JText::_('COM_DJCATALOG2_CONTACT_COMPANY_NAME_LABEL'),
						'value'	=> $data['contact_company_name']
				);
			}
			if (!empty($data['contact_phone'])){
				$additional_data[] = array(
						'label' => JText::_('COM_DJCATALOG2_CONTACT_PHONE_LABEL'),
						'value'	=> $data['contact_phone']
				);
			}
			if (!empty($data['contact_street'])){
				$additional_data[] = array(
						'label' => JText::_('COM_DJCATALOG2_CONTACT_STREET_LABEL'),
						'value'	=> $data['contact_street']
				);
			}
			if (!empty($data['contact_city'])){
				$additional_data[] = array(
						'label' => JText::_('COM_DJCATALOG2_CONTACT_CITY_LABEL'),
						'value'	=> $data['contact_city']
				);
			}
			if (!empty($data['contact_zip'])){
				$additional_data[] = array(
						'label' => JText::_('COM_DJCATALOG2_CONTACT_ZIP_LABEL'),
						'value'	=> $data['contact_zip']
				);
			}
			if (!empty($data['contact_country'])){
				$additional_data[] = array(
						'label' => JText::_('COM_DJCATALOG2_CONTACT_COUNTRY_LABEL'),
						'value'	=> $data['contact_country']
				);
			}
			
			if (count($additional_data) > 0) {
				$body .= "\r\n\r\n".JText::_('COM_DJCATALOG2_CONTACT_ADDITIONAL_DATA');
				foreach ($additional_data as $k=>$v) {
					$body .= "\r\n".$v['label'].': '.$v['value'];
				}
			}

			// Prepare email body
			$prefix = JText::sprintf('COM_DJCATALOG2_ENQUIRY_TEXT', JURI::base(), $item->name);
			$body	= $prefix."\n".$name.' <'.$email.'>'."\r\n\r\n".stripslashes($body)."\r\n\r\n".JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->cat_slug), false, -1);

			$mail = JFactory::getMailer();

			//$mail->addRecipient($mailfrom);
			foreach ($recipient_list as $recipient) {
				$mail->addBCC(trim($recipient));
			}
			
			$mail->addReplyTo(array($email, $name));
			
			if ((int)$params->get('contact_sender', 0) == 1) {
				$mail->setSender(array($email, $name));
			} else {
				$mail->setSender(array($mailfrom, $fromname));
			}
			
			$mail->setSubject($sitename.': '.$subject);
			$mail->setBody($body);
			$sent = $mail->Send();

			//If we are supposed to copy the sender, do so.

			// check whether email copy function activated
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

			return $sent;
	}
}
