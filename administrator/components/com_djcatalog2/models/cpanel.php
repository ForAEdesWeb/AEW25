<?php
/**
 * @version $Id: cpanel.php 219 2014-01-22 11:51:06Z michal $
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

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.helper');
jimport( 'joomla.application.component.model');


class DJCatalog2ModelCPanel extends JModelLegacy {

	function __construct()
	{
		parent::__construct();
	}
	
	function performChecks() {
		$app = JFactory::getApplication();
		$menus = $app->getMenu('site');
		$component	= JComponentHelper::getComponent('com_djcatalog2');
		$menu_items		= $menus->getItems('component_id', $component->id);
		
		$checks = array();
		
		$checks['images'] = DJCATIMGFOLDER;
		$checks['custom_images'] = DJCATIMGFOLDER.DS.'custom';
		$checks['attachments'] = DJCATATTFOLDER;
		$checks['licence'] = JPATH_COMPONENT;

		foreach ($checks as $type => $folder) {
			if (!is_writable($folder)) {
				$app->enqueueMessage(JText::_('COM_DJCATALOG2_FOLDER_CHECK_'.strtoupper($type)), 'warning');
			}
		}
		
		if (!extension_loaded('gd')){
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_GD_CHECK_FAIL'), 'warning');
		}
		
		$root_menu_found = false;
		foreach ($menu_items as $item) {
			if (isset($item->query)) {
				if (array_key_exists('view', $item->query)) {
					$cid = 0;
					if (isset($item->query['cid'])) {
						$cid = (int)$item->query['cid'];
					}
					if ($item->query['view'] == 'items' && $cid == 0) {
						$root_menu_found = true;
						break;
					}
				}
			}
		}
		if ($root_menu_found === false) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_MENU_CHECK_FAIL'), 'message');
		}
		
	}
}
?>
