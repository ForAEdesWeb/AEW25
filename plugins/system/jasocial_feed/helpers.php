<?php
/**
 * ------------------------------------------------------------------------
 * JA System Social Feed Plugin for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

define('PLG_JASOCIALFEED_PATH', dirname(__FILE__).'/');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

//Load the Plugin language file out of the administration
$lang =  JFactory::getLanguage();
$lang->load('plg_system_jasocial_feed', JPATH_ADMINISTRATOR);

function jaSocialFeedGetProfiles($source = 'facebook') {
	$path = PLG_JASOCIALFEED_PATH.'profiles/'.$source.'/';
	$profiles = array();
	if(!empty($source) && JFolder::exists($path)) {
		$files = JFolder::files($path, '\.json$', false, false);
		
		foreach ($files as $file) {
			$profile = JFile::stripExt($file);
			$profileName = ucwords(str_replace('_', ' ', $profile));
			$profiles[$profile] = $profileName;
		}
	}
	
	return $profiles;
}

function jaSocialFeedGetProfile($source, $profile) {
	$path = PLG_JASOCIALFEED_PATH.'profiles/'.$source.'/'.$profile.'.json';
	if(!JFile::exists($path)) {
		return false;
	}
	$data = JFile::read($path);
	$data = json_decode($data);
	
	return $data;
}

function jaSocialFeedGetDefaultSetting($source) {
	$xmlFile = PLG_JASOCIALFEED_PATH.'jasocial_feed.xml';
	$content = JFile::read($xmlFile);
	$pattern = '/<field.*?name\s*=\s*"('.$source.'_[a-zA-Z0-9_]+)"[^>]*>/i';
	preg_match_all($pattern, $content, $matches);
	
	$obj = new stdClass();
	if(count($matches[0])) {
		$pattern = '/default\s*=\s*"([^"]*)"/i';
		foreach ($matches[0] as $index => $field) {
			$fieldName = $matches[1][$index];
			$obj->{$fieldName} = '';
			if($fieldName != $source.'_account') {
				preg_match($pattern, $field, $match);
				if(isset($match[1])) {
					$obj->{$fieldName} = $match[1];
				}
			}
		}
	}
	return $obj;
}

class jaSocialFeedAdminHelper
{
	private function check_profile($source, $profile) {
		$aSupport = array('facebook', 'twitter', 'youtube', 'vimeo', 'instagram', 'flickr');
		
		if(empty($source)) {
			$this->doResponse(null, 0, JText::_('Source is not specified!'));
		} elseif (!in_array($source, $aSupport)) {
			$this->doResponse(null, 0, JText::sprintf('Source %s is not supported!', $source));
		}
		if(!$profile) {
			$this->doResponse(null, 0, JText::_('Profile is not specified!'));
		}
		
		return true;
	}
	
	public function new_profile() {
		$source = JRequest::getVar('profile_source');
		$profile = JRequest::getString('profile');
		
		$this->check_profile($source, $profile);
		
		$path = PLG_JASOCIALFEED_PATH.'profiles/'.$source.'/'.$profile.'.json';
		if(JFile::exists($path)) {
			$this->doResponse(null, 0, JText::_('Profile is existed!'));
		}
		$data = jaSocialFeedGetDefaultSetting($source);
		$content = json_encode($data);
		$result = JFile::write($path, $content);
		
		if($result) {
	    	//
			$result = new stdClass();
	    	$result->source = $source;
	    	$result->profile = $profile;
	    	$result->data = $data;
			
			$this->doResponse($result, 1, JText::sprintf('Successfully created profile: %s!', $profile));
		} else {
			$this->doResponse(null, 0, JText::sprintf('Failed created profile: %s!', $profile));
		}
	}
	
	public  function edit_profile() {
		$source = JRequest::getVar('profile_source');
		$profile = JRequest::getString('profile');
		
		$this->check_profile($source, $profile);
		
		$path = PLG_JASOCIALFEED_PATH.'profiles/'.$source.'/'.$profile.'.json';
		if(!JFile::exists($path)) {
			$this->doResponse(null, 0, JText::_('Profile is not exists!'));
		}
		$data = JFile::read($path);
		$data = json_decode($data);
		
		$this->doResponse($data, 1, '');
	}
	
	public function save_profile() {
		$source = JRequest::getVar('profile_source');
		$profile = JRequest::getString('profile_'.$source);
		
		$this->check_profile($source, $profile);
		
		$path = PLG_JASOCIALFEED_PATH.'profiles/'.$source.'/';
		if(!JFolder::exists($path)) {
			JFolder::create($path);
		}
		
		//filter data
		$prefix = $source.'_';
		$data = array();
		$post = JRequest::getVar('jform');
		$socialId = '';
		$fieldSocialId = $prefix . 'account';
		if(isset($post['params'])) {
			foreach ($post['params'] as $key => $val) {
				if(strpos($key, $prefix) === 0) {
					if ($key == $fieldSocialId) {
						$socialId = $val;
					}
					$data[$key] = $val;
				}
			}
		}
		if(!count($data)) {
			$this->doResponse(null, 0, JText::_('Invalid Request!'));
		}
		
		//check for duplicated social account
		if(!empty($socialId)) {
			$listProfiles = jaSocialFeedGetProfiles($source);
			
			if(count($listProfiles)) {
				foreach ($listProfiles as $cP => $cPN) {
					$pData = jaSocialFeedGetProfile($source, $cP);
					if ($cP != $profile && isset($pData->{$fieldSocialId}) && $pData->{$fieldSocialId} == $socialId) {
						$this->doResponse(null, 0, JText::sprintf('ALERT_SOCIAL_ACCOUNT_IS_EXISTED', $source, $socialId, $cPN));
					}
				}
			}
		}
		//
		$data = json_encode($data);
		$result = JFile::write($path.$profile.'.json', $data);
		if($result) {
			$this->doResponse(null, 1, JText::sprintf('Successfully saved profile: %s!', $profile));
		} else {
			$this->doResponse(null, 0, JText::sprintf('Failed saved profile: %s!', $profile));
		}
	}
	
	function delete_profile() {
		$source = JRequest::getVar('profile_source');
		$profile = JRequest::getString('profile');
		
		$this->check_profile($source, $profile);
		
		if($profile == 'default') {
			$this->doResponse(null, 0, JText::_('You can not delete Default profile!'));
		}
		
		$listProfiles = jaSocialFeedGetProfiles($source);
		$numProfiles = count($listProfiles);
		if(isset($listProfiles['default'])) {
			$numProfiles = $numProfiles - 1;
		}
		if($numProfiles <= 1) {
			$this->doResponse(null, 0, JText::_('Failed Deleted! You must have at least one profile!'));
		}
		
		$path = PLG_JASOCIALFEED_PATH.'profiles/'.$source.'/'.$profile.'.json';
		if(!JFile::exists($path)) {
			$this->doResponse(null, 0, JText::_('Profile is not exists!'));
		}
		$result = JFile::delete($path);
		if($result) {
			$result = new stdClass();
	    	$result->source = $source;
	    	$result->profile = $profile;
			
			$this->doResponse($result, 1, JText::sprintf('Successfully deleted profile: %s!', $profile));
		} else {
			$this->doResponse(null, 0, JText::sprintf('Failed deleted profile: %s!', $profile));
		}
	}
	
	function doResponse($result, $status = 1, $msg = '') {
		if(empty($msg)) 
			$msg = !$status ? JText::_('Error!') : JText::_('Success!');
		
		$obj = new stdClass();
		$obj->status = $status ? 'success' : 'error';
		$obj->message = $msg;
		$obj->result = $result;
		echo json_encode($obj);
		jexit();
	}
}