<?php
/**
 * Part of Component Quickcontent files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';

JLoader::registerPrefix('Quickcontent', JPATH_BASE . '/components/com_quickcontent');
JLoader::registerNamespace('Quickcontent', JPATH_ADMINISTRATOR . '/components/com_quickcontent/src');
JLoader::registerNamespace('Windwalker', __DIR__);
JLoader::register('QuickcontentComponent', JPATH_BASE . '/components/com_quickcontent/component.php');
