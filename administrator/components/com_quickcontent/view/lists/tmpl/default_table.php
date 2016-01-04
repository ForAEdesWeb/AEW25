<?php
/**
 * Part of Component Quickcontent files.
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
<table id="listList" class="table table-striped adminlist">

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

	<!--TITLE-->
	<th class="center">
		<?php echo $grid->sortTitle('JGLOBAL_TITLE', 'list.title'); ?>
	</th>

	<!--MENUTYPE-->
	<th width="7%" class='left' class="nowrap">
		<?php echo $grid->sortTitle('COM_QUICKCONTENT_MENUTYPE', 'a.menutype'); ?>
	</th>

	<!--VIEWTYPE-->
	<th width="7%" class='left' class="nowrap">
		<?php echo $grid->sortTitle('COM_QUICKCONTENT_VIEWTYPE', 'a.category_menutype'); ?>
	</th>

	<!--CLEAR MENU-->
	<th width="7%" class='left' class="nowrap">
		<?php echo $grid->sortTitle('COM_QUICKCONTENT_CLEARMENU', 'a.delete_existing'); ?>
	</th>

	<!--LIST CONTENT-->
	<th class='left'>
		<?php echo $grid->sortTitle('COM_QUICKCONTENT_LIST_CONTENT', 'a.content'); ?>
	</th>

	<!--GENERATE-->
	<th class="center">
		<?php echo JText::_('COM_QUICKCONTENT_GENERATE_NOW'); ?>
	</th>

	<!--RESTORE-->
	<th class="center">
		<?php echo JText::_('COM_QUICKCONTENT_RESTORE'); ?>
	</th>

	<!--ID-->
	<th width="1%" class="nowrap center">
		<?php echo $grid->sortTitle('JGRID_HEADING_ID', 'list.id'); ?>
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
	<tr class="list-row" sortable-group-id="<?php echo $item->catid; ?>">
		<!-- DRAG SORT -->
		<td class="order nowrap center hidden-phone">
			<?php echo $grid->dragSort(); ?>
		</td>

		<!--CHECKBOX-->
		<td class="center">
			<?php echo JHtml::_('grid.id', $i, $item->list_id); ?>
		</td>

		<!--TITLE-->
		<td class="n/owrap has-context quick-edit-wrap">
			<div class="item-title">
				<!-- Checkout -->
				<?php echo $grid->checkoutButton(); ?>

				<!-- Title -->
				<?php echo $grid->editTitle(); ?>
			</div>
		</td>

		<td>
			<?php echo $item->menutype ; ?>
		</td>

		<td>
			<?php
			switch($item->category_menutype )
			{
				case 'list':
					echo JText::_('COM_QUICKCONTENT_VIEW_LIST') ;
					break;

				case 'blog':
					echo JText::_('COM_QUICKCONTENT_VIEW_BLOG') ;
					break;

				default: echo JText::_('COM_QUICKCONTENT_VIEW_NONE') ;
					break;
			}
			?>
		</td>

		<td>
			<?php
			if($item->delete_existing)
			{
				echo JText::_('JYES');
			}
			else
			{
				echo JText::_('JNO');
			}
			?>
		</td>

		<td>
			<?php echo JString::substr( strip_tags($item->content) , 0 , 150 ) . '...'; ?>
		</td>


		<td class="center">
			<?php $generated = $item->generated; ?>
			<?php $disabled_bg = $generated ? 'background-position: 0 32px;' : ''; ?>
			<span >
						<a
							<?php if( $generated ): ?>
								href="javascript:void(0);"
							<?php else: ?>
								href="index.php?option=com_quickcontent&task=generator.generate&id=<?php echo $item->id ?>"
							<?php endif; ?>

							class="btn btn-primary <?php echo ($generated) ? 'disabled' : ''; ?>">
							<i class="icon-new icon-white"></i>
						</a>
					</span>
		</td>

		<td class="center">
			<span>
				<a
					href="index.php?option=com_quickcontent&task=generator.restore&id=<?php echo $item->id ?>"
					class="btn btn-danger">
					<i class="icon-refresh iconwhite"></i></a>
			</span>
		</td>

		<!--ID-->
		<td class="center">
			<?php echo $item->id; ?>
		</td>

	</tr>
<?php endforeach; ?>
</tbody>
</table>
