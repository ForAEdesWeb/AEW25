<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

$width = $displayData['width'];
?>
<th style="max-width: <?php echo $width; ?>" class="center">
	<?php echo $displayData['grid']->sortTitle($displayData['field']->title, $displayData['key']); ?>
</th>