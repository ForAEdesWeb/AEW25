<?php
/**
 * @version $Id: djcatalog2.php 285 2014-06-13 10:03:52Z michal $
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

defined( '_JEXEC' ) or die( 'Restricted access' );

$version = new JVersion;
if (version_compare($version->getShortVersion(), '2.5.5', '<')) {
	$app = JFactory::getApplication();
	$app->redirect(JRoute::_('index.php'), 'ERROR: DJ-Catalog2 requires at least Joomla! ver. 2.5.5. Older versions are not supported!', 'error');
}

if (!JFactory::getUser()->authorise('core.manage', 'com_djcatalog2')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$lang = JFactory::getLanguage();
if ($lang->get('lang') != 'en-GB') {
    $lang = JFactory::getLanguage();
    $lang->load('com_djcatalog2', JPATH_ADMINISTRATOR, 'en-GB', false, false);
    $lang->load('com_djcatalog2', JPATH_COMPONENT_ADMINISTRATOR, 'en-GB', false, false);
    $lang->load('com_djcatalog2', JPATH_ADMINISTRATOR, null, true, false);
    $lang->load('com_djcatalog2', JPATH_COMPONENT_ADMINISTRATOR, null, true, false);
}

// DJ-Catalog2 version no.
$db = JFactory::getDBO();
$db->setQuery("SELECT manifest_cache FROM #__extensions WHERE type='component' AND element='com_djcatalog2' LIMIT 1");
$version = json_decode($db->loadResult());
$version = (empty($version->version)) ? 'undefined' : $version->version;

define('DJCATVERSION', $version);

$year = JFactory::getDate()->format('Y');
define('DJCATFOOTER', '<div style="text-align: center; margin: 10px 0;">DJ-Catalog2 (ver. '.DJCATVERSION.'), &copy; 2009-'.$year.' Copyright by <a target="_blank" href="http://dj-extensions.com">dj-extensions.com</a>, All Rights Reserved.<br /><a target="_blank" href="http://dj-extensions.com"><img src="'.JURI::base().'components/com_djcatalog2/assets/images/djextensions.png" alt="dj-extensions.com" style="margin-top: 20px;"/></a></div>');

define ('DJCATIMGFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'images');
define ('DJCATIMGURLPATH', str_replace('/administrator', '', JURI::base()).'media/djcatalog2/images');

define ('DJCATATTFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'files');
define ('DJCATATTURLPATH', str_replace('/administrator', '', JURI::base()).'media/djcatalog2/files');

jimport('joomla.utilities.string');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.controller');

$version = new JVersion;

require_once(JPATH_COMPONENT.DS.'lib'.DS.'categories.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'events.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djlicense.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'file.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'image.php');


// TODO
//require_once(JPATH_COMPONENT.DS.'lib'.DS.'customfield.php');
//require_once(JPATH_COMPONENT.DS.'lib'.DS.'customfields'.DS.'text.php');

require_once(JPATH_COMPONENT.DS.'lib'.DS.'upload.php');

$dispatcher = JDispatcher::getInstance();
new Djcatalog2Event($dispatcher);

$document = JFactory::getDocument();
if ($document->getType() == 'html') {
	 if (version_compare($version->getShortVersion(), '3.0.0', '<')) { 
	 	$document->addStyleSheet(JURI::base().'components/com_djcatalog2/assets/css/adminstyle_legacy.css');
	 }
	 else {
		$document->addStyleSheet(JURI::base().'components/com_djcatalog2/assets/css/adminstyle.css');
	 }
}

$controller	= JControllerLegacy::getInstance('Djcatalog2');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
