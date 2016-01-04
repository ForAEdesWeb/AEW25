<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>
<div>
 	<div class="accordion-group panel panel-default">
 		<div class="accordion-heading panel-heading">
 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-products-summary">
 				<?php echo JText::_('COM_PAYCART_CART_PRODUCT_SUMMARY'); ?>
 			</a>
 		</div>
 
 		<div id="pc-confirm-products-summary" class="accordion-body in collapse"">
 			<div class="accordion-inner panel-body">
 			<?php foreach ($product_particular as $particular) :?>
 				<div class="row">					
					<!-- Product Image			 				-->
 					<div class="col-xs-3"><img class="img-polaroid pc-border-box" src="<?php echo @$product_media[$particular->particular_id]['thumbnail'];?>" /></div>
 					
 					<!-- Product Details			 				-->
	 				<div class="col-xs-9">
	 					<div class="row">		
	 						<div class="col-sm-7"> 											
		 						<div>
		 							<a class="pc-break-word" href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=product&product_id='.$particular->particular_id);?>" >
		 								<?php echo $particular->title; ?>
		 							</a>
		 						</div>
		 						
		 						<?php //PCTODO : do not get instances direclty here ?>
		 						<?php $product = PaycartProduct::getInstance($particular->particular_id);?>
		 						<?php $postionedAttributes = (array)$product->getPositionedAttributes();?>
								<?php $attributes = $product->getAttributes();?>
								 <div class="pc-product-overview">
						 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>
										<ul class="list-unstyled text-muted">
						 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
						 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
						 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
									 			<li><small><?php echo $instance->getTitle();?>&nbsp;:&nbsp;<?php $options = $instance->getOptions(); echo $options[$attributes[$attributeId]]->title;?></small></li>
											<?php endif?>
						 				<?php endforeach;?>
						 				</ul>			 				
						 			<?php endif;?>
						 		</div>			 	
		 						<div>
		 							<?php echo JText::_('COM_PAYCART_UNIT_PRICE').':'.$formatter->amount($particular->unit_price, true, $currency_id);  ?>
		 						</div>
		 						
		 						<?php if ($particular->tax) : ?>
		 						 	<div>
		 						 		<?php $key = $particular->type.'-'.$particular->particular_id;?>
		 								<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE])):?>
											 	<a 	href="javascript:void(0)"  
											  		class="pc-popover" 
											  		title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
											  		data-content="<?php echo implode("<hr>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE]);?>" data-trigger="hover">
											  		
											 	 	<i class="fa fa-info-circle"></i>
											  </a>
										<?php endif;?> 
		 								<?php echo ' +'.JText::_('COM_PAYCART_TAX').':'.$formatter->amount($particular->tax, true, $currency_id);?>
		 							</div>
		 						<?php endif;?>
		 						
		 						<?php if ($particular->discount) : ?>
		 						 	<div>
		 						 		<?php $key = $particular->type.'-'.$particular->particular_id;?>
		 								<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE])):?>
											 	<a 	href="javascript:void(0)"  
											  		class="pc-popover" 
											  		title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
											  		data-content="<?php echo implode("<hr>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE]);?>" data-trigger="hover">
											  		
											 	 	<i class="fa fa-info-circle"></i>
											  </a>
										<?php endif;?>
		 								<?php echo ' -'.JText::_('COM_PAYCART_DISCOUNT').':'.$formatter->amount(-($particular->discount), true, $currency_id);?>		 								
		 							</div>
		 						<?php endif;?>
		 					</div>
	 					
	 						<!-- Product Price and quantity			 				-->
			 				<div class="col-sm-5">			 					
			 					<div class="row">
			 						<div class="col-sm-12">
				 					<label><?php echo JText::_('COM_PAYCART_QUANTITY'); ?> :</label>
				 					</div>
				 					<div class="col-sm-12">
				 						<div class="input-group">				 									
											<input type="text" class="form-control" id='pc-checkout-quantity-<?php echo $particular->particular_id; ?>'
					 							value="<?php echo $particular->quantity; ?>"
					 							min="<?php echo isset($particular->min_quantity) ? $particular->min_quantity : 1; ?>" />
				 								
						 					<div class="input-group-addon">				 										 											 										 					
						 						<a href="javascript:void(0);" onClick="paycart.cart.confirm.onChangeProductQuantity(<?php echo $particular->particular_id; ?>, this.value);">
						 						<i class="fa fa-refresh"></i>
						 						</a>					 		
						 					</div>
						 				</div>									 										 					
				 					</div>				
					 				<div class="col-sm-12"> 				
					 					<span class="text-danger" id="pc-checkout-quantity-error-<?php echo $particular->particular_id;?>"></span>
					 				</div>
					 				<div class="col-sm-12"><h4><?php echo JText::_('COM_PAYCART_PRICE'); ?> : <?php echo $formatter->amount($particular->total, true, $currency_id); ?></h4></div>
				 				</div>
			 				</div>
			 			</div>
	 				</div>			
	 			</div>

	 			<div class="row">
	 				<div class="col-sm-12">
	 				 <div class="pull-right">
	 					<a 	class="text-muted" href="javascript:void(0)"	onClick="paycart.cart.confirm.onRemoveProduct(<?php echo $particular->particular_id;?>)">
	 						<i class="fa fa-trash-o fa-lg">&nbsp;</i>
						</a>
					</div>
					</div>
	 			</div>
	 			<hr />
 			<?php endforeach;?>
 			<div class="row">
	 			<span class="col-sm-12 pull-right"><?php echo JText::_("COM_PAYCART_CART_PRODUCT_TOTAL") ?> : <?php echo $formatter->amount($product_total, true, $currency_id); ?> </span>
	 		</div>	 			
 			</div>
 		</div>
 	</div>
 </div>
<?php 	 	