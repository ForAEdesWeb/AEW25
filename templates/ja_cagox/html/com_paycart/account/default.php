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
	<div id="pc-account" class ='pc-account col-sm-12 clearfix' >
	
		<!-- START HEADER -->
		<div class="pc-account-header hidden-xs">
			<?php echo $this->loadTemplate('header');?>
		</div>
		<!-- START HEADER -->
		
		
		<!-- START BODY -->
		<div class="row">
			<div class="col-sm-3 hidden-xs">
				<?php echo $this->loadTemplate('sidebar');?>
			</div>
			<div class="col-sm-9 col-xs-12">
				<div class ='pc-account clearfix' >
				<table class="table table-hover">
					<thead>					
						<tr>
							<th width="10%">#</th>
							<th><?php echo JText::_('COM_PAYCART_ORDERS');?> <span class="badge"><?php echo $total_orders;?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php $counter = $limitstart+1;?>
						<?php foreach($carts as $cart_id => $cart): ?>
							<tr class="pc-account-orders" data-pc-cart-url="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=order&order_id='.$cart_id);?>">
								<td><?php echo $counter++;?></td>
								<td> 
									<div><h3><a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=order&order_id='.$cart_id);?>"><?php echo JText::_('COM_PAYCART_ORDER_ID');?> : <?php echo $cart->cart_id;?></a></h3></div>
									<div>
										<h4>
											<?php echo JText::_('COM_PAYCART_AMOUNT');?> : <?php echo $formatter->amount($invoices[$cart->invoice_id]['total']);?>
											<?php if($cart->status === Paycart::STATUS_CART_PAID):?>
												<i class="fa fa-check-circle text-success" title="<?php echo JText::_('COM_PAYCART_CART_STATUS_PAID');?>"></i>
											<?php endif;?>
										</h4>										
									</div>
									<div><?php echo JText::_('COM_PAYCART_CREATED_DATE');?> : <?php echo $formatter->date(new Rb_Date($cart->created_date));?></div>
									<div><?php echo JText::_('COM_PAYCART_STATUS');?> :
										<?php if($cart->is_delivered) :?>
												<span class="label label-success"><?php echo JText::_('COM_PAYCART_CART_STATUS_DELIVERED');?></span>
												<?php echo strtolower(JText::_('COM_PAYCART_ON'));?>
												<?php echo $formatter->date(new Rb_Date($cart->delivered_date));?>
										<?php else :?>
												<span class="label label-warning"><?php echo JText::_('COM_PAYCART_CART_STATUS_PENDING');?></span>												
										<?php endif;?>
									</div>
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">
								<div class="center">
									<?php echo $pagination->getListFooter(); ?>
								</div>								
							</td>
						</tr>
					</tfoot>
				</table>
				</div>				
			</div>
		</div>      	
	</div>
</div>