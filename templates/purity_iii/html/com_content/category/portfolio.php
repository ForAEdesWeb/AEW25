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

// no direct access
defined('_JEXEC') or die('Restricted access');

$cols = $this->params->get('num_columns', 3);
$span = floor(12 / $cols);
$key = 0;
$items = $this->items;
?>

<div class="porfolio<?php if ($this->params->get('pageclass_sfx')) echo ' ' . $this->params->get('pageclass_sfx'); ?>" itemscope itemtype="http://schema.org/Blog">

	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
	<?php endif; ?>

	<?php //JAHelper::loadModules('inline') ?>
	<!-- Item list -->
	<div class="porfolio-items">

	<?php foreach ($items as $item):
		?>
		<?php if ($key % $cols == 0) : ?>
		<!-- Row -->
		<div class="row row-porfolio">
	<?php endif ?>

			<div class="col-md-<?php echo $span ?>">
				<?php
				// Load category_item.php by default
				$this->item = $item;
				echo $this->loadTemplate('item');
				?>
			</div>

			<?php if ((($key+1) % $cols == 0) || $key+1 == count($this->items)) : ?>
			</div>
			<!-- // Row -->
		<?php endif ?>
			<?php
			$key++;
		endforeach; ?>

	</div>
	<!-- // Item list -->

	<!-- Pagination -->
	<?php
	if ($this->pagination->getPagesLinks()): ?>
		<div class="pagination-wrap">
			<?php echo $this->pagination->getPagesLinks(); ?>
			<p class="counter pagination-counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		</div>
	<?php endif; ?>
	<!-- //Pagination -->

</div>
