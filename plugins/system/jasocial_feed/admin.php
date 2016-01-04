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

if(!defined('_JEXEC')) {
	// Set flag that this is a parent file
	define('_JEXEC', 1);
	define('DS', DIRECTORY_SEPARATOR);
	
	if (!defined('_JDEFINES')) {
		define('JPATH_BASE', dirname(dirname(dirname(dirname(__FILE__)))).DS.'administrator');
		require_once JPATH_BASE.'/includes/defines.php';
	}
	
	require_once JPATH_BASE.'/includes/framework.php';
	require_once JPATH_BASE.'/includes/helper.php';
	require_once JPATH_BASE.'/includes/toolbar.php';
	
	// Mark afterLoad in the profiler.
	JDEBUG ? $_PROFILER->mark('afterLoad') : null;
	
	// Instantiate the application.
	$app = JFactory::getApplication('administrator');
	
	// Initialise the application.
	$app->initialise();
	
	// Mark afterIntialise in the profiler.
	JDEBUG ? $_PROFILER->mark('afterInitialise') : null;
}


jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

//admin section user
$user = JFactory::getUser();

$result = new stdClass();
$result->status = 'error';
$result->message = 'error';

$lang = JFactory::getLanguage();
$lang->load('mod_dnp_skitter');


require_once(dirname(__FILE__).'/helpers.php');
$helper = new jaSocialFeedAdminHelper();

if (!$user->id) {
    $helper->doResponse(null, 0, JText::_('NO_PERMISSION'));
} else {
	
	$task = JRequest::getVar('jatask', '');
	
	if ($task != '') {
		
		if($task != 'doResponse' && method_exists($helper, $task)) {
			call_user_func_array(array($helper, $task), array());
		} else {
			$helper->doResponse(null, 0, JText::sprintf('Invalid method: %s', $task));
		}
	
	
	    //JAAdminHelper::$task();
	} else {
		$helper->doResponse(null, 0, JText::_('NO_SPECIFIC_ACTION'));
	}
}
