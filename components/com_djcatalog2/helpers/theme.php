<?php
/**
 * @version $Id: theme.php 300 2014-07-04 11:09:09Z michal $
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

require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'defines.djcatalog2.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'djcatalog2.php');

class DJCatalog2ThemeHelper {
	static $themeSet = null;
	static $themeName = null;
	
	public static function setThemeAssets() {
		if (!isset(self::$themeSet)) {
			$app = JFactory::getApplication();
			$document = JFactory::getDocument();
			$params = Djcatalog2Helper::getParams(); //$app->getParams('com_djcatalog2');
			
			$theme = self::getThemeName();
			
			//TODO jquery scripts
			$jquery = false;
			
			JHTML::_('behavior.framework', true);
			JHTML::_('behavior.modal');
			
			$lightboxes = array('slimbox', 'picbox');
			
			$slimboxJs = JURI::base().'components/com_djcatalog2/assets/slimbox-1.8/js/slimbox.js';
			$slimboxCss = JURI::base().'components/com_djcatalog2/assets/slimbox-1.8/css/slimbox.css';
			
			$lightboxes['slimbox']['css'] = $slimboxCss;
			$lightboxes['slimbox']['js'] = $slimboxJs;
			
			$picboxJs = JURI::base().'components/com_djcatalog2/assets/picbox/js/picbox.js';
			$picboxCss = JURI::base().'components/com_djcatalog2/assets/picbox/css/picbox.css';
			
			$lightboxes['picbox']['css'] = $picboxCss;
			$lightboxes['picbox']['js'] = $picboxJs;
			
			$document->addStyleSheet($lightboxes[$params->get('lightbox_type', 'slimbox')]['css']);
			$document->addScript($lightboxes[$params->get('lightbox_type', 'slimbox')]['js']);
			
			$theme_css_url = JURI::base().'components/com_djcatalog2/themes/default/css/theme.css';
			$theme_responsive_url = JURI::base().'components/com_djcatalog2/themes/default/css/responsive.css';
			
			$theme_js_file = $jquery ? 'theme.jquery.js' : 'theme.js';
			$theme_js_url = JURI::base().'components/com_djcatalog2/themes/default/js/'.$theme_js_file;
			
			
			$theme_class_file = DJCATCOMPONENTPATH.DS.'themes'.DS.'default'.DS.'theme.php';
			$theme_class = 'Djcatalog2Theme';
			
			if (JFile::exists( DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'css'.DS.'theme.css' )) {
				$theme_css_url = JURI::base().'components/com_djcatalog2/themes/'.$theme.'/css/theme.css';
			}
			if (JFile::exists( DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'css'.DS.'responsive.css' )) {
				$theme_responsive_url = JURI::base().'components/com_djcatalog2/themes/'.$theme.'/css/responsive.css';
			} 
			if (JFile::exists( DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'js'.DS.$theme_js_file )) {
				$theme_js_url = JURI::base().'components/com_djcatalog2/themes/'.$theme.'/js/'.$theme_js_file;
			}
			if (JFile::exists( DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'theme.php' )) {
				$theme_class_file = DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'theme.php';
			}
			
			$document->addStyleSheet($theme_css_url);
			
			if ($params->get('theme_responsive', '1') == '1') {
				$document->addStyleSheet($theme_responsive_url);
			}
			
			$document->addScript($theme_js_url);
			
			if (!class_exists($theme_class)) {
				if (file_exists($theme_class_file)) {
					require_once $theme_class_file;
				}
			}
			if (class_exists($theme_class)) {
				$themeClass = new $theme_class();
				if (method_exists($themeClass, 'setStyles')) {
					$themeClass->setStyles($params);				
				}
			}
			self::$themeSet = true;
		}
	}
	public static function getThemeImage($filename) {
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		//$params = $app->getParams('com_djcatalog2');
		$params = Djcatalog2Helper::getParams();
		$theme = $params->get('theme','default');
		if (JFile::exists(DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'images'.DS.$filename)) {
			return JURI::base().'components/com_djcatalog2/themes/'.$theme.'/images/'.$filename;
		} else if (JFile::exists(DJCATCOMPONENTPATH.DS.'themes'.DS.'default'.DS.'images'.DS.$filename)) {
			return JURI::base().'components/com_djcatalog2/themes/default/images/'.$filename;
		} else {
			return '';
		}
	}
	public static function getThemeName() {
		if (!self::$themeName) {
			$app = JFactory::getApplication();
			$document = JFactory::getDocument();
			//$params = $app->getParams('com_djcatalog2');
			$params = Djcatalog2Helper::getParams();
			self::$themeName = $params->get('theme','default');
		}
		return self::$themeName;
	}
}