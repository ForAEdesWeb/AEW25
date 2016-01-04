<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
*/
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>
<div id="pc-cart-complete-paid">
 	<div class="row">
 		<div class="col-sm-12 center">
 			<?php if($payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE):?>
	 			<h1 class="text-success"><?php echo JText::_('COM_PAYCART_CART_COMPLETE_PAID_MSG');?></h1>
	 			<div class="text-muted"><em><?php echo JText::_('COM_PAYCART_CART_COMPLETE_PAID_ORDER_PROCESSING');?></em></div>
	 		<?php elseif($payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING):?>
	 			<h1 class="text-warning"><?php echo JText::_('COM_PAYCART_CART_COMPLETE_INPROCESS_MSG');?></h1>
	 			<div class="text-muted"><em><?php echo JText::_('COM_PAYCART_CART_COMPLETE_INPROCESS_ORDER_PROCESSING');?></em></div>
	 		<?php else :?>
	 			<h1 class="text-error"><?php echo JText::_('COM_PAYCART_CART_COMPLETE_FAILED_MSG');?></h1>
	 			<div class="text-muted"><em><?php echo JText::_('COM_PAYCART_CART_COMPLETE_FAILED_ORDER_PROCESSING');?></em></div>
	 		<?php endif;?>
	 		<hr/>
 		</div>
 	</div>
 	
 	<div class="row">
 		<div class="col-sm-6">
 			<div class="well">
	 			<h4 class="center"><?php echo JText::_('COM_PAYCART_ORDER_DETAILS');?></h4>
	 			<table class="table">
	 				<tbody>
	 					<tr>
	 						<td><?php echo JText::_('COM_PAYCART_ORDER_ID');?> :</td>
	 						<td><strong><?php echo $cart->cart_id;?></strong> <span class="pc-lowercase">(<?php echo count($productCartParticulars).' '.JText::_('COM_PAYCART_ITEM'.((count($productCartParticulars) > 1 ) ? 'S' : ''));?>)</span></td> 					
	 					</tr>
	 					<tr>
	 						<td><?php echo JText::_('COM_PAYCART_ORDER_PLACED');?> :</td>
	 						<td><?php echo $formatter->date(new Rb_Date($cart->locked_date));?></td>
	 					</tr>	 					
	 					<tr>
	 						<td><?php echo JText::_('COM_PAYCART_PAYMENT_METHOD');?> :</td>
	 						<td><?php echo $cart->params['payment_gateway']['title'];?></td>
	 					</tr>
	 					<tr>
							<td><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> :</td>
							<td><?php echo $formatter->amount($cart->subtotal);?></td>
						</tr>			
						<?php if(!empty($cart->promotion)):?>
							<tr>
								<td><?php echo JText::_('COM_PAYCART_PROMOTION_DISCOUNT');?> :</td>
								<td><?php echo $formatter->amount($cart->promotion);?></td>
							</tr>
						<?php endif;?>
						<?php if(!empty($cart->duties)):?>
							<tr>
								<td><?php echo JText::_('COM_PAYCART_TAX');?> :</td>
								<td>
									<?php echo $formatter->amount($cart->duties);?>
									<br><small>(<?php echo JText::_("COM_PAYCART_CART_TAX_ON_TAX_DESC")?>)</small>
								</td>
							</tr>
						<?php endif;?>					
						<?php if(!empty($cart->shipping)):?>
							<tr>
								<td><?php echo JText::_('COM_PAYCART_SHIPPING_COST')?> :</td>
								<td><?php echo $formatter->amount($cart->shipping);?></td>
							</tr>
						<?php endif;?>						
						<tr>
							<td><strong><?php echo JText::_('COM_PAYCART_TOTAL');?> :</strong></td>
							<td><strong><?php echo $formatter->amount($cart->total);?></strong></td>
						</tr>
						<tr class="<?php echo  $payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE ? 'success' : ($payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING ? 'warning' : 'error'); ?>">
							<td><?php echo JText::_('COM_PAYCART_STATUS');?> :</td>
							<td>
								<span class="pc-uppercase">
								<?php if($payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE): ?>
									<?php echo $invoiceStatusList[PaycartHelperInvoice::STATUS_INVOICE_PAID];?>
								<?php elseif($payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING):?>
									<?php echo $invoiceStatusList[PaycartHelperInvoice::STATUS_INVOICE_INPROCESS];?>
								<?php else:?>
									<?php echo $invoiceStatusList[$invoice->status];?>
								<?php endif;?>
								</span>
							</td>
	 				</tbody>
	 			</table>
	 		</div>
 		</div>
 		
 		<div class="col-sm-6">
 			<div>
				<h4><?php echo JText::_('COM_PAYCART_BUYER_EMAIL');?></h4>
				<?php echo $buyer->getEmail();?>
				<hr />
			</div>
			<div>
				<h4><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING');?></h4>
				<div>
					<?php echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $shippingAddress);?>
				</div>
				<hr />
			</div> 		
			<div>
				<div class="alert alert-warning"><?php echo JText::_('COM_PAYCART_DELIVERY_STATUS');?> : <i class="fa fa-spinner"></i> <strong><?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_PENDING');?></strong></div>
			</div>
 		</div>
 	</div>
 	
 	<div class="row">
 		<div class="col-sm-12">
 			<h3><?php echo JText::_('COM_PAYCART_PRODUCT_DETAILS');?></h3>
 			<table class="table">
 				<tbody>
 					<?php foreach($productCartParticulars as $particular) :?>
 						<?php $product_id = $particular->particular_id;?>
 						<?php $product = $products[$product_id];?>
 						<tr>
							<td style="width:60%">
								<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$product_id);?>"><?php echo $product->getTitle();?></a>
  								<div class="text-muted">
  									<ul class="list-inline">
  										<?php $postionedAttributes = (array)$product->getPositionedAttributes();?>
										<?php $attributes = $product->getAttributes();?>								 
							 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>			 			
							 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
							 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
							 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
										 			<li><small><?php echo $instance->getTitle();?>&nbsp;:&nbsp;<?php $options = $instance->getOptions(); echo $options[$attributes[$attributeId]]->title;?>,</small></li>
												<?php endif?>	                         
							 				<?php endforeach;?>			 				
							 			<?php endif;?>
 										<li><small><?php echo JText::_('COM_PAYCART_QUANTITY');?>: <?php echo $particular->quantity;?></small></li>
 									</ul>
 								</div>
	 						</td>
	 						<td>
	 							<?php echo JText::_('COM_PAYCART_SUBTOTAL')?> : <?php echo $formatter->amount($particular->total);?>
	 							<?php if(!empty($estimatedDeliveryDate)):?>
	 							<div class="text-muted">
  									<ul class="list-inline">
  										<li><small><?php echo JText::_('COM_PAYCART_SHIPPMENT_EXPECTED_DELIVERY_DATE');?> : <?php echo $estimatedDeliveryDate ? $formatter->date($estimatedDeliveryDate) : JText::_('COM_PAYCART_SOON') ;?></small></li>
  									</ul>
  								</div>
  								<?php endif;?>
	 						</td>
						</tr>
 					<?php endforeach;?> 				
 				</tbody>
 			</table>
 			<hr/>
 		</div>
 	</div>
 		
 	<div class="row">
 		<div class="col-sm-5">
			<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>" class="btn btn-lg input-block-level btn-info  form-control"><?php echo JText::_('COM_PAYCART_KEEP_SHOPPING');?></a> 		
 		</div>
 		<div class="col-sm-2">
 			<h4 class="text-muted center"><?php echo JText::_('COM_PAYCART_OR');?></h4>
 		</div>
 		<div class="col-sm-5">
 			<?php if($payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE || $payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING) :?>
				<a href="<?php echo $track_url;?>" class="btn btn-lg input-block-level btn-default  form-control"><?php echo JText::_('COM_PAYCART_TRACK_ORDER');?></a>
			<?php else:?>
			 	<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=cart&task=unlock&cart_id='.$cart->cart_id.'&'.JSession::getFormToken().'=1');?>" class="btn btn-lg input-block-level btn-default  form-control"><?php echo JText::_('COM_PAYCART_PAY_AGAIN');?></a>
			<?php endif;?>
 		</div>
 	</div>
 	
</div>
<?php 