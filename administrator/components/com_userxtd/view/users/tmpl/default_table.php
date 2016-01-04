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
<table id="userList" class="table table-striped table-bordered adminlist">

<!-- TABLE HEADER -->
<thead>
<tr>
	<!--CHECKBOX-->
	<th width="1%" class="center">
		<?php echo JHtml::_('grid.checkAll'); ?>
	</th>

	<!--NAME-->
	<th class="center user-data">
		<?php echo $grid->sortTitle('COM_USERS_HEADING_NAME', 'user.name'); ?>
	</th>

	<!--ENABLED-->
	<th width="5%" class="center user-data">
		<?php echo $grid->sortTitle('COM_USERS_HEADING_ENABLED', 'user.enabled'); ?>
	</th>

	<!-- ACTIVATION -->
	<th width="5%" class="center user-data">
		<?php echo $grid->sortTitle('COM_USERS_HEADING_ACTIVATED', 'user.activation'); ?>
	</th>

	<?php
	echo $this->loadTemplate('dynamic_th');
	?>

	<!--ID-->
	<th width="1%" class="nowrap center">
		<?php echo $grid->sortTitle('JGRID_HEADING_ID', 'user.id'); ?>
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
	<tr class="user-row" sortable-group-id="<?php echo $item->catid; ?>">
		<!--CHECKBOX-->
		<td class="center">
			<?php echo JHtml::_('grid.id', $i, $item->user_id); ?>
		</td>

		<!--NAME-->
		<td class="n/owrap has-context quick-edit-wrap">
			<div class="item-title">
				<a target="_blank" href="<?php echo \JRoute::_('index.php?option=com_users&task=user.edit&id=' . $item->user_id) ?>">
					<?php echo $this->escape($item->user_name); ?>
				</a>
				(<?php echo $this->escape($item->user_username); ?>)
			</div>
		</td>

		<!-- BLOCK -->
		<td class="center nowrap">
			<?php
			if(!$item->user_block)
			{
				echo '<i class="icon-publish"></i>';
			}
			else
			{
				echo '<i class="icon-unpublich"></i>';
			}
			?>
		</td>

		<!-- ACTIVATION -->
		<td class="center nowrap">
			<?php
			if(!$item->user_activation)
			{
				echo '<i class="icon-publish"></i>';
			}
			else
			{
				echo '<i class="icon-unpublich"></i>';
			}
			?>
		</td>

		<?php
		echo $this->loadTemplate('dynamic_td');
		?>

		<!--ID-->
		<td class="center">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
