<?php
/**
 * @version $Id: default_items.php 272 2014-05-21 10:25:49Z michal $
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
$user		= JFactory::getUser();
?>

<?php
$k = 0; 
$i = 1; 
$col_count = $this->params->get('producers_columns',2);
$col_width = ((100/$col_count)-0.01);

foreach ($this->items as $item) {

	$item->slug = (!empty($item->alias)) ? $item->id.':'.$item->alias : $item->id;
	
	$newrow_open = $newrow_close = false;
	if ($k % $col_count == 0) $newrow_open = true;
	if (($k+1) % $col_count == 0 || count($this->items) <= $k+1) $newrow_close = true;
	        
	$rowClassName = 'djc_clearfix djc_item_row djc_item_row';
	if ($k == 0) $rowClassName .= '_first';
	if (count($this->items) <= ($k + $this->params->get('producers_columns',2))) $rowClassName .= '_last';
	
	$colClassName ='djc_item_col';
	if ($k % $col_count == 0) { $colClassName .= '_first'; }
	else if (($k+1) % $col_count == 0) { $colClassName .= '_last'; }
	else {$colClassName .= '_'.($k % $col_count);}
	$k++;
	
	if ($newrow_open) { $i = 1 - $i; ?>
	<div class="<?php echo $rowClassName.'_'.$i; ?> djc2_cols_<?php echo $col_count ?>">
	<?php }
	?>
        <div class="djc_item djc_producer_item pull_left <?php echo $colClassName; ?>" style="width:<?php echo $col_width; ?>%">
        <div class="djc_item_bg">
		<div class="djc_item_in djc_clearfix">
        <?php if ($item->item_image && (int)$this->params->get('producers_image_link', 0) != -1) { ?>
        	<div class="djc_image">
        		<?php if ((int)$this->params->get('producers_image_link', 0) == 1) { ?>
					<a rel="djimagebox-djitem" title="<?php echo $item->image_caption; ?>" href="<?php echo DJCatalog2ImageHelper::getImageUrl($item->image_fullpath,'fullscreen'); ?>"><img class="img-polaroid" alt="<?php echo $item->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($item->image_fullpath,'medium'); ?>"/></a>
				<?php } else { ?>
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($item->slug)); ?>"><img class="img-polaroid" alt="<?php echo $item->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($item->image_fullpath,'medium'); ?>"/></a>
	        	<?php } ?>
        	</div>
		<?php } ?>
		<?php if ((int)$this->params->get('producers_show_name','1') > 0 ) {?>
		<div class="djc_title">
	        <h3>
	        <?php 
	        if ((int)$this->params->get('producers_show_name','1') == 2 ) {
	        	echo $item->name;
	        } else { ?>
	        	<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($item->slug)); ?>"><?php echo $item->name; ?></a>
	        <?php } ?>
	        </h3>
	    </div>
	    <?php } ?>
            <div class="djc_description">
				<?php if ($this->params->get('producers_show_intro', '0') == '1') { ?>
				<div class="djc_introtext">
					<?php if ($this->params->get('producers_intro_length') > 0  && $this->params->get('producers_intro_trunc') == '1' ) {
							?><p><?php echo DJCatalog2HtmlHelper::trimText($item->description, $this->params->get('producers_intro_length'));?></p><?php
						}
						else {
							echo $item->description; 
						}
					?>
				</div>
				<?php } ?>
            </div>
            <?php if ($this->params->get('producers_readmore', '0') == '1') { ?>
				<div class="clear"></div>
				<div class="djc_readon">
					<a class="btn button readmore" href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($item->slug)); ?>" class="readmore"><?php echo JText::sprintf('COM_DJCATALOG2_READMORE'); ?></a>
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