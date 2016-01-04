<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}?>

<div class='pc-products-wrapper row clearfix'> 
	<div id="pc-products" class ='pc-products col-sm-12 clearfix' data-columns>     
		
		<?php foreach($products as $p) : ?>
			<?php $instance  = PaycartProduct::getInstance($p->product_id,$p)?> 
			<?php $inStock   = PaycartFactory::getHelper('product')->isProductInStock($p->product_id);?>  
			<?php $class     = !$inStock?'pc-product-stockout':''?>  
			<div class="pc-product-outer">
				<div class='pc-product thumbnail'>
						<?php $media = $instance->getCoverMedia();?>      
						<?php $url   = PaycartRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$p->product_id);?>           
						
						<a class="pc-clickable" href="<?php echo $url;?>">
							<div class="pc-product-content">
								<?php if(!empty($class)):?>
									<strong><span class="<?php echo $class;?> text-center"><?php echo strtoupper(JText::_("COM_PAYCART_PRODUCT_IS_OUT_OF_STOCK"));?></span></strong>
								<?php endif;?>  
								
								<img class="<?php echo !$inStock?'pc-product-stockout-image':'';?>" src="<?php echo @$media['optimized'];?>">
								<p class="pc-product-title pc-break-word"><?php echo $instance->getTitle();?></p>
							</div>
						</a>
						
						<h4><span class="amount"><?php echo $formatter->amount($instance->getPrice(), true)?></span></h4>
				</div>
			</div>
		<?php endforeach;?>

	</div>
</div>
<?php

