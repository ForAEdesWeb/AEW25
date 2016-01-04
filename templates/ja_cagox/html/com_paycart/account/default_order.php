<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

echo $this->loadTemplate('css');
echo $this->loadTemplate('js');
?>
<div class='pc-account-wrapper row clearfix'> 
	<div id="pc-account" class ='pc-account pc-account-order clearfix col-sm-12' >
	
		<!-- HEADER -->
		<div class="pc-account-header hidden-xs">
			<?php echo $this->loadTemplate('header');?>
		</div>		
		
		
		<!-- BREADCRUMB -->		
		<ul class="breadcrumb pc-account-order-breadcrumb">
			<li><a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=display');?>"><?php echo JText::_('COM_PAYCART_ACCOUNT');?></a> <span class="divider"> </span></li>
			<li><a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=order');?>"><?php echo JText::_('COM_PAYCART_MY_ORDERS');?></a> <span class="divider"> </span></li>
			<li class="active"><?php echo $order_id;?></li>
		</ul>
		
		
		<!-- DETAILS -->
		<div class="pc-account-order-details">
			<div class="row">	
				<div class="col-sm-6">
					<div class="pc-account-order-orderdetail">
						<fieldset>
							<legend><?php echo JText::_('COM_PAYCART_ORDER_DETAILS');?></legend>
							<div><?php echo JText::_('COM_PAYCART_ORDER_ID');?> : <span class="heading"><?php echo $cart->cart_id;?></span> <span class="pc-lowercase">(<?php echo count($productCartParticulars).' '.JText::_('COM_PAYCART_ITEM'.((count($productCartParticulars) > 1 ) ? 'S' : ''));?>)</span></div>
							<div><?php echo JText::_('COM_PAYCART_ORDER_PLACED');?> : <span class="heading"><?php echo $formatter->date(new Rb_Date($cart->locked_date));?></span></div>
							<div>
								<?php if($cart->is_delivered) :?>
									<span class="text-success"><strong><?php echo JText::_('COM_PAYCART_CART_STATUS_DELIVERED');?></strong></span>
									<span class="pc-lowercase"><?php echo JText::_('COM_PAYCART_ON');?></span>
									<?php echo $formatter->date(new Rb_Date($cart->delivered_date));?>
								<?php else :?>
									<span class="text-warning"><strong><?php echo JText::_('COM_PAYCART_CART_STATUS_PENDING');?></strong></span>												
								<?php endif;?>
							</div> 
						</fieldset>
					</div>
				</div>
				<br class="visible-xs-block" />
				<div class="col-sm-6">
					<div class="pc-account-order-shipping-address">
						<fieldset>
							<legend><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING');?></legend>
							<div>
								<?php echo Rb_HelperTemplate::renderLayout('paycart_buyeraddress_display', $shippingAddress);?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			
			<div class="pc-account-order-paymentdetail">
				<fieldset>
					<legend><?php echo JText::_('COM_PAYCART_ACCOUNT_PAYMENT_DETAILS');?></legend>
					<div class="row">
						<div class="col-sm-6">
							<div><?php echo JText::_('COM_PAYCART_PAYMENT_METHOD');?> : <?php echo $cart->params['payment_gateway']['title'];?></div>
							<div><?php echo JText::_('COM_PAYCART_STATUS');?> :
								<span class="heading pc-uppercase">
								<?php if($invoice->status == PaycartHelperInvoice::STATUS_INVOICE_PAID): ?>
									<span class="text-success"><?php echo $invoiceStatusList[$invoice->status];?></span>
								<?php elseif($invoice->status == PaycartHelperInvoice::STATUS_INVOICE_INPROCESS):?>
									<span class="text-warning"><?php echo $invoiceStatusList[$invoice->status];?></span>
								<?php else:?>
									<span class="text-error"><?php echo $invoiceStatusList[$invoice->status];?></span>
								<?php endif;?>
								</span>
							</div>
						</div>
						<br class="visible-xs-block" />
						<div class="col-sm-6">
							<table class="table">
								<thead>
									<tr>
										<td><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> :</td>
										<td><span class="pull-right"><?php echo $formatter->amount($cart->subtotal);?></span></td>
									</tr>								
									<?php if(!empty($cart->shipping)):?>
										<tr>
											<td><?php echo JText::_('COM_PAYCART_SHIPPING')?> :</td>
											<td><span class="pull-right"><?php echo $formatter->amount($cart->shipping);?></span></td>
										</tr>
									<?php endif;?>
									<?php if(!empty($cart->promotion)):?>
										<tr>
											<td><?php echo JText::_('COM_PAYCART_PROMOTION_DISCOUNT');?> :</td>
											<td><span class="pull-right"><?php echo $formatter->amount($cart->promotion);?></span></td>
										</tr>
									<?php endif;?>
									<?php if(!empty($cart->duties)):?>
										<tr>
											<td><?php echo JText::_('COM_PAYCART_TAX');?> :</td>
											<td>
												<span class="pull-right"><?php echo $formatter->amount($cart->duties);?></span>
												<br><small class="pull-right">(<?php echo JText::_("COM_PAYCART_CART_TAX_ON_TAX_DESC")?>)</small>
											</td>
										</tr>
									<?php endif;?>
									<tr>
										<td><span class="heading"><?php echo JText::_('COM_PAYCART_TOTAL');?> :</span></td>
										<td><span class="pull-right heading"><?php echo $formatter->amount($cart->total);?></span></td>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		
		<div class="pc-account-order-productdetails">
			<div class="pc-account-order-productdetails-header well well-small text-muted">
				<div class="row">
					<div class="col-sm-6">
						<span class="heading pc-uppercase"><?php echo JText::_('COM_PAYCART_PRODUCT_DETAILS');?></span>
					</div>
					<div class="col-sm-6 hidden-xs">
						<span class="heading pc-uppercase">
							<span class="pull-left"><?php echo JText::_('COM_PAYCART_DELIVERY')?></span>
							<span class="pull-right pc-uppercase"><?php echo JText::_('COM_PAYCART_SUBTOTAL')?></span>
						</span>
					</div>
				</div>
			</div>
		
			<div class="pc-account-order-product">
				<?php foreach($productShipments as $product_id => $productShipment) :?>
					<?php foreach ($productShipment as $shipment):?>
		  			<?php $product = $products[$product_id];?>
		  			<?php $productParticular = $productCartParticulars[$product_id];?>
					<div class="row">
						<div class="col-sm-6">
							<table class="table">
	              				<thead>
	                				<tr>
				                  		<td width="30%">
								  			<div class="text-center">								
								  			<?php $image = $product->getCoverMedia(); ?>
								  			<img class="img-polaroid" src="<?php echo !empty($image) ? $image['thumbnail'] : '';?>" title="<?php echo !empty($image) ? $image['title'] : '';?>" alt="<?php echo !empty($image) ? $image['title'] : '';?>">
								  			</div>
								  		</td>
								  		<td>
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
			 										<li><small><?php echo JText::_('COM_PAYCART_QUANTITY');?>: <?php echo $shipment['quantity'];?></small></li>
		 										</ul>
		 									</div>
		 									<strong class="visible-phone"><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> : <?php echo $formatter->amount($productParticular->total);?></strong>
		 								</td>
	 								</tr>
	 							</thead>
	 						</table>
	 					</div>
	 					
	 					<div class="col-sm-6">
							<table class="table">
								<thead>
									<tr>													
										<td>
											<?php if(isset($shipments[$shipment['shipment_id']]) && $shipments[$shipment['shipment_id']]->status == Paycart::STATUS_SHIPMENT_DELIVERED) :?>
												<p><i class="fa fa-check-circle"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_DELIVERED');?></p>
	 											<div class="progress">
  													<div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">    												
  													</div>
												</div>	 											
	  											<p class="text-muted"><span class="pc-lowercase"><?php echo JText::_('COM_PAYCART_ON');?> <?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->delivered_date));?></span></p>
	  										<?php elseif(isset($shipments[$shipment['shipment_id']]) && $shipments[$shipment['shipment_id']]->status == Paycart::STATUS_SHIPMENT_DISPATCHED) :?>
												<p><i class="fa fa-truck"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_DISPATCHED');?></p>
												<div class="progress">
  													<div class="progress-bar progress-bar-info" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 66%">    												
  													</div>
												</div>	
	 											<p class="text-muted"><span><?php echo JText::_('COM_PAYCART_SHIPPMENT_STD_DELIVERY_DATE');?> : <?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->est_delivery_date));?></span></p>
	  										<?php elseif(isset($shipments[$shipment['shipment_id']]) && $shipments[$shipment['shipment_id']]->status == Paycart::STATUS_SHIPMENT_FAILED) :?>
												<p><i class="fa fa-times-circle"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_FAILED');?></p>
												<div class="progress">
  													<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuemin="0" aria-valuemax="100" 
  															style="width: <?php echo $shipments[$shipment['shipment_id']]->dispatched_date == '0000-00-00 00:00:00' ? '33' : '66';?>%">    												
  													</div>
												</div>	 											
	  											<p class="text-muted"><del><?php echo JText::_('COM_PAYCART_SHIPPMENT_STD_DELIVERY_DATE');?> : <?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->est_delivery_date));?></del></p>
	  										<?php else :?>
	  											<p><i class="fa fa-spinner"></i> <?php echo JText::_('COM_PAYCART_SHIPMENT_STATUS_PENDING');?></p>
	 											<div class="progress">
  													<div class="progress-bar progress-bar-warning" role="progressbar"  aria-valuemin="0" aria-valuemax="100" style="width: 33%">    												
  													</div>
												</div>	
												<?php // IMP : In case if shipment is not created yet, then we can not show std delivery date?>
												<p class="text-muted"><span><?php echo JText::_('COM_PAYCART_SHIPPMENT_STD_DELIVERY_DATE');?> : 
												<?php if(isset($shipments[$shipment['shipment_id']])) :?>
	  												<?php echo $formatter->date(new Rb_Date($shipments[$shipment['shipment_id']]->est_delivery_date));?>
	  											<?php else:?>
	  												<?php echo $estimatedDeliveryDate ? $formatter->date($estimatedDeliveryDate) : JText::_('COM_PAYCART_SOON') ;?>
	  											<?php endif;?>
	  											</span></p>
	  										 <?php endif;?>
										</td>
										
										<td class="hidden-xs" width="30%">
	 										<div class="text-right">
	  											<span class="heading"><?php echo $formatter->amount($productParticular->total);?></span>
	  											<span>
	  											<a href="#" onclick="return false;" data-toggle="popover" data-placement="left" data-trigger="click" 
	  												data-content="">	  												
	  												<i class="fa fa-question-circle"></i>
	  											</a>
		  											<div class="popover-content hide">
		  												<span class='text-muted '>
														<span class='pull-right'><?php echo JText::_('com_paycart_unit_price').' : '.$formatter->amount($productParticular->unit_price);?></span><br/>
														<span class='pull-right'><?php echo JText::_('com_paycart_quantity').' : x'.$productParticular->quantity;?></span>																		
														<?php if($productParticular->total !=  $productParticular->price):?>
															<hr/><span class='pull-right'><?php echo JText::_('com_paycart_subtotal').' : '.$formatter->amount($productParticular->price);?></span><br/>
														<?php endif;?>																		
														<?php if($productParticular->discount < 0):?>
															<span class='pull-right'><?php echo JText::_('com_paycart_discount').' : '.$formatter->amount($productParticular->discount);?></span><br/>
														<?php endif;?>
														<?php if($productParticular->tax > 0):?>
															<span class='pull-right'><?php echo JText::_('com_paycart_tax').' : '.$formatter->amount($productParticular->tax);?></span><br/>
														<?php endif;?>
														</span>
														<hr/>
														<span class='pull-right'><?php echo JText::_('com_paycart_total').' : '.$formatter->amount($productParticular->total);?></span>
													</div>
	  											</span>				  												
	  										</div>
	  									</td>
	  								</tr>
	  							</thead>
	  						</table>
	  					</div>
	  				</div>
					<?php endforeach;?>
					<hr />
				<?php endforeach;?>
			</div>						
		</div>			
		
		<div class="row">
			<div class="col-sm-6">
			</div>
			<div class="col-sm-6">
				<table class="table">
					<thead>
						<tr>
							<td><?php echo JText::_('COM_PAYCART_SUBTOTAL')?> :</td>
							<td><span class="pull-right"><?php echo $formatter->amount($cart->subtotal);?></span></td>
						</tr>								
						<?php if(!empty($cart->shipping)):?>
							<tr>
								<td><?php echo JText::_('COM_PAYCART_SHIPPING')?> :</td>
								<td><span class="pull-right"><?php echo $formatter->amount($cart->shipping);?></span></td>
							</tr>
						<?php endif;?>
						<?php if(!empty($cart->promotion)):?>
							<tr>
								<td><?php echo JText::_('COM_PAYCART_PROMOTION_DISCOUNT');?> :</td>
								<td><span class="pull-right"><?php echo $formatter->amount($cart->promotion);?></span></td>
							</tr>
						<?php endif;?>
						<?php if(!empty($cart->duties)):?>
							<tr>
								<td><?php echo JText::_('COM_PAYCART_TAX');?> :</td>
								<td>
									<span class="pull-right"><?php echo $formatter->amount($cart->duties);?></span>
									<br><small class="pull-right">(<?php echo JText::_("COM_PAYCART_CART_TAX_ON_TAX_DESC")?>)</small>
								</td>
							</tr>
						<?php endif;?>
						<tr>
							<td><span class="heading"><?php echo JText::_('COM_PAYCART_TOTAL');?> :</span></td>
							<td><span class="pull-right heading"><?php echo $formatter->amount($cart->total);?></span></td>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
