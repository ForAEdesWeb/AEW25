<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

// Count average width
$c = count($data->keys);
$c = $c ? $c : 1;
$width = 70 / $c;

$layout = new \Windwalker\View\Layout\FileLayout('userxtd.users.table.th');

foreach ($data->keys as $i => $key)
{
	if (!$key)
	{
		continue;
	}

	$field = $data->filteredFields[$i];

	echo $layout->render(
		array(
			'width' => $width . '%',
			'grid' => $data->grid,
			'field' => $field,
			'key' => $key
		)
	);
}
