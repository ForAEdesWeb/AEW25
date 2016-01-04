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
<div class="pc-checkout-state row clearfix">
	<div class="col-sm-12">
	<?php echo $this->loadTemplate('steps');?>
	</div>
</div>

<div class='pc-checkout-wrapper clearfix row'>
	<span for="paycart_cart_confirm" class="pc-error"></span>
	
	 <div class="pc-checkout-confirm" id="accordion-parent" >
<!--	 -->
	 	<div class="col-sm-8">
	 		
	 		<!-- Email Block		-->
	 		<div class="accordion-group panel panel-default">
		 		<div class="accordion-heading panel-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-email">
		 				<?php echo JText::_("COM_PAYCART_EMAIL_TITLE"); ?>
		 			</a>
		 		</div>
		 		
		 		<div id="pc-confirm-email" class="accordion-body in collapse"">
		 			<div class="accordion-inner panel-body">
		 				<p><?php echo $buyer->email; ?></p>
		 				
		 				<?php if ($cart->is_guestcheckout) :?>
		 				<div>
		 					<a href="#" onclick="return paycart.cart.login.get();"> <i class="fa fa-edit"></i> <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
		 				</div>
		 				<?php endif; ?>
		 			</div>
		 		</div>
		 	</div>
		 	
			<!-- Addresses Block		 	-->
		 	<div class="row">
		 	
		 	<!--	Billing Address	 		-->
		 		<div class="col-sm-6">				
		 			<div class="accordion-group panel panel-default">
		 				<div class="accordion-heading panel-heading">
		 					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-billing-address">
		 						<?php echo JText::_("COM_PAYCART_BILLING_ADDRESS_TITLE"); ?>
		 					</a>
		 				</div>
		 		
				 		<div id="pc-confirm-billing-address" class="accordion-body in collapse"">
				 			<div class="accordion-inner panel-body">
				 				<?php
				 					$layout = new JLayoutFile('paycart_buyeraddress_display');
									echo $layout->render($billing_address); 
				 				?>
				 											
								<div>
			 						<a href="#" onclick="return paycart.cart.address.get();"> <i class="fa fa-edit"></i>  <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
			 					</div>
			 					
				 			</div>
				 		</div>
		 			</div>
		 		</div>
		 		
		 		<!-- Shipping Address 		-->
		 		<div class="col-sm-6">
		 			<div class="accordion-group panel panel-default">
		 				<div class="accordion-heading panel-heading">
		 					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-shipping-address">
		 						<?php echo JText::_("COM_PAYCART_SHIPPING_ADDRESS_TITLE"); ?>
		 					</a>
		 				</div>
				 		<div id="pc-confirm-shipping-address" class="accordion-body in collapse"">
				 			<div class="accordion-inner panel-body">
				 				<?php
				 					if ( @$billing_to_shipping ) {
				 						echo '<i class="fa fa-clipboard"></i> ' . JText::_('COM_PAYCART_CART_ADDRESS_SAME_AS_BILLING');
				 					} else {
				 						$layout = new JLayoutFile('paycart_buyeraddress_display');
										echo $layout->render($shipping_address);
				 					} 
				 				?>	
								
								<div>
			 						<a href="#"  onclick="return paycart.cart.address.get();"> <i class="fa fa-edit"></i>  <?php echo JText::_('COM_PAYCART_EDIT')?> </a>
			 					</div>
								
				 			</div>
				 		</div>
		 			</div>
		 		</div>
		 	</div>
			
			<!-- Shipping Options		 	-->
		 	<div class="row">
		 		<div class="col-sm-12">	
		 		<div class="accordion-group panel panel-default">
		 			<div class="accordion-heading panel-heading">
		 				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-shipping-option">
	 						<?php echo JText::_("COM_PAYCART_CART_SHIPPING_OPTIONS")?>
	 					</a>
	 				</div>
	 		
			 		<div id="pc-confirm-shipping-option" class="accordion-body in collapse"">
			 			<div class="accordion-inner panel-body">
			 				<?php if(!empty($shipping_options)):?>	
				 				<div class='pc-checkout-shipping-list form-group'>
					 				<?php echo PaycartHtml::_('select.genericlist', $shipping_options, 'shipping', 'class="form-control" onChange="paycart.cart.confirm.onChangeShipping(this.value)"','value','title',$default_shipping);
					 				?>
					 			</div>
					 			<div class='pc-checkout-shipping-notes'>
						 				<b><?php echo JText::_('COM_PAYCART_PRICE')?> - <?php echo $formatter->amount($shipping_total);?></b><br/>
					 				   	   <?php $estimatedDate = null;?>
										   <?php foreach ($shipping_options[$default_shipping]['details'] as $shippingrule_id => $details):?>
										   			<?php $date = new Rb_Date($details['delivery_date']);?>
										   			<?php if(empty($estimatedDate)):?>
									   					<?php $estimatedDate = $date;?>
									   					<?php continue?>
									   				<?php endif;?>
									   													   				
								   					<?php $estimatedDate = ($estimatedDate->toUnix() < $date->toUnix())?$date:$estimatedDate; ?>
										   <?php endforeach;?>								   
										   <?php echo JText::_("COM_PAYCART_SHIPPING_ESTIMATED_DELIVERY_DATE").' : '.$formatter->date($estimatedDate);?> <br />
										   <?php if(count($shipping_options[$default_shipping]['details']) > 1):?>
													<span class='text-error'><?php echo JText::_("COM_PAYCART_SHIPPING_ORDER_MAY_BE_IN_MULTIPLE_PACKAGES");?></span>
										   <?php endif;?>			 				
					 			</div> 
				 			<?php else : ?>
								<span class='text-danger' id="pc-cart-shipping-error"><?php echo JText::_("COM_PAYCART_SHIPPING_NO_METHOD_AVAILABLE") ?></span>
				 			<?php endif;?>
			 			</div>
			 		</div>
	 			</div>
	 			</div>
		 	</div>
		 	
			<!-- Product Summary		 	-->
		 	<?php echo $this->loadTemplate('confirm_product_summary')?>	 	
	 		
	 	</div>
	 	
		
	 	<div class="col-sm-4">	 		
	 		<!-- Order Summary	 	-->	 		
 			<div class="accordion-group panel panel-primary">
		 		<div class="accordion-heading panel-heading">
		 			<a class="accordion-toggle panel-title" data-toggle="collapse" data-parent="#accordion-parent" href="#pc-confirm-order-summary">
		 				<?php echo JText::_('COM_PAYCART_CART_ORDER_SUMMARY'); ?>
		 			</a>
		 		</div>
		 		
		 		<div id="pc-confirm-order-summary" class="accordion-body in collapse"">
		 			<div class="accordion-inner panel-body">
		 				<table class="table">
		 					<thead>
		 						<tr>
		 							<td><?php echo JText::_('COM_PAYCART_QUANTITY'); ?></td>
		 							<td><?php echo $product_quantity;?></td>
		 						</tr>
		 					</thead>
		 					
		 					<tbody>
		 						<tr>
		 							<td><?php echo JText::_('COM_PAYCART_CART_TOTAL'); ?></td>
		 							<td><?php echo $formatter->amount($product_total, true, $currency_id); ?></td>
		 						</tr>
		 						
		 						<tr>
		 							<td><?php echo JText::_('COM_PAYCART_SHIPPING'); ?></td>
		 							<td><?php echo $formatter->amount($shipping_total, true, $currency_id); ?></td>
		 						</tr>
		 						
		 						<?php if(!empty($duties_particular) && floatval($duties_total) != 0):?>
			 						<tr>
										<td>
											<?php $duties_particular = array_shift($duties_particular);?>
											<?php $key = $duties_particular->type.'-'.$duties_particular->particular_id;?>
											<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE])):?>
												 	<a 	href="javascript:void(0)"  
												  		class="pc-popover" 
												  		title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
												  		data-content="<?php echo implode("<br/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_TAXRULE]);?>" data-trigger="hover">
												  		
												 	 	<i class="fa fa-info-circle"></i>
												  </a>
											<?php endif;?>
			 							<?php echo JText::_('COM_PAYCART_TAX'); ?></td>
			 							<td><?php echo $formatter->amount($duties_total, true, $currency_id); ?>
			 								<br><small>(<?php echo JText::_("COM_PAYCART_CART_TAX_ON_TAX_DESC")?>)</small>
			 							</td>
			 						</tr>
		 						<?php endif;?>
		 						
		 						<?php if(!empty($promotion_particular) && floatval($promotion_total) != 0):?>
			 						<tr>
			 							<td>
			 								<?php $promotion_particular = array_shift($promotion_particular);?>
			 								<?php $key = $promotion_particular->type.'-'.$promotion_particular->particular_id;?>
			 								<?php if(isset($usageDetails[$key]) && isset($usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE])):?>
												 	<a 	href="javascript:void(0)"  
												  		class="pc-popover" 
												  		title="<?php echo JText::_("COM_PAYCART_DETAILS")?>"
												  		data-content="<?php echo implode("<br/>", $usageDetails[$key][Paycart::PROCESSOR_TYPE_DISCOUNTRULE]);?>" data-trigger="hover">
												  		
												 	 	<i class="fa fa-info-circle"></i>
												  </a>												  
											<?php endif;?>
			 								<?php echo JText::_('COM_PAYCART_DISCOUNT'); ?>
			 							</td>
			 							<td><?php echo $formatter->amount($promotion_total, true, $currency_id); ?></td>
			 						</tr>
		 						<?php endif;?>
		 						
		 						<tr>
		 							<td><?php echo JText::_('COM_PAYCART_TOTAL'); ?></td>
		 							<td><?php echo $formatter->amount(($product_total+$promotion_total+$duties_total+$shipping_total), true, $currency_id); ?> </td>
		 						</tr>
		 					</tbody>
		 				</table>	
		 			</div>
		 		</div>
		 	</div>
			 
			<!-- Cart Discount		 -->
			<div class="form-group">
				<div class="control-label">
					<label><?php echo JText::_('COM_PAYCART_PROMOTION_CODE_LABEL')?></label>
				</div>
				<div class="controls">					
				 	<?php  
				 	 // Already applied discount
				 	 if (!empty($applied_promotion_code)) :
				 	 		foreach ($applied_promotion_code as $code) : ?>				 	 		
				 	 		<div class="input-group" >
					 	  	  <span class="input-group-addon text-success"><i class="text-success fa fa-check "></i></span>
							  <input class="form-control" type="text" value='<?php echo $code;?>'  readonly="readonly">
							  <div class="input-group-addon">
							  	<a href="#" class="" onclick="paycart.cart.onRemovePromotionCode('<?php echo $code;?>'); return false;">
							  		<span class="text-danger"><i class="fa fa-remove "></i></span>
							  		<?php echo JText::_('COM_PAYCART_PROMOTION_CODE_REMOVE')?>
							  	</a>
							</div>
							</div>
				 	<?php 	endforeach;
				 	?>
				 	<?php else :?> 	 	
				 	<div class="input-group" >				 		
					  <input class="form-control" id="paycart-promotion-code-input-id" type="text">
					   <div class="input-group-addon"><a href="#" class="" onclick="paycart.cart.onApplyPromotionCode(); return false;"><?php echo JText::_('COM_PAYCART_PROMOTION_CODE_APPLY')?></a></div>
					</div>
					<?php endif;?>
					<span class=" pc-error " id="pc-checkout-promotioncode-error" for="pc-checkout-promotioncode-error"></span>
		 		</div>				
			</div>		 			
		 	
		 
			 <!-- Process ne		 -->
			 <div>
			 	<input type="hidden" name="paycart_cart_confirm">
			 	<button type="button" class="btn btn-primary btn-block btn-lg <?php echo $isDisabled;?>" <?php echo $clickActionOnProceed?> ><?php echo JText::_('COM_PAYCART_CART_PROCEED_TO_PAYMENT'); ?></button>
			 </div>
			 
			 <input	type="hidden"	name='step_name' value='confirm' />
		 </div>	 	
	 </div>	 
</div>

<script>
		(function($){							
			$(".pc-popover").popover();
		})(paycart.jQuery);
</script>
<?php

