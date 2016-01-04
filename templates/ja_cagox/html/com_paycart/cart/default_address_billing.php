<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

/**
 * Populated varaible ::
 * $title : title required or not
 * $shipping_to_billing ::  html display or not
 * $billing_address 	:: 	stdclass object, contain previous address data 
 * 
 */

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>
<?php if (!empty($buyer_addresses)) :?>
	<div class="control-group form-group">	
	<select name='select_billing_address' id="pc-buyeraddress-billing-address" class="pc-chozen input-block-level form-control" onChange='paycart.cart.address.onSelect(this.value, "billing");'>
		<option value='0'> <?php echo JText::_('COM_PAYCART_CART_SELECT_EXISTING_ADDRESS'); ?> </option>
		<?php foreach ($buyer_addresses as $buyeaddress_id => $buyeraddress_details):?>
			<?php $selected = ($billing_address_id == $buyeraddress_details->buyeraddress_id) ? 'selected' : ''; ?>
			<option value='<?php echo $buyeraddress_details->buyeraddress_id?>'	<?php echo $selected; ?>>
				<?php echo $buyeraddress_details->address.","; ?>
				<?php echo "{$buyeraddress_details->city}-{$buyeraddress_details->zipcode},"; ?>
				<?php echo "{$formatter->state($buyeraddress_details->state_id)},"; ?>
				<?php echo "{$formatter->country($buyeraddress_details->country_id)},"; ?>
				<?php echo "{$buyeraddress_details->phone}"; ?>
			</option>
		<?php endforeach;?>
	</select>
	</div>				
<?php endif; ?>
	
  <fieldset>
  	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_zipcode-lbl" for="paycart_cart_address_billing_zipcode" class="required"><?php echo JText::_('COM_PAYCART_ZIPCODE'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][zipcode]" id="paycart_cart_address_billing_zipcode" class="input-block-level form-control" required="" value = "<?php echo @$billing_address->zipcode; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_zipcode"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>

	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_to-lbl" for="paycart_cart_address_billing_to" class="required"><?php echo JText::_('COM_PAYCART_FULL_NAME'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][to]" id="paycart_cart_address_billing_to" class="input-block-level form-control" required="" value = "<?php echo @$billing_address->to; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_to"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_phone-lbl" for="paycart_cart_address_billing_phone" class="required"><?php echo JText::_('COM_PAYCART_PHONE_NUMBER'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][phone]" id="paycart_cart_address_billing_phone" class="input-block-level form-control" required="" value = "<?php echo @$billing_address->phone; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_phone"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
				
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_billing_country_id-lbl" for="paycart_billing_country_id" class="required"><?php echo JText::_('COM_PAYCART_COUNTRY'); ?></label>
 		</div>
 		<div class="controls">		 			
			<?php echo PaycartHtmlCountry::getList('paycart_cart_address[billing][country_id]',  @$billing_address->country_id,  'paycart_billing_country_id', Array('class'=>'pc-chozen input-block-level form-control', 'required' => '')); ?>
			<span class="pc-error" for="paycart_billing_country_id"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
				
				
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_billing_state_id-lbl" for="paycart_billing_state_id" class="required"><?php echo JText::_('COM_PAYCART_STATE'); ?></label>
 		</div>
 		<div class="controls">		 			
			<?php echo PaycartHtmlState::getList('paycart_cart_address[billing][state_id]', @$billing_address->state_id,  'paycart_billing_state_id', Array('class'=>'pc-chozen input-block-level form-control', 'required' => ''), @$billing_address->country_id);?>
			<span class="pc-error" for="paycart_billing_state_id"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_city-lbl" for="paycart_cart_address_billing_city" class="required"><?php echo JText::_('COM_PAYCART_CITY'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][city]" id="paycart_cart_address_billing_city" class="input-block-level form-control" required="" value = "<?php echo @$billing_address->city; ?>" />
			<span class="pc-error" for="paycart_cart_address_billing_city"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_address-lbl" for="paycart_cart_address_billing_address" class="required"><?php echo JText::_('COM_PAYCART_ADDRESS'); ?></label>
 		</div>
 		<div class="controls">
			<textarea name="paycart_cart_address[billing][address]" id="paycart_cart_address_billing_address" class="input-block-level form-control" required=""><?php echo @$billing_address->address; ?></textarea>
			<span class="pc-error" for="paycart_cart_address_billing_address"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
		</div>
	</div>
	
	<div class="control-group form-group">	
	 	<div class="control-label">
 			<label id="paycart_cart_address_billing_vat_number-lbl" for="paycart_cart_address_billing_vat_number"><?php echo JText::_('COM_PAYCART_VATNUMBER'); ?></label>
 		</div>
 		<div class="controls">
			<input type="text" name="paycart_cart_address[billing][vat_number]" id="paycart_cart_address_billing_vat_number" class="input-block-level form-control" value="<?php echo @$billing_address->vat_number; ?>"/>
		</div>
	</div>
</fieldset>
		
<script>
	<?php // @PCTODO : move to proper location?>
	(function($){

		$('#paycart_billing_country_id').on('change',  function(event, data) {
			var default_selected_state = 0;
			var success_callback = null;

			if (typeof data !== 'undefined' && typeof data.state_id !== 'undefined') {
				default_selected_state =  data.state_id;
			}

			if(typeof data !== 'undefined' && typeof data.success_callback !== 'undefined'){
				success_callback = data.success_callback;
			}
			
			paycart.address.state.onCountryChange('#paycart_billing_country_id', '#paycart_billing_state_id', default_selected_state,success_callback);
		});
		//if state already selected then no need to get states
		if (!$('#paycart_billing_state_id').val()) { 
			paycart.address.state.onCountryChange('#paycart_billing_country_id', '#paycart_billing_state_id');
		}
		
		<?php if (!$is_platform_mobile) : ?>
			$('#pc-buyeraddress-billing-address, #paycart_billing_country_id, #paycart_billing_state_id').chosen();
		<?php endif;?>	
		
	})(paycart.jQuery);

</script>	 

<?php

