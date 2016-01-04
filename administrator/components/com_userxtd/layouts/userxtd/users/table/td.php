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
$item = $displayData['grid']->current;
$key = $displayData['key'];

$content = $item->$key;

$content = JFilterOutput::cleanText($content);
?>
<td class="nowrap center hasTooltip" title="<?php echo strip_tags($content); ?>">
	<div class="field-value-wrap" style=" max-height: 40px; text-overflow:ellipsis; overflow: hidden;">
		<?php echo $content; ?>
	</div>
</td>
