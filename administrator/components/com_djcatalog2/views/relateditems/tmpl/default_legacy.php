<?php
/**
 * @version $Id: default_legacy.php 140 2013-09-09 07:42:05Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= false;
$parent_id = JFactory::getApplication()->input->getInt('item_id',null);
?>
<form action="<?php echo JRoute::_('index.php?option=com_djcatalog2&view=relateditems&tmpl=component');?>" method="post" name="adminForm" id="adminForm">
	<div class="filter-select fltrt">
		<button type="button" onclick="javascript:Joomla.submitbutton('relateditems.assign')"><?php echo JText::_('JAPPLY'); ?></button>
		<button type="button" onclick="javascript:Joomla.submitbutton('relateditems.assignclose')"><?php echo JText::_('JSAVE'); ?></button>
		<button type="button" onclick="window.parent.SqueezeBox.close();"><?php echo JText::_('JTOOLBAR_CLOSE'); ?></button>
	</div>
	<div class="clr"></div>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '1', 'JPUBLISHED'),JHtml::_('select.option', '0', 'JUNPUBLISHED')), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
			<?php echo JHTML::_('select.genericlist', $this->categories, 'filter_category', 'class="inputbox" onchange="this.form.submit()"', 'value', 'text', $this->state->get('filter.category')); ?>
			<?php 
				$producers_first_option = new stdClass();
				$producers_first_option->id = '';
				$producers_first_option->name = '- '.JText::_('COM_DJCATALOG2_SELECT_PRODUCER').' -';
				$producers_first_option->published = null;
				$producers = count($this->producers) ? array_merge(array($producers_first_option),$this->producers) : array($producers_first_option);
				echo JHTML::_('select.genericlist', $producers, 'filter_producer', 'class="inputbox" onchange="this.form.submit()"', 'id', 'name', $this->state->get('filter.producer'));
			?>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th width="75" align="center">
					<?php echo JText::_('COM_DJCATALOG2_IMAGE'); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_DJCATALOG2_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th width="15%"  class="title">
					<?php echo JHTML::_('grid.sort',  'COM_DJCATALOG2_CATEGORY', 'category_name', $listDirn, $listOrder ); ?>
				</th>
				<th width="15%"  class="title">
					<?php echo JHTML::_('grid.sort',  'COM_DJCATALOG2_PRODUCER', 'producer_name', $listDirn, $listOrder ); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$item->max_ordering = 0; //??
			$ordering	= false;
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php if (!$item->checked_out && $item->id != $parent_id) { ?>
						<input type="checkbox" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $item->id?>" onclick="isChecked(this.checked);" title="<?php echo JText::sprintf('JGRID_CHECKBOX_ROW_N', ($i + 1));?>" <?php if ($item->related_count > 0) echo 'checked="yes"'?> />
					    <?php echo '<input type="hidden" name="listed_cid[]" value="'.$item->id.'" />'; ?>
					<?php } else { ?>
						<?php 
						  echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'items.', false); ?>
					<?php } ?>
				</td>
				<td align="center">
					<?php 
					if ($item->item_image) { ?><img alt="<?php echo $item->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($item->image_fullpath,'thumb'); ?>"/><?php }
					else { ?><img src="<?php echo str_replace('/administrator', '', JURI::base()).'components/com_djcatalog2/assets/images/noimage.jpg'; ?>" alt="" /><?php }?>
				</td>
				<td>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'items.', false); ?>
						<?php echo $this->escape($item->name); ?>
					<?php else: ?>
						<?php echo $this->escape($item->name); ?>
					<?php endif; ?>
						
					<p class="smallsub"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
				</td>
				<td>
					<?php echo $this->escape($item->category_name); ?>
				</td>
				<td>
					<?php echo $this->escape($item->producer_name); ?>
				</td>
				<td class="order">
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->cat_id == @$this->items[$i-1]->cat_id), 'items.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->cat_id == @$this->items[$i+1]->cat_id), 'items.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->cat_id == @$this->items[$i-1]->cat_id), 'items.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->cat_id == @$this->items[$i+1]->cat_id), 'items.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="clr"></div>
	<div class="filter-select fltrt">
		<button type="button" onclick="javascript: Joomla.submitbutton('relateditems.assign')"><?php echo JText::_('JAPPLY'); ?></button>
		<button type="button" onclick="javascript:Joomla.submitbutton('relateditems.assignclose')"><?php echo JText::_('JSAVE'); ?></button>
		<button type="button" onclick="window.parent.SqueezeBox.close();"><?php echo JText::_('JTOOLBAR_CLOSE'); ?></button>
	</div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="item_id" value="<?php echo $parent_id; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
