<?php
/**
 * @version $Id: item.raw.php 132 2013-05-20 07:12:44Z michal $
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

class Djcatalog2ControllerItem extends JControllerForm {
	function __construct($config = array())
	{
		$this->view_list = 'items';
		$this->view_item = 'item';
		parent::__construct($config);
		
	}
	function download() {
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$authorised = $user->authorise('djcatalog2.filedownload', 'com_djcatalog2');
		
		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		if ($out = DJCatalog2FileHelper::getFile($app->input->get('fid','','int'))) {
			$app->input->set('format','raw');
			echo $out;
		}
		else {
			JError::raiseError(404);
			return false;
		}
	}
}
?>
