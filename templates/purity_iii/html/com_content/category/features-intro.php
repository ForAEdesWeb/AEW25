<?php
 /**
 *------------------------------------------------------------------------------
 * @package Purity III Template - JoomlArt
 * @version 1.0 Feb 1, 2014
 * @author JoomlArt http://www.joomlart.com
 * @copyright Copyright (c) 2004 - 2014 JoomlArt.com
 * @license GNU General Public License version 2 or later;
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

JHtml::_('behavior.caption');

$items = $this->items;
?>
<div class="blog<?php echo $this->pageclass_sfx;?> features-intro">

	<?php if (!empty($items)) : ?>
		<?php foreach ($items as &$item) : ?>
			<?php
			$this->item = &$item;
			echo $this->loadTemplate('item');
			?>
		<?php endforeach; ?>
	<?php endif; ?>

</div>
