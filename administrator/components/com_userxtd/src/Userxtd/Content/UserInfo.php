<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Userxtd\Content;

use Userxtd\Router\Route;
use Windwalker\DI\Container;
use Windwalker\Helper\LanguageHelper;
use Windwalker\Image\Thumb;
use Windwalker\System\ExtensionHelper;
use Windwalker\View\Layout\FileLayout;

// No direct access
defined('_JEXEC') or die;

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
	 * @param Container  $container
	 * @param \stdClass  $article
	 * @param mixed      $params
	 *
	 * @return  string
	 */
	public static function createInfoBox(Container $container, $article, $params = null)
	{
		// Include Component Core
		$param = ExtensionHelper::getParams('com_userxtd');
		$doc   = \JFactory::getDocument();
		$app   = $container->get('app');

		LanguageHelper::loadLanguage('com_userxtd', 'admin');

		// init params
		$image_field   = $param->get('UserInfo_ImageField', 'BASIC_AVATAR');
		$website_field = $param->get('UserInfo_WebiteField', 'BASIC_WEBSITE');
		$include_css   = $param->get('UserInfo_IncludeCSS_Article', 1);

		// Image params
		$width  = $param->get('UserInfo_ImageWidth', 150);
		$height = $param->get('UserInfo_ImageHeight', 150);
		$crop   = $param->get('UserInfo_ImageCrop', 1) ? \JImage::CROP_RESIZE : false;

		// Include CSS
		if($include_css)
		{
			/** @var $asset \Windwalker\Helper\AssetHelper */
			$asset = $container->get('helper.asset');

			$asset->addCss('userxtd-userinfo.css', 'com_userxtd');
		}

		$user = \UXFactory::getUser($article->created_by);

		// Images
		$image  = $user->get($image_field);
		$thumb  = new Thumb(null, 'com_userxtd');
		$image 	= $thumb->resize($image, $width, $height, $crop);

		// Link
		$query = array(
			'option' => 'com_users',
			'view' => 'profile',
			'id' => $user->get('id')
		);

		$link = \JRoute::_('index.php?' . http_build_query($query));
			//Route::_('com_userxtd.user_id', array('id' => $user->get('id')));

		// Website
		$website_link = $user->get($website_field);

		// Render
		return with(new FileLayout('userxtd.content.userinfo', null, array('component' => 'com_userxtd')))->render(
			array(
				'params' => $param,
				'article' => $article,
				'link' => $link,
				'website_link' => $website_link,
				'image' => $image,
				'user' => $user
			)
		);
	}
}
 