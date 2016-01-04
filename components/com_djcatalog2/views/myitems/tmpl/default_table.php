<?php
/**
 * @version $Id: default_table.php 272 2014-05-21 10:25:49Z michal $
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

defined ('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
?>
<table width="100%" cellpadding="0" cellspacing="0" class="djc_items_table jlist-table category table table-condensed" id="djc_my_items_table">
	<thead>
		<tr>
			<th class="djc_thead djc_th_title" colspan="2">
				<?php echo JText::_('COM_DJCATALOG2_NAME'); ?>
	        </th>
			<?php if ($this->params->get('fed_show_category_name', 1) > 0) { ?>
				<th class="djc_thead djc_th_category">
					<?php echo JText::_('COM_DJCATALOG2_CATEGORY'); ?>
				</th>
			<?php } ?>
			<?php if ($this->params->get('fed_show_producer_name', 3) > 0) { ?>
				<th class="djc_thead djc_th_producer">
					<?php echo JText::_('COM_DJCATALOG2_PRODUCER'); ?>
				</th>
			<?php } ?>
			<?php if ($this->params->get('fed_show_price', 1) > 0) { ?>
                <th class="djc_thead djc_th_price">
                	<?php echo JText::_('COM_DJCATALOG2_PRICE'); ?>
                </th>
            <?php } ?>
                <th>
                	<?php echo JText::_('COM_DJCATALOG2_ACTIONS'); ?>
                </th>
	            </tr>
            </thead>
            <tbody>
        <?php
	$k = 1;
	foreach($this->items as $item){
		$k = 1 - $k;
		?>
        <tr class="cat-list-row<?php echo $k;?> djc_row<?php echo $k; if ($item->featured == 1) echo ' featured_item'; ?>">
            <td class="djc_image">
                <?php if ($item->item_image) { ?>
	        	<div class="djc_image_in">
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)); ?>"><img class="img-polaroid" alt="<?php echo $item->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($item->image_fullpath,'small'); ?>"/></a>
	        	</div>
			<?php } ?>
            </td>
			<td class="djc_td_title">
           		<?php 
		        if ((int)$item->published != 1 ) {
		        	echo $item->name;
		        } else {
		        	echo JHTML::link(JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)), $item->name);
		        } 
		        ?>
                <?php if ($item->featured == 1) { 
					echo '<img class="djc_featured_image" alt="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" title="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" src="'.DJCatalog2ThemeHelper::getThemeImage('featured.png').'" />';
				}?>
            </td>
            <?php if ($this->params->get('fed_show_category_name', 1) > 0) { ?>
			<td class="djc_category">
				<?php 
					if ($this->params->get('fed_show_category_name', 1) == 2) {
            			?><span><?php echo $item->category; ?></span> 
					<?php }
					else {
						?><a href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute($item->catslug)) ;?>"><span class="djcat_category"><?php echo $item->category; ?></span></a> 
					<?php } ?>
			</td>
			<?php } ?>
			<?php if ($this->params->get('fed_show_producer_name', 3) > 0) { ?>
			<td class="djc_producer">
			<?php if ($item->publish_producer && $item->producer) { ?>
				<?php 
					if ($this->params->get('fed_show_producer_name', 3) == 2 && $item->producer) {
            			?><span><?php echo $item->producer;?></span>
					<?php }
					else if(($this->params->get('fed_show_producer_name', 3) == 3 && $item->producer)) {
						?><a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($item->prodslug).'&tmpl=component'); ?>"><span class="djcat_producer"><?php echo $item->producer; ?></span></a> 
					<?php }
					else if ($item->producer){
						?><a href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($item->prodslug)); ?>"><span class="djcat_producer"><?php echo $item->producer; ?></span></a>
					<?php } ?>
				<?php } ?>
			</td>
			<?php } ?>
			<?php if ($this->params->get('fed_show_price', 1) > 0) { ?>
            <td class="djc_price" nowrap="nowrap">
                <?php if ($item->price != $item->final_price ) { ?>
        			<span class="djc_price_old"><?php echo DJCatalog2HtmlHelper::formatPrice($item->price, $this->params); ?></span><br /><span class="djc_price_new"><?php echo DJCatalog2HtmlHelper::formatPrice($item->final_price, $this->params); ?></span>
				<?php } else { ?>
					<span><?php echo DJCatalog2HtmlHelper::formatPrice($item->price, $this->params); ?></span>
				<?php } ?>
            </td>
            <?php } ?>
            <td nowrap="nowrap">
            	<?php if ($user->authorise('core.edit', 'com_djcatalog2') || $user->authorise('core.edit.own', 'com_djcatalog2')) { ?>
            	<a class="djc_formbutton djc_button_edit" href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=itemform.edit&id='.$item->id); ?>">
            	<img src="<?php echo DJCatalog2ThemeHelper::getThemeImage('btn_edit.png');?>" alt="<?php echo JText::_('COM_DJCATALOG2_EDIT'); ?>" />
            	<span><?php echo JText::_('COM_DJCATALOG2_EDIT') ?></span>
            	</a>
            	<?php } ?>
            	<?php if ($user->authorise('core.edit.state', 'com_djcatalog2') || $user->authorise('core.edit.state.own', 'com_djcatalog2')) { ?>
            		<?php $new_state_task = ($item->published == '1') ? 'unpublish' : 'publish'; ?>
            		<?php if ($new_state_task == 'publish') { ?>
            			<a class="djc_formbutton djc_button_unpublished" href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=myitems.publish&id='.$item->id.'&'.JSession::getFormToken().'=1'); ?>">
		            	<img src="<?php echo DJCatalog2ThemeHelper::getThemeImage('btn_unpublish.png');?>" alt="<?php echo JText::_('COM_DJCATALOG2_UNPUBLISH'); ?>" />
		            	<span><?php echo JText::_('COM_DJCATALOG2_PUBLISH') ?></span>
		            	</a>
            		<?php } else { ?>
	            		<a class="djc_formbutton djc_button_published" href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=myitems.unpublish&id='.$item->id.'&'.JSession::getFormToken().'=1'); ?>">
		            	<img src="<?php echo DJCatalog2ThemeHelper::getThemeImage('btn_publish.png');?>" alt="<?php echo JText::_('COM_DJCATALOG2_PUBLISH'); ?>" />
		            	<span><?php echo JText::_('COM_DJCATALOG2_UNPUBLISH') ?></span>
		            	</a>
            		<?php } ?>
            	<?php } ?>
            	
            	<?php if ($user->authorise('core.delete', 'com_djcatalog2') || $user->authorise('core.delete.own', 'com_djcatalog2')) { ?>
            	<a class="djc_formbutton djc_button_delete" href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=myitems.delete&id='.$item->id.'&'.JSession::getFormToken().'=1'); ?>" 
            	onclick="return confirm('<?php echo $this->escape(JText::_('COM_DJCATALOG2_DELETE_CONFIRM_MSG')); ?>');">
            	<img src="<?php echo DJCatalog2ThemeHelper::getThemeImage('btn_delete.png');?>" alt="<?php echo JText::_('COM_DJCATALOG2_DELETE'); ?>" />
            	<span><?php echo JText::_('COM_DJCATALOG2_DELETE') ?></span>
            	</a>
            	<?php } ?>
            </td>
        </tr>
	<?php } ?>
	</tbody>
</table>