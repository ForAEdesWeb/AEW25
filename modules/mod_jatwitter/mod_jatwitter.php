<?php
/**
 * ------------------------------------------------------------------------
 * JA Twitter Module for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__)  . '/jatwitter.php');
require_once (dirname(__FILE__)  . '/helper.php');
require_once (dirname(__FILE__)  . '/TwitterAPIExchange.php');

include_once(dirname(__FILE__) . '/assets/asset.php');

// get params.
$is_ajax 			= $params->get('is_ajax');
$taccount 			= $params->get('taccount');
$show_limit 		= $params->get('show_limit',1);
$headingtext 		= $params->get('headingtext');
$showfollowlink 	= $params->get('showfollowlink', 1);
$showtextheading 	= $params->get('showtextheading');
$displayitem 		= $params->get('displayitem');
$showIcon 			= $params->get('show_icon', 1);
$showUsername 		= $params->get('show_username', 1);
$showSource 		= $params->get('show_source', 1);
$apikey 			= $params->get('apikey');
$screenName 		= $params->get('taccount', 'JoomlartDemo');
$layout 			= $is_ajax ? 'default.ajax' : 'default';
$useDisplayAccount 	= $params->get('use_display_taccount', 0);
$useFriends 		= $params->get('use_friends', 0);
$iconsize 			= $params->get('icon_size', 48);
$sizeIconaccount 	= $params->get('size_iconaccount', 48);
$sizeIconfriend 	= $params->get('size_iconfriend', 24);

$consumer_key 		= $params->get('consumer_key', '');
$consumer_secret 	= $params->get('consumer_secret', '');
$oauth_access_token = $params->get('oauth_access_token', '');
$oauth_access_token_secret = $params->get('oauth_access_token_secret', '');

$jatHerlper = new modJaTwitterHelper($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);
// render layout


// enable or disable using cache data
$jatHerlper->setCache($params->get('enable_cache'), $params->get('cache_time'));
// if show account information
if ($useDisplayAccount) {
    $accountInfo = $jatHerlper->getTwitter('show', $screenName);
}
if ($useFriends) {
    $friends = $jatHerlper->getTwitter('friends', $screenName, $params->get('max_friends', 10));
}
$list = array();
if ($params->get('show_tweet') == '1') {
	if ($show_limit!=0) {
		$list = $jatHerlper->getTwitter("user_timeline", $screenName, $show_limit, (int) $params->get('tweets_cachetime', 30));
	}
}

$layout = 'default';
include (JModuleHelper::getLayoutPath('mod_jatwitter', $layout));