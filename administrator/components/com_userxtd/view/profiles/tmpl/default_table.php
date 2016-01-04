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
<table id="profileList" class="table table-striped table-bordered adminlist">

<!-- TABLE HEADER -->
<thead>
<tr>
	<!--CHECKBOX-->
	<th width="1%" class="center">
		<?php echo JHtml::_('grid.checkAll'); ?>
	</th>

	<!-- USER ID -->
	<th class="center" width="10%">
		<?php echo $grid->sortTitle('COM_USERXTD_USER_ID', 'profile.user_id'); ?>
	</th>

	<!-- USER NAME -->
	<th class="nowrap center"  width="20%">
		<?php echo $grid->sortTitle('COM_USERXTD_USER_USERNAME', 'user.name'); ?>
	</th>

	<!-- ATTR NAME -->
	<th  class="center" width="20%">
		<?php echo $grid->sortTitle('LIB_WINDWALKER_FIELD_ATTR_NAME', 'field.name'); ?>
	</th>

	<!-- ATTR LABEL -->
	<th  class="center" width="20%">
		<?php echo $grid->sortTitle('LIB_WINDWALKER_FIELD_ATTR_LABEL', 'field.label'); ?>
	</th>

	<!-- ATTR VALUE -->
	<th  class="center">
		<?php echo $grid->sortTitle('LIB_WINDWALKER_FIELD_ATTR_VALUE', 'profile.value'); ?>
	</th>

	<!-- CATEGORY -->
	<th  class="center" width="10%">
		<?php echo $grid->sortTitle('JCATEGORY', 'category.id'); ?>
	</th>

	<!-- ID -->
	<th width="1%" class="nowrap center">
		<?php echo $grid->sortTitle('JGRID_HEADING_ID', 'profile.id'); ?>
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
	<tr class="profile-row" sortable-group-id="<?php echo $item->catid; ?>">

		<!--CHECKBOX-->
		<td class="center">
			<?php echo JHtml::_('grid.id', $i, $item->profile_id); ?>
		</td>

		<td>
			<?php echo $item->user_id; ?>
		</td>

		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . $item->user_id); ?>" target="_blank">
				<?php echo $item->user_name; ?>
			</a>
			(<?php echo $item->user_username; ?>)
		</td>

		<td>
			<?php echo $item->profile_key; ?>
		</td>

		<td>
			<?php echo $item->field_title; ?>
		</td>

		<td>
			<?php echo $item->profile_value; ?>
		</td>

		<!--CATEGORY-->
		<td class="center">
			<?php echo $this->escape($item->category_title); ?>
		</td>

		<!--ID-->
		<td class="center">
			<?php echo $item->id; ?>
		</td>

	</tr>
<?php endforeach; ?>
</tbody>
</table>
