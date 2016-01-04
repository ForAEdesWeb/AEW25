<?php
/**
 * @version $Id: mod_djc2relateditems.php 120 2013-02-19 08:05:17Z michal $
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

require_once(dirname(__FILE__).DS.'helper.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'defines.djcatalog2.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'route.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'html.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'theme.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djcatalog2'.DS.'lib'.DS.'categories.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'image.php');

$document= JFactory::getDocument();
$module_id = $module->id;

DJCatalog2ThemeHelper::setThemeAssets();

$module_css = array();
$module_float 	= $params->get('module_float','');
$module_width 	= $params->get('module_width','');
$module_height 	= $params->get('module_height','');
$module_text_align = $params->get('module_text_align','');
if ($module_float == 'left') {
	$module_css[] = 'float: left;';
	//$module_css[] = 'clear: right;';
	$module_css[] = 'margin: auto;';
} else if ($module_float == 'right') {
	$module_css[] = 'float: right;';
	//$module_css[] = 'clear: left;';
	$module_css[] = 'margin: auto;';
}
if ($module_text_align) {
	$module_css[] = 'text-align: '.$module_text_align.';';
}
if (preg_match('#^(\d+)(px|%)?$#', $module_width, $width_matches)) {
	$unit = 'px';
	$width = $width_matches[1];
	if (count($width_matches) == 3) {
		$unit = $width_matches[2];
	}
	$module_css[] = 'width: '.$width.$unit.';';
}
if (preg_match('#^(\d+)(px|%)?$#', $module_height, $height_matches)) {
	$unit = 'px';
	$height = $height_matches[1];
	if (count($height_matches) == 3) {
		$unit = $height_matches[2];
	}
	$module_css[] = 'height: '.$height.$unit.';';
}
if (!empty($module_css)) {
	$css_style = '#mod_djc_items-'.$module_id.' .mod_djc_item {'.implode(PHP_EOL, $module_css).'}';
	$document->addStyleDeclaration($css_style);
}

$helperClass = new modDjc2RelateditemsHelper($params);
$items = $helperClass->getData();

$layout = 'default';
if ($items) {
	require(JModuleHelper::getLayoutPath('mod_djc2relateditems',$layout));
} else return false;

?>
