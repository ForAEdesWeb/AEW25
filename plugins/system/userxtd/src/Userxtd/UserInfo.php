<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Userxtd;

/**
 * Class UserInfo
 *
 * @since 1.0
 */
class UserInfo
{
	/**
	 * createInfoBox
	 *
	 * @param $context
	 * @param $article
	 * @param $params
	 *
	 * @return  string
	 */
	public static function createInfoBox($context, $article, $params)
	{
		$input->

		// Detect Context
		$option = JRequest::getVar('option') ;
		$view 	= JRequest::getVar('view') ;
		$layout = JRequest::getVar('layout', 'default') ;
		if( $option != 'com_content' || $view != 'article' || $layout != 'default' ) return ;


		// Include Component Core
		include_once JPATH_ADMINISTRATOR.'/components/com_userxtd/includes/core.php' ;
		$ux 	= plgSystemUserxtd::getInstance();
		$param 	= $ux->params ;
		$app 	= JFactory::getApplication() ;
		$doc 	= JFactory::getDocument();
		UXHelper::_('lang.loadLanguage', 'com_userxtd', 'admin');

		if($app->isAdmin()) return ;

		// init params
		$image_field 	= $param->get('UserInfo_ImageField', 'BASIC_AVATAR');
		$title_field 	= $param->get('UserInfo_TitleField', 'name');
		$about_field 	= $param->get('UserInfo_AboutField', 'BASIC_ABOUT');
		$website_field 	= $param->get('UserInfo_WebiteField', 'BASIC_WEBSITE');
		$width 			= $param->get('UserInfo_ImageWidth', 150);
		$height 		= $param->get('UserInfo_ImageHeight', 150);
		$crop 			= $param->get('UserInfo_ImageCrop', 1);
		$include_css	= $param->get('UserInfo_IncludeCSS_Article', 1);


		// Include CSS
		if($include_css) {
			$doc->addStyleSheet('administrator/components/com_userxtd/includes/bootstrap/css/bootstrap.min.css');
			$doc->addStyleSheet('components/com_userxtd/includes/css/userxtd-userinfo.css');
		}


		// handle params
		$user 	= UXFactory::getUser($article->created_by);
		$image 	= $user->get($image_field) ;
		$image 	= AKHelper::_('thumb.resize', $image, $width, $height, $crop) ;

		$link	= JRoute::_('index.php?option=com_userxtd&view=user&id='.$user->get('id'));
		$link 	= JHtml::link($link, JText::_('COM_USERXTD_USER_INFO_MORE'));

		$website_link = $user->get($website_field) ;
		$website_link = $website_link ? JHtml::link($website_link, JText::_('COM_USERXTD_USER_INFO_WEBSITE')) : null;


		// Get Template override
		$tpl 	= $app->getTemplate();
		$file 	= JPATH_THEMES."/{$tpl}/html/plg_userxtd/content/userInfo.php" ;

		if(!JFile::exists($file)) {
			$file = dirname(__FILE__).'/tmpl/userInfo.php' ;
		}


		// Start capturing output into a buffer
		ob_start();
		// Include the requested template filename in the local scope
		include $file;

		// Done with the requested template; get the buffer and clear it.
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}
 