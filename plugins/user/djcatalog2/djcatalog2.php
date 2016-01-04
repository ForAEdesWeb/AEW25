<?php
/**
 * @version $Id: djcatalog2.php 317 2014-07-23 10:36:21Z michal $
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.utilities.date');

class plgUserDjcatalog2 extends JPlugin
{
	public static $component_language_loaded = false;
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$lang = JFactory::getLanguage();
		if (self::$component_language_loaded == false) {
			$lang = JFactory::getLanguage();
			if (JFactory::getApplication()->isSite()) {
				$lang->load('com_djcatalog2', JPATH_ROOT, 'en-GB', false, false);
				$lang->load('com_djcatalog2', JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2', 'en-GB', false, false);
				$lang->load('com_djcatalog2', JPATH_ROOT, null, true, false);
				$lang->load('com_djcatalog2', JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2', null, true, false);
			}
			else {
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR, 'en-GB', false, false);
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2', 'en-GB', false, false);
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR, null, true, false);
				$lang->load('com_djcatalog2', JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2', null, true, false);
			}
			self::$component_language_loaded = true;
		}
		
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		require_once(JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2'.DIRECTORY_SEPARATOR.'defines.djcatalog2.php');
		require_once(JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');
		
	}
	
	function onContentPrepareData($context, $data)
	{
		if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile')))
		{
			return true;
		}
		
		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->djcatalog2profile) and $userId > 0)
			{
				// Load the profile data from the database.
				$db = JFactory::getDbo();
				$db->setQuery('SELECT u.*, c.country_name FROM #__djc2_users as u INNER JOIN #__djc2_countries as c on c.id = u.country_id ' .
						' WHERE user_id = '.(int) $userId);
				$results = $db->loadAssoc();
				
				// Check for a database error.
				if ($db->getErrorNum())
				{
					$this->_subject->setError($db->getErrorMsg());
					return false;
				}
				
				if (count($results)) {
					// Merge the profile data.
					$data->djcatalog2profile = array();
					foreach ($results as $k=>$v)
					{
						$data->djcatalog2profile[$k] = $v;
					}	
				}
			}
		}
		
		if (!JHtml::isRegistered('users.country_id'))
		{
			JHtml::register('users.country_id', array(__CLASS__, 'country'));
		}
		
		
		if (!JHtml::isRegistered('users.www'))
		{
			JHtml::register('users.www', array(__CLASS__, 'www'));
		}

		return true;
	}

	function onContentPrepareForm($form, $data)
	{
		$app = JFactory::getApplication();
		
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form->getName();
		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration')))
		{
			return true;
		}
		
		/*if ($app->isSite() && $form->getXML()  instanceof SimpleXMLElement) {
			$form->setFieldAttribute('name', 'type', 'hidden');
			$form->setFieldAttribute('name', 'required', null);
			$form->setFieldAttribute('name', 'validate', null);
			$form->setFieldAttribute('name', 'value', '');
			$form->setFieldAttribute('name', 'default', '');
			
			$document = JFactory::getDocument();
			$document->addScript(JURI::base() . "plugins/user/djcatalog2/forms/js/username.js");
			$document->addScriptDeclaration('
						window.addEvent("domready", function(){
							plguserdjc2.init();
						});
					');
			
		}*/

		// Add the registration fields to the form.
		JForm::addFormPath(JPath::clean(JPATH_ROOT.'/components/com_djcatalog2/models/forms'));

		$form->loadFile('userprofile', false);
		
		// ADMIN should not be required to fill-in all the fields
		
		$form->removeField('email', 'djcatalog2profile');
		$form->removeField('customer_note', 'djcatalog2profile');
			
		$form->removeField('customer_group_id', 'djcatalog2profile');
		$form->removeField('client_type', 'djcatalog2profile');
		$form->removeField('captcha', 'djcatalog2profile');
		
		if ($app->isAdmin()) {
			$catalog_fields = $form->getFieldset('basicprofile');
			foreach ($catalog_fields as $field) {
				$form->setFieldAttribute($field->fieldname, 'required', null, 'djcatalog2profile');
				$class = $form->getFieldAttribute($field->fieldname, 'class', '', 'djcatalog2profile');
				$class = str_replace('required', '', $class);
				$form->setFieldAttribute($field->fieldname, 'class', $class, 'djcatalog2profile');
			}
			
		} else {
			$app = JFactory::getApplication();
			
			$fields = array('firstname', 'lastname', 'company', 'position', 'address', 'city', 'postcode', 'country_id', 'vat_id', 'phone', 'fax', 'www', 'customer_note');
			
			$group = 'djcatalog2profile';
			$params = $this->params;
			
			foreach ($fields as $field) {
				// in case config is broken - using defaults from XML file
				if ($params->get('field_'.$field, false) === false) {
					continue;
				}
					
				if ($params->get('field_'.$field, '0') == '0') {
					$form->removeField($field, $group);
				} else {
					if ($params->get('field_'.$field, '0') == '2') {
						$form->setFieldAttribute($field, 'required', 'required', $group);
						$form->setFieldAttribute($field, 'class', $form->getFieldAttribute($field, 'class').' required', $group);
					} else {
						$form->setFieldAttribute($field, 'required', false, $group);
							
						$class = $form->getFieldAttribute($field, 'class', '', $group);
						$class = str_replace('required', '', $class);
							
						$form->setFieldAttribute($field, 'class', $class, $group);
					}
				}
			}
						
		}
		
		return true;
	}

	function onUserBeforeSave($oldData, $isNew, $data)
	{
		/*if (isset($data['djcatalog2profile']) && (count($data['djcatalog2profile'])))
		{
			if ($data->name == '---' || empty($data->name)) {
				$data->name = $data['djcatalog2profile']['firstname'].' '.$data['djcatalog2profile']['lastname'];
			}
		}*/
		//return false;
	}
	
	function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');
		$user = JFactory::getUser($userId);
		$date = JFactory::getDate();
		$params = JComponentHelper::getParams('com_djcatalog2');
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$files = $app->input->files->get('jform', array());
		

		$company_name = '';
		
		$image_whitelist = explode(',', $image_types);
		foreach($image_whitelist as $key => $extension) {
			$image_whitelist[$key] = strtolower(trim($extension));
		}
		
		if ($userId && $result) {
			if (isset($data['djcatalog2profile']) && (count($data['djcatalog2profile'])))
			{
				try
				{
					
					$row = new stdClass();
					$data['djcatalog2profile']['user_id'] = $userId;
					foreach($data['djcatalog2profile'] as $column => $value) {
						$row->$column = $value;
					}
					
					//if ($app->isAdmin() && (empty($row->firstname) || empty($row->lastname) || empty($row->company) )) {
					if ($app->isAdmin()) {
						$isempty = true;
						foreach ($row as $value) {
							if (!empty($value)) {
								$isempty = false;
								break;
							}
						}
						$this->onUserAfterDelete($user->getProperties(1), true, '');
					} else {
						
						/*$db->setQuery('DELETE FROM #__djc2_users WHERE user_id = '.$userId);
						
						if (!$db->query())
						{
							throw new Exception($db->getErrorMsg());
						}
							
						if (!$db->insertObject('#__djc2_users', $row))
						{
							throw new Exception($db->getErrorMsg());
						}
						*/
						
						$db->setQuery('SELECT * FROM #__djc2_users WHERE user_id = '.$userId);
						if ($djuser = $db->loadObject()) {
							$row->id = $djuser->id;
							$row->customer_group_id = $djuser->customer_group_id;
							$row->client_type = $djuser->client_type;
						} else {
							$db->setQuery('SELECT id FROM #__djc2_customer_groups WHERE is_default=1');
							$group_id = $db->loadResult();
							$row->customer_group_id = $group_id ? $group_id : 0;
							
							$client_type = $params->get('default_client_type', 'R');
							$row->client_type = ($client_type == 'R' || $client_type == 'W') ? $client_type : 'R';
							
						}
						
						if ($row->id > 0) {
							if (!$db->updateObject('#__djc2_users', $row, 'id', true))
							{
								throw new Exception($db->getErrorMsg());
							}
						} else {
							if (!$db->insertObject('#__djc2_users', $row))
							{
								throw new Exception($db->getErrorMsg());
							}
						}				
						
						 
						if (($user->name == '---' || empty($user->name)) && !empty($row->firstname) && !empty($row->firstname)) {
							$new_name = $row->firstname.' '.$row->lastname;
							$user->set('name', $new_name);
							$db->setQuery('update #__users set name='.$db->quote($new_name).' where id='.(int)$userId);
							$db->query();
						}
						$company_name = $row->company;
					}
				}
				catch (JException $e)
				{
					$this->_subject->setError($e->getMessage());
					return false;
				}
			}
		}
		
		/*if ($app->isSite() && $isNew == false && $app->input->get('task') == 'save' && $app->input->get('option') == 'com_users') {
			$app->setUserState('com_users.edit.profile.redirect', JRoute::_(DJCatalogHelperRoute::getMyItemsRoute(), false));
		}*/
		
		return true;
	}

	function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$userId	= JArrayHelper::getValue($user, 'id', 0, 'int');
		$db = JFactory::getDbo();
		if ($userId)
		{
			try
			{
				$db->setQuery('DELETE FROM #__djc2_users WHERE user_id = '.$userId);

				if (!$db->query())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}
	/*
	private function _notifyAfterSave($item, $user)
	{
		$app		= JFactory::getApplication();
		$params 	= JComponentHelper::getParams('com_djcatalog2');
			
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
		
		$body .= "\n\n".JURI::base().'administrator/index.php?option=com_djcatalog2&view=items&filter_search='.urlencode('id:'.$item_id);

		$mail = JFactory::getMailer();
	
		//$mail->addRecipient($mailfrom);
		foreach ($recipient_list as $recipient) {
			$mail->addRecipient(trim($recipient));
		}
		if ($user->guest == false) {
			$mail->addReplyTo(array($email, $name));
		}
		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($sitename.': '.$subject);
		$mail->setBody($body);
		$sent = $mail->Send();
	
		return $sent;
	}
	
	public static function image($value, $alt = '')
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$directory = self::$images_path;
			$image_path = JURI::root().'/'.$directory.'/'.$value;
			
			return '<img src="'.$image_path.'" alt="'.htmlspecialchars($alt).'" />';
		}
	}*/
	
	public static function country($value)
	{
		if (empty($value) || !(int)$value)
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$db = JFactory::getDbo();
			$db->setQuery('select country_name from #__djc2_countries where id ='.(int)$value);
			$country = $db->loadResult();
			
			return (empty($country)) ? JHtml::_('users.value', $value) : $country;
		}
	}
	
	public static function www($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$value = htmlspecialchars($value);
			if (substr ($value, 0, 4) == "http")
			{
				return '<a href="'.$value.'" target="_blank">'.$value.'</a>';
			}
			else
			{
				return '<a href="http://'.$value.'" target="_blank">'.$value.'</a>';
			}
		}
	}
}
