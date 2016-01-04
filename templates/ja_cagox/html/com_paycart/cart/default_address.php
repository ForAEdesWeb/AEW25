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

	<div class="pc-checkout-address row">					
		<!--	Billing Address -->
	 	<div class="col-sm-6 pc-checkout-billing ">
	 	
	 		<h3><?php echo JText::_('COM_PAYCART_ADDRESS_BILLING'); ?></h3>
	 		<div class="checkbox"><label>&nbsp;</label></div>
	 		<div class="pc-checkout-billing-html">
			 	<?php
			 		// load billing address template
			 		echo $this->loadTemplate('address_billing'); 
			 	?>
			 </div>
			 
		</div>
	
		<!--	Shipping Address	-->
		<div class=" col-sm-6 pc-checkout-shipping clearfix">
		
			<h3><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING'); ?></h3>			
			<div class="checkbox">
				<label>
				<input 	id='billing_to_shipping' type="checkbox" 
						<?php echo ($billing_to_shipping)? 'checked="checked"' : ''?>		
						name="paycart_cart_address[billing_to_shipping]"
						onClick="return paycart.cart.address.onBillingToShipping();"
						value='true'
				/><?php echo JText::_('COM_PAYCART_SAME_ADDRESS_TEXT'); ?>					
				</label>
			</div>
			
			<div class="pc-checkout-shipping-html">
			 	<?php
					// load shipping address template
			 		echo $this->loadTemplate('address_shipping');
			 	?>
		 	</div>
		 	
		</div>
	
		<!--	Continue Checkout	-->
		<div class="col-sm-12 clearfix">
			
			<button type="button" onClick="paycart.cart.address.onContinue();" 
					class="pc-whitespace btn btn-block btn-lg btn-primary">
				<?php echo JText::_('COM_PAYCART_BUTTON_CONTINUE'); ?> <i class="fa fa-angle-double-right"></i>
			</button>
			
		</div>
			
		<input	type="hidden"	name='step_name' value='address' />
				
	</div>

	<script>
			
		(function($) {
			$(document).ready(function(){			
				paycart.cart.address.init();
			});			
		})(paycart.jQuery);
	
	</script>	 

<?php

