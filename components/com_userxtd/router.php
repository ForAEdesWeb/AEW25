<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use Windwalker\Router\CmsRouter;
use Windwalker\Router\Helper\RoutingHelper;

include_once JPATH_ADMINISTRATOR . '/components/com_userxtd/src/init.php';

// Prepare Router
$router = CmsRouter::getInstance('com_userxtd');

// Register routing config and inject Router object into it.
$router = RoutingHelper::registerRouting($router, 'com_userxtd');

/**
 * UserxtdBuildRoute
 *
 * @param array &$query
 *
 * @return  array
 */
function UserxtdBuildRoute(&$query)
{
	$segments = array();

	$router = CmsRouter::getInstance('com_userxtd');

	$query = \Windwalker\Router\Route::build($query);

	if (!empty($query['_resource']))
	{
		$segments = $router->build($query['_resource'], $query);

		unset($query['_resource']);
	}
	else
	{
		$segments = $router->buildByRaw($query);
	}

	return $segments;
}

/**
 * UserxtdParseRoute
 *
 * @param array $segments
 *
 * @return  array
 */
function UserxtdParseRoute($segments)
{
	$router = CmsRouter::getInstance('com_userxtd');

	$segments = implode('/', $segments);

	// OK, let's fetch view name.
	$view = $router->getView(str_replace(':', '-', $segments));

	if ($view)
	{
		return array('view' => $view);
	}

	return array();
}
