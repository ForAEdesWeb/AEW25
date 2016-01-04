<?php
/**
 * @version $Id: print.php 300 2014-07-04 11:09:09Z michal $
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
$price_auth = ($this->params->get('price_restrict', '0') == '1' && $user->guest) ? false : true;

$nullDate = JFactory::getDbo()->getNullDate();
$jinput = JFactory::getApplication()->input;

$printable = (bool)($jinput->get('pdf', false) === false);
?>

<div id="djcatalog" class="djc_clearfix djc_item<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default'); if ($this->item->featured == 1) echo ' featured_item'; ?> djc_printable">
	<?php if ($printable) {?>
	<button class="djc_back_button button btn" onclick="window.history.go(-1)"><?php echo JText::_('COM_DJCATALOG2_BACK_BUTTON'); ?></button>
	<button class="djc_print_button button btn" onclick="window.print(); return false;"><?php echo JText::_('COM_DJCATALOG2_PRINT_BUTTON'); ?></button>
	<?php } ?>
	
	<?php if($this->item->event->beforeDJCatalog2DisplayContent) { ?>
	<div class="djc_pre_content">
			<?php echo $this->item->event->beforeDJCatalog2DisplayContent; ?>
	</div>
	<?php } ?>

	<h2 class="djc_title">
	<?php if ($this->item->featured == 1) { 
		echo '<img class="djc_featured_image" alt="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" title="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" src="'.DJCatalog2ThemeHelper::getThemeImage('featured.png').'" />';
	}?>
	<?php echo $this->item->name; ?></h2>
	<?php if($this->item->event->afterDJCatalog2DisplayTitle) { ?>
		<div class="djc_post_title">
			<?php echo $this->item->event->afterDJCatalog2DisplayTitle; ?>
		</div>
	<?php } ?>

	<?php 
	$this->item->images = DJCatalog2ImageHelper::getImages('item',$this->item->id);
	if ($this->item->images && (int)$this->params->get('show_image_item', 1) > 0) {
		for($i = 0; $i < count($this->item->images); $i++) { ?>
			<img alt="<?php echo $this->item->images[$i]->caption; ?>" src="<?php echo $this->item->images[$i]->medium; ?>" />
		<?php }
	}
	?>
    
    <div class="djc_description">
    	<div class="djc_item_info">
			<?php if ($this->params->get('show_category_name_item') && $this->item->publish_category == '1') { ?>
				<div class="djc_category_info">
				<small>
				 <?php echo JText::_('COM_DJCATALOG2_CATEGORY').': '?><span><?php echo $this->item->category; ?></span> 
				</small>
			    </div>
			<?php } ?>
			<?php if ($this->params->get('show_producer_name_item') > 0 && $this->item->publish_producer == '1' && $this->item->producer) { ?>
				<div class="djc_producer_info">
					<small>
	        		<?php echo JText::_('COM_DJCATALOG2_PRODUCER').': '; ?><span><?php echo $this->item->producer;?></span>
	        		</small>
	        	</div>
				<?php } ?>
	        	<?php
					if ($price_auth && ($this->params->get('show_price_item') == 2 || ( $this->params->get('show_price_item') == 1 && $this->item->price > 0.0))) {
						?>
			        	<div class="djc_price">
			        		<small>
			        		<?php 
			        		if ($this->item->price != $this->item->final_price ) { ?>
			        			<?php if ($this->params->get('show_old_price_item', '1') == '1') {?>
			        				<?php echo JText::_('COM_DJCATALOG2_PRICE').': ';?><span class="djc_price_old"><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->price, $this->params); ?></span>&nbsp;<span class="djc_price_new"><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->final_price, $this->params); ?></span>
			        			<?php } else { ?>
			        				<?php echo JText::_('COM_DJCATALOG2_PRICE').': ';?><span><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->final_price, $this->params); ?></span>
			        			<?php } ?>
							<?php } else { ?>
								<?php echo JText::_('COM_DJCATALOG2_PRICE').': ';?><span><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->price, $this->params); ?></span>
							<?php } ?>
			        		</small>
			        	</div>
				<?php } ?>
				
				<?php if ((int)$this->params->get('show_author_item', 0) > 0 && $this->item->author) { ?>
	    			<div class="djc_author">
	    				<small>
		    				<?php echo JText::_('COM_DJCATALOG2_CREATED_BY').': '; ?>
		    				<span><?php echo $this->item->author; ?></span>
	    				</small>
	    			</div>
	    		<?php } ?>
	    		
	    		<?php if ((int)$this->params->get('show_date_item', 0) == 1 && $this->item->created != $nullDate) { ?>
	    			<div class="djc_date djc_created_date">
	    				<small>
		    				<?php echo JText::_('COM_DJCATALOG2_CREATED_ON').': '; ?>
		    				<span><?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')); ?></span>
	    				</small>
	    			</div>
	    		<?php } ?>
	    		
	    		<?php if ((int)$this->params->get('show_publishdate_item', 0) == 1 && $this->item->publish_up != $nullDate) { ?>
	    			<div class="djc_date djc_publish_date">
	    				<small>
		    				<?php echo JText::_('COM_DJCATALOG2_PUBLISHED_ON').': '; ?>
		    				<span><?php echo JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3')); ?></span>
	    				</small>
	    			</div>
	    		<?php } ?>
	    		
	    		<?php if ((int)$this->params->get('show_hits_item', 0) == 1) { ?>
	    			<div class="djc_hits">
	    				<small>
		    				<?php echo JText::_('COM_DJCATALOG2_HITS').': '; ?>
		    				<span><?php echo $this->item->hits; ?></span>
		    			</small>
	    			</div>
	    		<?php } ?>
	    	</div>

	    	<?php if ((int)$this->params->get('show_intro_desc_item', 0) == 1) { ?>
			<div class="djc_introtext">
                <?php echo JHTML::_('content.prepare', $this->item->intro_desc); ?>
            </div>
			<?php } ?>
			
	    	<div class="djc_fulltext">
                <?php echo JHTML::_('content.prepare', $this->item->description); ?>
            </div>
            
            <?php 
			if (count($this->attributes) > 0) {
				$attributes_body = '';
				foreach ($this->attributes as $attribute) {
					$this->attribute_cursor = $attribute;
					$attributes_body .= $this->loadTemplate('attributes');
				}
				?>
				<?php if ($attributes_body != '') { ?>
					<div class="djc_attributes">
						<table class="table table-condensed">
						<?php echo $attributes_body; ?>
						</table>
					</div>
				<?php } ?>
			<?php } ?>
            
            <?php if (isset($this->item->tabs)) { ?>
            	<div class="djc_clear"></div>
            	<div class="djc_tabs">
            		<?php echo JHTML::_('content.prepare', $this->item->tabs); ?>
            	</div>
            <?php } ?>
			<?php if($this->item->event->afterDJCatalog2DisplayContent) { ?>
				<div class="djc_post_content">
					<?php echo $this->item->event->afterDJCatalog2DisplayContent; ?>
				</div>
			<?php } ?>
        </div>
	<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
	?>
</div>