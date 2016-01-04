<?php
/**
* @version $Id: mod_djc2frontpage.php 263 2014-03-14 08:13:44Z michal $
* @package DJ-Catalog2
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Michal Olczyk - michal.olczyk@design-joomla.eu
*
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

defined('_JEXEC') or die ('Restricted access');

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$app = JFactory::getApplication();

$document = JFactory::getDocument();
JHTML::_('behavior.framework');
JHTML::_('behavior.modal');

require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'theme.php');
DJCatalog2ThemeHelper::setThemeAssets();

$option = $app->input->get('option','','string');

//$layout = $params->get('layout','default');
$layout = 'default';

$style = $params->get('css','default');
$cssStyleSheets = array('default','bootstrap');
if (!in_array($style, $cssStyleSheets)) {
	$style = 'default';
}

$componentParams = $app->getParams('com_djcatalog2');
$categories = null;
if (is_array($params->get('catid')))
{
	$categories = implode('|',$params->get('catid'));
}
else {
	$categories = $params->get('catid');
}

$mid = $module->id;

$css = JURI::base().'modules/mod_djc2frontpage/css/'.$style.'.css';
$js = JURI::base().'modules/mod_djc2frontpage/js/djfrontpage.js';
$document->addStyleSheet($css);

$responsiveWidth = (int)$params->get('responsive_width','');
$responsiveCss = JPATH_BASE.DS.'modules'.DS.'mod_djc2frontpage'.DS.'css'.DS.'responsive.css';

if ($responsiveWidth > 0 && JFile::exists($responsiveCss)) {
	$responsiveStyle = array();
	$responsiveArray[] = '@media screen and (max-width: '.$responsiveWidth.'px) {';
	$cssContents = JFile::read($responsiveCss);
	if ($cssContents) {
		$responsiveArray[] = $cssContents;
	}
	$responsiveArray[] = '}';
	$document->addStyleDeclaration(implode(PHP_EOL, $responsiveArray));
}

$document->addScript($js);
$document->addScriptDeclaration('
//<!--
	var sliderOptions_'.$mid.' = {
		moduleId: \''.$mid.'\',
		baseurl: \''.JURI::base().'index.php?option=com_djcatalog2&format=raw&task=modfp\',
		showcategorytitle: \''. $params->get('showcattitle').'\',
		showtitle: \''. $params->get('showtitle',1).'\',
		showproducer: \''. $params->get('showprodtitle',0).'\',
		showprice: \''. $params->get('showprice',0).'\',
		linktitle: \''. $params->get('linktitle',1).'\',
		showpagination: \''. $params->get('showpagination', 1).'\',
		order: \''. $params->get('orderby').'\',
		orderdir: \''. $params->get('orderbydir',0).'\',
		featured_only: \''. $params->get('featured_only',0).'\',
		featured_first: \''. $params->get('featured_first',0).'\',
		columns: \''. $params->get('cols').'\',
		rows: \''. $params->get('rows').'\',
		allcategories: \''. $params->get('catsw').'\',
		categories: \''. $categories.'\',
		trunc: \''. $params->get('trunc','0').'\',
		trunclimit: \''. $params->get('trunclimit','0').'\',
		modal: \''. $params->get('modalimage','1').'\',
		showreadmore: \''. $params->get('showreadmore','1').'\',
		readmoretext: \''. urlencode($params->get('readmoretext','')).'\',
		url : \'\',
		largewidth : \''.(int)$params->get('largewidth','400').'\',
		largeheight : \''.(int)$params->get('largeheight','240').'\',
		largecrop : \''.(int)$params->get('largeprocess', 1).'\',
		smallwidth : \''.(int)$params->get('smallwidth','90').'\',
		smallheight : \''.(int)$params->get('smallheight','70').'\',
		smallcrop : \''.(int)$params->get('smallprocess', 1).'\'	
	};
	
	window.addEvent(\'domready\', function(){
	    this.DJFrontpage_'. $mid.' = new DJFrontpage(sliderOptions_'.$mid.');
	});
// -->
');

require(JModuleHelper::getLayoutPath('mod_djc2frontpage', $layout));
?>