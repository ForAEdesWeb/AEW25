<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Data\Data;

// No direct access
defined('_JEXEC') or die;

// Prepare script
JHtmlBehavior::multiselect('adminForm');

/**
 * Prepare data for this template.
 *
 * @var $container Windwalker\DI\Container
 * @var $data      Windwalker\Data\Data
 * @var $asset     Windwalker\Helper\AssetHelper
 * @var $grid      Windwalker\View\Helper\GridHelper
 * @var $date      \JDate
 */
$container = $this->getContainer();
$asset     = $container->get('helper.asset');
$grid      = $data->grid;
$date      = $container->get('date');

// Set order script.
$grid->registerTableSort();
?>

<!-- LIST TABLE -->
<table id="fieldList" class="table table-striped table-bordered adminlist">

<!-- TABLE HEADER -->
<thead>
<tr>
	<!--SORT-->
	<th width="1%" class="nowrap center hidden-phone">
		<?php echo $grid->orderTitle(); ?>
	</th>

	<!--CHECKBOX-->
	<th width="1%" class="center">
		<?php echo JHtml::_('grid.checkAll'); ?>
	</th>

	<!--STATE-->
	<th width="5%" class="nowrap center">
		<?php echo $grid->sortTitle('JSTATUS', 'field.published'); ?>
	</th>

	<!--CATEGORY-->
	<th width="10%" class="center">
		<?php echo $grid->sortTitle('JCATEGORY', 'category.title'); ?>
	</th>

	<!--TITLE-->
	<th class="center">
		<?php echo $grid->sortTitle('JGLOBAL_TITLE', 'field.title'); ?>
	</th>

	<th class="center">
		<?php echo $grid->sortTitle('LIB_WINDWALKER_FIELD_TYPE', 'field.field_type'); ?>
	</th>

	<th class="center">
		<?php echo $grid->sortTitle('LIB_WINDWALKER_FIELD_ATTR_LABEL', 'field.label'); ?>
	</th>

	<th class="center">
		<?php echo $grid->sortTitle('LIB_WINDWALKER_FIELD_ATTR_NAME', 'field.name'); ?>
	</th>

	<th width="5%" class="nowrap center">
		<?php echo $grid->sortTitle( 'JOPTION_REQUIRED', 'a.required'); ?>
	</th>

	<!--USER-->
	<th width="10%" class="center">
		<?php echo $grid->sortTitle('JAUTHOR', 'user.name'); ?>
	</th>

	<!--ID-->
	<th width="1%" class="nowrap center">
		<?php echo $grid->sortTitle('JGRID_HEADING_ID', 'field.id'); ?>
	</th>
</tr>
</thead>

<!--PAGINATION-->
<tfoot>
<tr>
	<td colspan="15">
		<div class="pull-left">
			<?php echo $data->pagination->getListFooter(); ?>
		</div>
	</td>
</tr>
</tfoot>

<!-- TABLE BODY -->
<tbody>
<?php foreach ($data->items as $i => $item)
	:
	// Prepare data
	$item = new Data($item);

	// Prepare item for GridHelper
	$grid->setItem($item, $i);
	?>
	<tr class="field-row" sortable-group-id="<?php echo $item->catid; ?>">
		<!-- DRAG SORT -->
		<td class="order nowrap center hidden-phone">
			<?php echo $grid->dragSort(); ?>
		</td>

		<!--CHECKBOX-->
		<td class="center">
			<?php echo JHtml::_('grid.id', $i, $item->field_id); ?>
		</td>

		<!--STATE-->
		<td class="center">
			<div class="btn-group">
				<!-- STATE BUTTON -->
				<?php echo $grid->state() ?>

				<!-- CHANGE STATE DROP DOWN -->
				<?php echo $this->loadTemplate('dropdown'); ?>
			</div>
		</td>

		<!--CATEGORY-->
		<td class="center">
			<?php echo $this->escape($item->category_title); ?>
		</td>

		<!--TITLE-->
		<td class="n/owrap has-context quick-edit-wrap">
			<div class="item-title">
				<!-- Checkout -->
				<?php echo $grid->checkoutButton(); ?>

				<!-- Title -->
				<?php echo $grid->editTitle(); ?>
			</div>

			<!-- Sub Title -->
			<div class="small">
				<?php echo JTEXT::_('LIB_WINDWALKER_FIELD_ATTR_LABEL').': ' . $this->escape($item->attrs->label);?>
			</div>
		</td>

		<td class="center">
			<?php echo $grid->editTitle(JText::_('LIB_WINDWALKER_FIELDTYPE_' . $item->field_field_type ? : 'text')); ?>
		</td>

		<td class="center">
			<?php echo JText::_($item->field_label); ?>
		</td>

		<td class="center">
			<?php echo $item->field_name; ?>
		</td>

		<td class="center">
			<?php if ($item->field_required): ?>
			<i class="icon-publish"></i>
			<?php else: ?>
			-
			<?php endif; ?>
		</td>

		<!--USER-->
		<td class="center">
			<?php echo $this->escape($item->user_name); ?>
		</td>

		<!--ID-->
		<td class="center">
			<?php echo $item->id; ?>
		</td>

	</tr>
<?php endforeach; ?>
</tbody>
</table>
