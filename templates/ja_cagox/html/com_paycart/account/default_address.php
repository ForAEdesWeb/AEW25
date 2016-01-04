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
	<div id="pc-account" class ='pc-account pc-account-address col-sm-12 clearfix' >
	
		<!-- START HEADER -->
		<div class="pc-account-header hidden-phone">
			<?php echo $this->loadTemplate('header');?>
		</div>
		<!-- START HEADER -->
		
		
		<!-- START BODY -->
		<div class="row">
			<div class="col-sm-3 hidden-xs">
				<?php echo $this->loadTemplate('sidebar');?>
			</div>
			
			<div class="col-sm-9 pc-account-addresses">
				<div class="row">
					<div class="col-sm-12">
					<fieldset>
						<legend>
							<?php echo JText::_('COM_PAYCART_MANAGE_ADDRESS');?> 
							<span class="pull-right">
								<a href="#" class="btn btn-primary" data-pc-selector="pc-account-address-new-modal"><?php echo JText::_('COM_PAYCART_ADD_NEW')?></a>
							</span>
						</legend>
					</fieldset>					
					</div>
				</div>
				<div class="row">				
					<?php $counter = 0;?>				
					<?php $default_address = $buyer->getDefaultAddress();?>
					<?php $defaultAddressObject = null;?>
					<?php if(isset($addresses[$default_address])):?>
						<?php $defaultAddressObject = $addresses[$default_address];?>
						<?php unset($addresses[$default_address]);?>
						<?php array_unshift($addresses, $defaultAddressObject);?>
					<?php endif;?>
					<?php foreach($addresses as $address):?>
							<div class="col-sm-6" data-pc-selector="pc-address-<?php echo $address->buyeraddress_id;?>">
								<div class="accordion">
									<div class="accordion-group panel panel-default">
										<div class="accordion-heading panel-heading">
											<span class="accordion-toggle heading panel-title">
												<?php echo $address->to;?>
											</span>
										</div>
										<div class="accordion-body collapse in">
											<div class="accordion-inner text-muted panel-body">
												<div><?php echo $address->address;?></div>
												<div><?php echo $address->city;?> - <?php echo $address->zipcode;?></div>
												<div><?php echo $formatter->state($address->state_id);?> , <?php echo $formatter->country($address->country_id);?></div>
												<div><?php echo JText::_('COM_PAYCART_PHONE');?> - <?php echo $address->phone;?></div>
												<hr/>														
												<div>	
													<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=setDefaultAddress&address_id='.$address->buyeraddress_id);?>">
														<?php if($default_address == $address->buyeraddress_id):?>
															<i class="fa fa-dot-circle-o fa-2x text-success" title="<?php echo JText::_('COM_PAYCART_ACCOUNT_ADDRESS_DEFAULT');?>"> </i>
														<?php else:?>
															<i class="fa fa-circle-o fa-2x text-muted" title="<?php echo JText::_('COM_PAYCART_ACCOUNT_ADDRESS_MAKE_IT_DEFAULT');?>"> </i>
														<?php endif?>
													</a>
													<span class="<?php echo ($default_address == $address->buyeraddress_id) ? 'text-success': ''; ?>">
														<?php echo JText::_('COM_PAYCART_ACCOUNT_ADDRESS_DEFAULT');?>
													</span>
													<span class="pull-right"><a href="#" class="text-danger" data-pc-selector="pc-address-remove" data-pc-id="<?php echo $address->buyeraddress_id;?>" onClick="return false;"><i class="fa fa-trash fa-2x"> </i></a></span>
												</div>
												<br/>
												<div>
													<span for="pc-address-error-<?php echo $address->buyeraddress_id;?>" id="pc-address-error-<?php echo $address->buyeraddress_id;?>" class="pc-error hide">
														<?php echo JText::_('COM_PAYCART_ACCOUNT_ADDRERSS_NOT_REMOVED');?>
													</span>
												</div>  
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php $counter++;?>						
					<?php endforeach;?>
				</div>
			</div>			
		</div>      	
	</div>
</div>
<?php 