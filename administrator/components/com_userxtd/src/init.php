<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

JLoader::registerPrefix('Userxtd', JPATH_BASE . '/components/com_userxtd');
JLoader::registerNamespace('Userxtd', JPATH_ADMINISTRATOR . '/components/com_userxtd/src');
JLoader::registerNamespace('Windwalker', __DIR__);
JLoader::register('UserxtdComponent', JPATH_BASE . '/components/com_userxtd/component.php');

// UserXTD API
\JLoader::register('UXFactory', __DIR__ . '/Class/factory.php');

// CCK
$container = \Windwalker\DI\Container::getInstance('com_userxtd');

$container->registerServiceProvider(new \Windwalker\CCK\CCKProvider);
