<?php
/**
 * @version $Id: mod_djc2filters.php 140 2013-09-09 07:42:05Z michal $
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

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
$app = JFactory::getApplication();
$option = $app->input->get('option', '', 'string');
$view = $app->input->get('view', '', 'string');
$cid = $app->input->getInt('cid', 0);
	
$visibility = $params->get('visibility', null);

if ($visibility == '1' && !($option == 'com_djcatalog2' && $view == 'items')) {
	return false;
}

if ($visibility == '2' && !($option == 'com_djcatalog2' && ($view == 'items' || $view == 'item'))) {
	return false;
}

$categories = $params->get('categories');
if (!empty($categories)) {
	if (!($option == 'com_djcatalog2' && in_array($cid, $categories))) {
		return false;
	}
}

require_once(dirname(__FILE__).DS.'helper.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'route.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'theme.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'djcatalog2.php');
DJCatalog2ThemeHelper::setThemeAssets();

$data = DJC2FiltersModuleHelper::getData($params);



$isempty = true;
foreach($data as $key => $group) {
	$data[$key]->isempty = true;
	foreach ($group->attributes as $item) {
		if (!empty($item->selectedOptions) || $item->availableOptions > 0) {
			$isempty = false;
			$data[$key]->isempty = false;
		}
	}
}

if ($isempty == false) {
	require(JModuleHelper::getLayoutPath('mod_djc2filters'));
}
else {
	return false;
}



