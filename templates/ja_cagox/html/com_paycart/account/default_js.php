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
?>
<script>
(function($){
	
	paycart.account = {};
	paycart.account.address = {}
	paycart.account.address.add = function(){
		//Validation Checking
		if(!paycart.formvalidator.isValid('#paycart_buyeraddress_form')){
			return false;
		}
		
		var link  = 'index.php?option=com_paycart&view=account&task=saveNewAddress';
		// get all form data for post
		var postData = $("#paycart_buyeraddress_form").serializeArray();

		paycart.ajax.go(link, postData);
	};
	
	// data is json string
	paycart.account.address.error = function(data){
		var response = $.parseJSON(data);
		paycart.formvalidator.handleResponse(false, $("#paycart-buyer-address-error"), 'error', data.message);		
		
	};

	paycart.account.address.remove = function(id){
		var link  = 'index.php?option=com_paycart&view=account&task=removeAddress&address_id='+id;
		var request = [];
		request['url'] 	= link;
		request['data']	= {};
		request['success_callback']	= paycart.account.address.remove.response;
		paycart.request(request);
	};

	paycart.account.address.remove.response = function(response){
		var address_id 	 = response.address_id;
		if(response.isValid){
			$('[data-pc-selector="pc-address-'+address_id+'"]').remove();
			return true;
		}		
		
		var message = '';
		for(var index in response.errors){
			if(response.errors.hasOwnProperty(index) == false){
				continue;
			}
			message += "\n" + response.errors[index].message;
		}
		paycart.formvalidator.handleResponse(false, $('#pc-address-error-'+address_id), 'error', message);
	};

	paycart.account.login = function(){
		var error_mapper = {'email' : '#paycart_account_loginform_email', 'password': '#paycart_account_loginform_password', 'header' : '#paycart_account_login'};
		
		for (var index in error_mapper){
			paycart.formvalidator.handleResponse(true, $(error_mapper[index]));
		}
		//console.log('paycart.cart.login.do');
		if(paycart.formvalidator.isValid('#pc-account-login-form')){
			// get all form data for post	
			var postData 	= $("#pc-account-login-form").serializeArray();
			var link  		= 'index.php?option=com_paycart&view=account&task=login';
			paycart.ajax.go(link, postData);
		}
		return false;					
	};

	paycart.account.login.error = function(errors){
		var error_mapper = {'email' : '#paycart_account_loginform_email', 'password': '#paycart_account_loginform_password', 'header' : '#paycart_account_login'};
		
		for (var index in errors){
			paycart.formvalidator.handleResponse(false, $(error_mapper[errors[index].for]), errors[index].message_type, errors[index].message);
		}
	};

	paycart.account.guest = function(){
		var error_mapper = {'email' : '#paycart_account_guestform_email', 'order_id': '#paycart_account_guestform_order_id', 'header' : '#paycart_account_guest'};
		
		for (var index in error_mapper){
			paycart.formvalidator.handleResponse(true, $(error_mapper[index]));
		}
		//console.log('paycart.cart.login.do');
		if(paycart.formvalidator.isValid('#pc-account-guest-form')){
			// get all form data for post	
			var postData 	= $("#pc-account-guest-form").serializeArray();
			postData['spinner_selector'] = '#paycart-ajax-spinner';
			var link  		= 'index.php?option=com_paycart&view=account&task=guest';
			paycart.ajax.go(link, postData);
		}
		return false;					
	};

	paycart.account.guest.response = function(response){
		if(response.isValid){
			$('[data-ppc-selector="pc-account-guest-form-header"]').html(response.message).removeClass('hide');
			setTimeout(function(){ $('[data-ppc-selector="pc-account-guest-form-header"]').html('').addClass('hide'); }, 7000);	
			$('#paycart_account_guestform_email').val('');
			$('#paycart_account_guestform_order_id').val('');
			return true;
		}
		
		var errors = response.errors;
		var error_mapper = {'email' : '#paycart_account_guestform_email', 'order_id': '#paycart_account_guestform_order_id', 'header' : '#paycart_account_guest'};
		
		for (var index in errors){
			paycart.formvalidator.handleResponse(false, $(error_mapper[errors[index].for]), errors[index].message_type, errors[index].message);
		}
	};
	
	$(document).ready(function(){

		paycart.formvalidator.initialize('.pc-form-validate');

		$('form.pc-form-validate').submit(function(e){
			if(paycart.formvalidator.isValid($(this))){
				return true;
			}
			e.preventDefault();
			return false;
		});
		
		$('[data-pc-cart-url]').click(function(){
			location.href = $(this).attr('data-pc-cart-url');			
		});

		$('[data-toggle="popover"]').popover({
			html : true,
		   content: function() {
		      return $(this).next().html();
			}
		});

		$('body').on('click', function (e) {
		    $('[data-toggle="popover"]').each(function () {
		        //the 'is' for buttons that trigger popups
		        //the 'has' for icons within a button that triggers a popup
		        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
		            $(this).popover('hide');
		        }
		    });
		});


		$('[data-pc-selector="pc-account-address-new-modal"]').click(function(){
			var link  = 'index.php?option=com_paycart&view=account&task=addNewAddress';
			paycart.url.modal(link, null, '600px');
			return false;
		});


		$('[data-pc-selector="pc-address-remove"]').click(function(){
			return paycart.account.address.remove($(this).attr('data-pc-id'));
		});
	
		// submit login form
		$('[data-pc-selector="pc-login"]').click(function(){
			paycart.account.login();
			return false;
		});

		// submit login form
		$('[data-pc-selector="pc-guest"]').click(function(){
			paycart.account.guest();
			return false;
		});

		$('a[href="#pc-account-guest-form"]').on('shown.bs.tab', function (e) {
			paycart.formvalidator.initialize('.pc-form-validate');
		});
	});
	
})(paycart.jQuery);
</script>
<?php 