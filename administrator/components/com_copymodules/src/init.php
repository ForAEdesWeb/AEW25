<?php
/**
 * Part of Component Copymodules files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

JLoader::registerPrefix('Copymodules', JPATH_BASE . '/components/com_copymodules');
JLoader::registerNamespace('Copymodules', JPATH_ADMINISTRATOR . '/components/com_copymodules/src');
JLoader::registerNamespace('Windwalker', __DIR__);
JLoader::register('CopymodulesComponent', JPATH_BASE . '/components/com_copymodules/component.php');
