<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

/**
 * Populated varaible
 * $title : title required or not
 * $shipping_address 	:: 	stdclass object, contain previous address data 
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>

<?php if (!empty($buyer_addresses)) :?>	
	<div class="control-group form-group">	
	<select name='select_shipping_address' id="pc-buyeraddress-shipping-address" class="pc-chozen input-block-level form-control" onChange='paycart.cart.address.onSelect(this.value, "shipping");'>
		<option value='0'><?php echo JText::_('COM_PAYCART_CART_SELECT_EXISTING_ADDRESS'); ?></option>
		<?php foreach ($buyer_addresses as $buyeaddress_id => $buyeraddress_details):?>
			<?php $selected = ($shipping_address_id == $buyeraddress_details->buyeraddress_id)	? 'selected' : '';?>
			<option value='<?php echo $buyeraddress_details->buyeraddress_id?>'	<?php echo $selected; ?>>
				<?php echo $buyeraddress_details->address; ?>
				<?php echo "{$buyeraddress_details->city}-{$buyeraddress_details->zipcode}"; ?>
				<?php echo "{$buyeraddress_details->state_id}"; ?>
				<?php echo "{$buyeraddress_details->country_id}"; ?>
				<?php echo "{$buyeraddress_details->phone}"; ?>
			</option>
		<?php endforeach;?>
	</select>		
	</div>
<?php endif; ?>
		
<fieldset>
  	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_shipping_zipcode-lbl" for="paycart_cart_address_shipping_zipcode" class="required"><?php echo JText::_('COM_PAYCART_ZIPCODE'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[shipping][zipcode]" id="paycart_cart_address_shipping_zipcode" class="input-block-level form-control" required="" value = "<?php echo @$shipping_address->zipcode; ?>" />
			<span class="pc-error" for="paycart_cart_address_shipping_zipcode"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>

	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_shipping_to-lbl" for="paycart_cart_address_shipping_to" class="required"><?php echo JText::_('COM_PAYCART_FULL_NAME'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[shipping][to]" id="paycart_cart_address_shipping_to" class="input-block-level form-control" required="" value = "<?php echo @$shipping_address->to; ?>" />
			<span class="pc-error" for="paycart_cart_address_shipping_to"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_shipping_phone-lbl" for="paycart_cart_address_shipping_phone" class="required"><?php echo JText::_('COM_PAYCART_PHONE_NUMBER'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[shipping][phone]" id="paycart_cart_address_shipping_phone" class="input-block-level form-control" required="" value = "<?php echo @$shipping_address->phone; ?>" />
			<span class="pc-error" for="paycart_cart_address_shipping_phone"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
				
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_shipping_country_id-lbl" for="paycart_shipping_country_id" class="required"><?php echo JText::_('COM_PAYCART_COUNTRY'); ?></label>
 		</div>
 		<div class="controls">		 			
			<?php echo PaycartHtmlCountry::getList('paycart_cart_address[shipping][country_id]',  @$shipping_address->country_id,  'paycart_shipping_country_id', Array('class'=>'pc-chozen input-block-level form-control', 'required' => '')); ?>
			<span class="pc-error" for="paycart_shipping_country_id"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
				
				
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_shipping_state_id-lbl" for="paycart_shipping_state_id" class="required"><?php echo JText::_('COM_PAYCART_STATE'); ?></label>
 		</div>
 		<div class="controls">		 			
			<?php echo PaycartHtmlState::getList('paycart_cart_address[shipping][state_id]', @$shipping_address->state_id,  'paycart_shipping_state_id', Array('class'=>'pc-chozen input-block-level form-control', 'required' => ''), @$shipping_address->country_id);?>
			<span class="pc-error" for="paycart_shipping_state_id"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_shipping_city-lbl" for="paycart_cart_address_shipping_city" class="required"><?php echo JText::_('COM_PAYCART_CITY'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[shipping][city]" id="paycart_cart_address_shipping_city" class="input-block-level form-control" required="" value = "<?php echo @$shipping_address->city; ?>" />
			<span class="pc-error" for="paycart_cart_address_shipping_city"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_shipping_address-lbl" for="paycart_cart_address_shipping_address" class="required"><?php echo JText::_('COM_PAYCART_ADDRESS'); ?></label>
 		</div>
 		<div class="controls">
			<textarea name="paycart_cart_address[shipping][address]" id="paycart_cart_address_shipping_address" class="input-block-level form-control" required=""><?php echo @$shipping_address->address; ?></textarea>
			<span class="pc-error" for="paycart_cart_address_shipping_address"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_shipping_vat_number-lbl" for="paycart_cart_address_shipping_vat_number"><?php echo JText::_('COM_PAYCART_VATNUMBER'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[shipping][vat_number]" id="paycart_cart_address_shipping_vat_number" class="input-block-level form-control" value="<?php echo @$shipping_address->vat_number; ?>"/>
		</div>
	</div>
</fieldset>

	<script>
			
		(function($){
			<?php if (!$is_platform_mobile) : ?>
				$('#pc-buyeraddress-shipping-address, #paycart_shipping_country_id, #paycart_shipping_state_id').chosen();
			<?php endif;?>


			$('#paycart_shipping_country_id').on('change',  function(event, data) {
				var default_selected_state = 0;
				var success_callback = null;

				if (typeof data !== 'undefined' && typeof data.state_id !== 'undefined') {
					default_selected_state =  data.state_id;
				}

				if(typeof data !== 'undefined' && typeof data.success_callback !== 'undefined'){
					success_callback = data.success_callback;
				}

				paycart.address.state.onCountryChange('#paycart_shipping_country_id', '#paycart_shipping_state_id', default_selected_state,success_callback);
			});
			

			//if state already selected with country respected  then no need to get states (In checkout steps on restart)
			if (!$('#paycart_shipping_state_id').val()) { 
				paycart.address.state.onCountryChange('#paycart_shipping_country_id', '#paycart_shipping_state_id');
			}
		})(paycart.jQuery);
	
	</script>	 

<?php

