<?php
/**
 * @version $Id: controller.php 209 2013-11-18 17:18:01Z michal $
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

class Djcatalog2Controller extends JControllerLegacy
{
	protected $default_view = 'cpanel';
	
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/djcatalog2.php';
		Djcatalog2Helper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'cpanel'));
		parent::display($cachable, $urlparams);
	}
	public function download_file() {
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		if (!$user->authorise('core.manage', 'com_djcatalog2') && !$user->authorise('core.admin', 'com_djcatalog2')){
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$path = $app->input->get('path', null, 'base64');
		$file_path = JPATH_ROOT.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, base64_decode($path));
		
		if (empty($path) || !JFile::exists($file_path)) {
			$this->setRedirect( 'index.php?option=com_djcatalog2', JText::sprintf('COM_DJCATALOG2_ERROR_FILE_MISSING', base64_decode($path)), 'error' );
			return false;
		}
		
		if (!DJCatalog2FileHelper::getFileByPath($file_path)){
			//JError::raiseError(404);
			throw new Exception('', 404);
			return false;
		}
		return true;
	}
	
	public function multiupload() {
	
		// todo: secure upload from injections
		$user = JFactory::getUser();
		if (!$user->authorise('core.manage', 'com_djcatalog2')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
	
		DJCatalog2UploadHelper::upload();
	
		return true;
	}
}

?>