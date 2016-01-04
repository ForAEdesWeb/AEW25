<?php
/**
 * @version $Id: default.php 318 2014-07-23 11:53:34Z michal $
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
$edit_auth = ($user->authorise('core.edit', 'com_djcatalog2') || ($user->authorise('core.edit.own', 'com_djcatalog2') && $user->id == $this->item->created_by)) ? true : false;

$nullDate = JFactory::getDbo()->getNullDate();
?>

<?php if ($this->params->get( 'show_page_heading', 1) /*&& ($this->params->get( 'page_heading') != @$this->item->name)*/) : ?>
<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ) ?>">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<div id="djcatalog" class="djc_clearfix djc_item<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default'); if ($this->item->featured == 1) echo ' featured_item'; ?>">
	<?php if($this->item->event->beforeDJCatalog2DisplayContent) { ?>
	<div class="djc_pre_content">
			<?php echo $this->item->event->beforeDJCatalog2DisplayContent; ?>
	</div>
	<?php } ?>
	<?php if ($this->navigation && (!empty($this->navigation['prev']) || !empty($this->navigation['next'])) && ($this->params->get('show_navigation', '0') == 'top' || $this->params->get('show_navigation', '0') == 'all')) { ?>
		<div class="djc_product_top_nav djc_clearfix">
			<?php if (!empty($this->navigation['prev'])) { ?>
				<a class="djc_prev_btn" href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($this->navigation['prev']->slug, $this->navigation['prev']->catslug)); ?>"><span class="button btn"><?php echo JText::_('COM_DJCATALOG2_PREVIOUS'); ?></span></a>
			<?php } ?>
			<?php if (!empty($this->navigation['next'])) { ?>
				<a class="djc_next_btn" href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($this->navigation['next']->slug, $this->navigation['next']->catslug)); ?>"><span class="button btn"><?php echo JText::_('COM_DJCATALOG2_NEXT'); ?></span></a>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if ( in_array('item', $this->params->get('social_code_views',array())) && $this->params->get('social_code_position','top') == 'top' && $this->params->get('social_code', '') != '') { ?>
		<div class="djc_clearfix djc_social_t">
			<?php echo $this->params->get('social_code'); ?>
		</div>
	<?php } ?>
	<?php 
	$this->item->images = DJCatalog2ImageHelper::getImages('item',$this->item->id);
	if ($this->item->images && (int)$this->params->get('show_image_item', 1) > 0) {
		echo $this->loadTemplate('images'); 
	} ?>
	<h2 class="djc_title">
	<?php if ($this->item->featured == 1) { 
		echo '<img class="djc_featured_image" alt="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" title="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" src="'.DJCatalog2ThemeHelper::getThemeImage('featured.png').'" />';
	}?>
	<?php if ((int)$this->params->get('fed_edit_button', 0) == 1 && $edit_auth) { ?>
		<a class="btn btn-primary btn-mini button djc_edit_button" href="<?php echo JRoute::_('index.php?option=com_djcatalog2&task=itemform.edit&id='.$this->item->id); ?>"><?php echo JText::_('COM_DJCATALOG2_EDIT')?></a>
	<?php } ?>
	
	<?php echo $this->item->name; ?></h2>
	<?php if($this->item->event->afterDJCatalog2DisplayTitle) { ?>
		<div class="djc_post_title">
			<?php echo $this->item->event->afterDJCatalog2DisplayTitle; ?>
		</div>
	<?php } ?>
	
	<?php if ( in_array('item', $this->params->get('social_code_views',array())) && $this->params->get('social_code_position','top') == 'aft_title' && $this->params->get('social_code', '') != '') { ?>
		<div class="djc_clearfix djc_social_at">
			<?php echo $this->params->get('social_code'); ?>
		</div>
	<?php } ?>
	
	<?php /*if ($this->params->get('show_print_button_item', false) == '1' || $this->params->get('show_pdf_button_item', false) == '1') {?>
	<div class="btn-toolbar">
		<?php if ($this->params->get('show_print_button_item', false) == '1') {?>
			<a rel="nofollow" class="djc_printable_version button btn btn-mini" href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($this->item->id, $this->item->cat_id).'&tmpl=component&print=1&layout=print'); ?>"><?php echo JText::_('COM_DJCATALOG2_PRINTABLE_BUTTON'); ?></a>
		<?php } ?>
		<?php if ($this->params->get('show_pdf_button_item', false) == '1') { ?>
			<a rel="nofollow" class="djc_print_pdf_button button btn btn-mini" href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($this->item->id, $this->item->cat_id).'&tmpl=component&print=1&layout=print&pdf=1'); ?>"><?php echo JText::_('COM_DJCATALOG2_PRINT_PDF_BUTTON'); ?></a>
		<?php } ?>
	</div>
	<?php }*/ ?>
	
	<?php if ($this->params->get('show_contact_form', '1')) { ?>
	<p class="djc_contact_form_toggler">
		<button id="djc_contact_form_button" class="btn btn-primary btn-mini button"><?php echo JText::_('COM_DJCATALOG2_CONTACT_FORM_OPEN')?></button>
    </p>
	<?php } ?>
    
    <div class="djc_description">
    	<div class="djc_item_info">
			<?php if ($this->params->get('show_category_name_item') && $this->item->publish_category == '1') { ?>
				<div class="djc_category_info">
				<small>
				 <?php 
					if ($this->params->get('show_category_name_item') == 2) {
			        	echo JText::_('COM_DJCATALOG2_CATEGORY').': '?><span><?php echo $this->item->category; ?></span> 
					<?php }
					else {
						echo JText::_('COM_DJCATALOG2_CATEGORY').': ';?><a href="<?php echo DJCatalogHelperRoute::getCategoryRoute($this->item->catslug);?>"><span><?php echo $this->item->category; ?></span></a> 
					<?php } ?>
				</small>
			    </div>
			<?php } ?>
			<?php if ($this->params->get('show_producer_name_item') > 0 && $this->item->publish_producer == '1' && $this->item->producer) { ?>
				<div class="djc_producer_info">
					<small>
	        		<?php 
						if ($this->params->get('show_producer_name_item') == 2) {
	            			echo JText::_('COM_DJCATALOG2_PRODUCER').': '; ?><span><?php echo $this->item->producer;?></span>
						<?php }
						else if(($this->params->get('show_producer_name_item') == 3)) {
							echo JText::_('COM_DJCATALOG2_PRODUCER').': ';?><a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 450}}" href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($this->item->prodslug).'&tmpl=component'); ?>"><span><?php echo $this->item->producer; ?></span></a> 
						<?php }
						else {
							echo JText::_('COM_DJCATALOG2_PRODUCER').': ';?><a href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($this->item->prodslug)); ?>"><span><?php echo $this->item->producer; ?></span></a>
						<?php } ?>
						<?php if ($this->params->get('show_producers_items_item', 1)) { ?>
							<a class="djc_producer_items_link btn btn-mini button" href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute(0).'&cm=0&pid='.$this->item->producer_id); ?>"><span><?php echo JText::_('COM_DJCATALOG2_SHOW_PRODUCERS_ITEMS'); ?></span></a>
	        			<?php } ?>
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
		    				<?php if ((int)$this->params->get('show_author_item') == 1 && $this->item->created_by) {?>
		    					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute(0).'&aid='.$this->item->created_by.':'.JApplication::stringURLSafe($this->item->author));?>"><span><?php echo $this->item->author; ?></span></a>
		    				<?php } else {?>
		    					<span><?php echo $this->item->author; ?></span>
		    				<?php } ?>
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
	    	
	    	<?php if ((int)$this->item->available == 1 && $this->params->get('items_show_cart_button_item', '1') == 1 && ($this->params->get('cart_enabled', false) || $this->params->get('cart_query_enabled', 1) )) { 
	    		echo $this->loadTemplate('addtocart'); 
	    	}?>
			
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
<?php if ("joomlart" == $this->item->producer): ?>
<div>
<!-- Button trigger modal -->
<button id="liveDemo" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#demoModal">
    預覽網站 Live Demo
</button>

<!-- Modal -->
<div class="modal fade col-lg-12 bs-modal-lg" id="demoModal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $this->item->name; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="<?php echo $this->item->name; ?>"><?php echo $this->item->name; ?></h4>
      </div>
      <div class="modal-body">
          <iframe class="lazyIframe" data-url="http://<?php echo str_replace("_", "-", $this->item->name); ?>.demo.joomlart.com/">Loading... </iframe>
      </div>
    </div>
  </div>
</div>
</div>
<?php endif; ?>
            <?php if (isset($this->item->tabs)) { ?>
            	<div class="djc_clear"></div>
            	<div class="djc_tabs">
            		<?php echo JHTML::_('content.prepare', $this->item->tabs); ?>
            	</div>
            <?php } ?>
            <?php if ($this->item->files = DJCatalog2FileHelper::getFiles('item',$this->item->id)) {
				echo $this->loadTemplate('files');
			} ?>
			<?php if ($this->params->get('show_contact_form', '1')) { ?>
			<div class="djc_clear"></div>
			<div class="djc_contact_form_wrapper" id="contactform">
				<?php echo $this->loadTemplate('contact'); ?>
			</div>
			<?php } ?>
			<?php if($this->item->event->afterDJCatalog2DisplayContent) { ?>
				<div class="djc_post_content">
					<?php echo $this->item->event->afterDJCatalog2DisplayContent; ?>
				</div>
			<?php } ?>
			
			<?php if ($this->navigation && (!empty($this->navigation['prev']) || !empty($this->navigation['next'])) && ($this->params->get('show_navigation', '0') == 'bottom' || $this->params->get('show_navigation', '0') == 'all')) { ?>
				<div class="djc_product_bottom_nav djc_clearfix">
					<?php if (!empty($this->navigation['prev'])) { ?>
						<a class="djc_prev_btn" href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($this->navigation['prev']->slug, $this->navigation['prev']->catslug)); ?>"><span class="button btn"><?php echo JText::_('COM_DJCATALOG2_PREVIOUS'); ?></span></a>
					<?php } ?>
					<?php if (!empty($this->navigation['next'])) { ?>
						<a class="djc_next_btn" href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($this->navigation['next']->slug, $this->navigation['next']->catslug)); ?>"><span class="button btn"><?php echo JText::_('COM_DJCATALOG2_NEXT'); ?></span></a>
					<?php } ?>
				</div>
			<?php } ?>
			
			<?php if ( in_array('item', $this->params->get('social_code_views',array())) && $this->params->get('social_code_position','top') == 'aft_desc' && $this->params->get('social_code', '') != '') { ?>
				<div class="djc_clearfix djc_social_ad">
					<?php echo $this->params->get('social_code'); ?>
				</div>
			<?php } ?>
			
			<?php if((int)$this->params->get('comments', 0) > 0){
				echo $this->loadTemplate('comments');
			} ?>						
			
			<?php if ($this->relateditems && $this->params->get('related_items_count',2) > 0) {
				echo $this->loadTemplate('relateditems');
			} ?>
        </div>
        
        <?php if ( in_array('item', $this->params->get('social_code_views',array())) && $this->params->get('social_code_position','top') == 'bottom' && $this->params->get('social_code', '') != '') { ?>
			<div class="djc_clearfix djc_social_b">
				<?php echo $this->params->get('social_code'); ?>
			</div>
		<?php } ?>
	<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
	?>
</div>