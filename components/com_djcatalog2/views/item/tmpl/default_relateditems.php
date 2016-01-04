<?php
/**
 * @version $Id: default_relateditems.php 141 2013-09-16 08:09:56Z michal $
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

$count = (count($this->relateditems) > $this->params->get('related_items_count',2)) ? $this->params->get('related_items_count',2):count($this->relateditems);

$k = 0; 
$i = 1; 
$col_count = $this->params->get('related_items_columns',2);
$col_width = ((100/$col_count)-0.01);

?>
<div class="djc_clear"></div>
<div class="djc_related_items djc_clearfix">
	<h3 class="djc_related_title"><?php echo JText::_('COM_DJCATALOG2_RELATED_ITEMS');?></h3>
	<?php 
	for ($j=0; $j < $count; $j++ ) { 
		$relateditem = $this->relateditems[$j];
		
		$newrow_open = $newrow_close = false;
		if ($k % $col_count == 0) $newrow_open = true;
		if (($k+1) % $col_count == 0 || $count <= $k+1) $newrow_close = true;
		        
		$rowClassName = 'djc_clearfix djc_item_row djc_item_row';
		if ($k == 0) $rowClassName .= '_first';
		if ($count <= ($k + $this->params->get('items_columns',2))) $rowClassName .= '_last';
		
		$colClassName ='djc_item_col';
		if ($k % $col_count == 0) { $colClassName .= '_first'; }
		else if (($k+1) % $col_count == 0) { $colClassName .= '_last'; }
		else {$colClassName .= '_'.($k % $col_count);}
		$k++;
		
		if ($newrow_open) { $i = 1 - $i; ?>
		<div class="<?php echo $rowClassName.'_'.$i; ?>">
		<?php }
		?>
	<div class="djc_item <?php echo $colClassName; if ($relateditem->featured == 1) echo ' featured_item'; ?>" style="width:<?php echo $col_width; ?>%">
        <div class="djc_item_bg">
		<div class="djc_item_in djc_clearfix">
		<?php if ($relateditem->featured == 1) { 
			echo '<img class="djc_featured_image" alt="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" src="'.DJCatalog2ThemeHelper::getThemeImage('featured.png').'" />';
		}?>
        <?php if ($relateditem->item_image) { ?>
        	<div class="djc_image">
        		<?php if ($this->params->get('image_link_item')) { ?>
					<a rel="lightbox-djitem-related" title="<?php echo $relateditem->image_caption; ?>" href="<?php echo DJCatalog2ImageHelper::getImageUrl($relateditem->image_fullpath,'fullscreen'); ?>"><img alt="<?php echo $relateditem->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($relateditem->image_fullpath,'medium'); ?>"/></a>
				<?php } else { ?>
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($relateditem->slug, $relateditem->catslug)); ?>"><img alt="<?php echo $relateditem->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($relateditem->image_fullpath,'medium'); ?>"/></a>
	        	<?php } ?>
        	</div>
		<?php } ?>
		<div class="djc_title">
	        <h3><?php
	          echo (JHTML::link(JRoute::_(DJCatalogHelperRoute::getItemRoute($relateditem->slug, $relateditem->catslug)), $relateditem->name));
	        ?></h3>
	    </div>
            <div class="djc_description">
			<?php if ($this->params->get('show_category_name') > 0) { ?>
			<div class="djc_category_info">
            	<?php 
				if ($this->params->get('show_category_name') == 2) {
            		echo JText::_('COM_DJCATALOG2_CATEGORY').': '?>
            		<span><?php echo $relateditem->category; ?></span> 
				<?php }
				else {
					echo JText::_('COM_DJCATALOG2_CATEGORY').': ';?>
					<a href="<?php echo DJCatalogHelperRoute::getCategoryRoute($relateditem->catslug);?>">
						<span><?php echo $relateditem->category; ?></span>
					</a> 
				<?php } ?>
            </div>
			<?php } ?>
			<?php if ($this->params->get('show_producer_name') > 0 && $relateditem->producer && $relateditem->publish_producer) { ?>
			<div class="djc_producer_info">
				<?php 
				if ($this->params->get('show_producer_name') == 2) {
            		echo JText::_('COM_DJCATALOG2_PRODUCER').': '; ?>
            		<span><?php echo $relateditem->producer;?></span>
				<?php }
				else if(($this->params->get('show_producer_name') == 3)) {
					echo JText::_('COM_DJCATALOG2_PRODUCER').': ';?>
					<a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($relateditem->prodslug).'&tmpl=component'); ?>">
						<span><?php echo $relateditem->producer; ?></span>
					</a> 
				<?php }
				else {
					echo JText::_('COM_DJCATALOG2_PRODUCER').': ';?>
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($relateditem->prodslug)); ?>">
						<span><?php echo $relateditem->producer; ?></span>
					</a> 
				<?php } ?>
            </div>
			<?php } ?>
            <?php 
				if ($this->params->get('show_price') == 2 || ( $this->params->get('show_price') == 1 && $relateditem->price > 0.0)) { 
			?>
            <div class="djc_price">
            	<?php if ($relateditem->price != $relateditem->final_price ) { ?>
        			<?php echo JText::_('COM_DJCATALOG2_PRICE').': ';?><span class="djc_price_old"><?php echo DJCatalog2HtmlHelper::formatPrice($relateditem->price, $this->params); ?></span>&nbsp;<span class="djc_price_new"><?php echo DJCatalog2HtmlHelper::formatPrice($relateditem->final_price, $this->params); ?></span>
				<?php } else { ?>
					<?php echo JText::_('COM_DJCATALOG2_PRICE').': ';?><span><?php echo DJCatalog2HtmlHelper::formatPrice($relateditem->price, $this->params); ?></span>
				<?php } ?>
            </div>
			<?php } ?>
			<?php if ($this->params->get('items_show_intro')) {?>
			<div class="djc_introtext">
				<?php if ($this->params->get('items_intro_length') > 0 ) {
						?><p><?php echo DJCatalog2HtmlHelper::trimText($relateditem->intro_desc, $this->params->get('items_intro_length'));?></p><?php
					}
					else {
						echo $relateditem->intro_desc; 
					}
				?>
			</div>
			<?php } ?>
            </div>
            <?php if ($this->params->get('showreadmore_item')) { ?>
				<div class="clear"></div>
				<div class="djc_readon">
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($relateditem->slug, $relateditem->catslug)); ?>" class="readmore"><?php echo JText::sprintf('COM_DJCATALOG2_READMORE'); ?></a>
				</div>
			<?php } ?>
         </div>
 	</div>
	<div class="djc_clear"></div>
	</div>
	<?php if ($newrow_close) { ?>
		</div>
	<?php } ?>
	<?php } ?>
</div>