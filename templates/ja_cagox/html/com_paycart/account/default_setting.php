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
				<form class="form-horizontal pc-form-validate" action="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=update&entity=info');?>" method="post" noValidate>
					<fieldset>
						<legend><?php echo JText::_('COM_PAYCART_ACCOUNT_INFORMATION');?></legend>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">
								<label class="" id="jform_spacer-lbl">
									<?php echo JText::_('COM_PAYCART_EMAIL');?>
								</label>
							</div>
							<div class="controls col-sm-6">
								<input type="email" class="form-control" disabled="" value="<?php echo $buyer->getEmail();?>">
							</div>
						</div>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">
								<label class="" for="paycart_account_form_realname">
									<?php echo JText::_('COM_PAYCART_FULL_NAME');?>
								</label>
							</div>
							<div class="controls col-sm-6">
								<input class="form-control" type="text" name="paycart_account_form[realname]" id="paycart_account_form_realname" value="<?php echo $buyer->getRealName();?>" required="">
							</div>
						</div>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">
								<label class="" for="paycart_account_form_default_phone">
									<?php echo JText::_('COM_PAYCART_PHONE');?>
								</label>
							</div>
							<div class="controls col-sm-6">
								<input class="form-control" type="text" name="paycart_account_form[default_phone]" id="paycart_account_form_default_phone" value="<?php echo $buyer->getDefaultPhone();?>" required="">
							</div>
						</div>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">								
							</div>
							<div class="controls col-sm-6">
								<button class="btn btn-primary" type="submit"><?php echo JText::_('COM_PAYCART_SAVE_CHANGES');?></button>
							</div>
						</div>
					</fieldset>
				</form>
				
				<form class="form-horizontal pc-form-validate" action="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=update&entity=password');?>" method="post" noValidate>
					<fieldset>
						<legend><?php echo JText::_('COM_PAYCART_ACCOUNT_CHANGE_PASSWORD');?></legend>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">
								<label class="" for="paycart_account_form_current_password">
									<?php echo JText::_('COM_PAYCART_ACCOUNT_CURRENT_PASSWORD');?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>
							<div class="controls col-sm-6">
								<input type="password" class="validate-password form-control" name="paycart_account_form[current_password]" id="paycart_account_form_current_password" required="">								
							</div>
						</div>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">
								<label class="" for="paycart_account_form_new_password">
									<?php echo JText::_('COM_PAYCART_ACCOUNT_NEW_PASSWORD');?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>
							<div class="controls col-sm-6">
								<input type="password" class="validate-password form-control" name="paycart_account_form[new_password]" id="paycart_account_form_new_password" required="">
							</div>
						</div>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">
								<label class="" for="paycart_account_form_retype_new_password">
									<?php echo JText::_('COM_PAYCART_ACCOUNT_RETYPE_NEW_PASSWORD');?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>
							<div class="controls col-sm-6">
								<input type="password" class="validate-password form-control" name="paycart_account_form[retype_new_password]" id="paycart_account_form_retype_new_password" required="">
							</div>
						</div>
						<div class="control-group form-group">
							<div class="control-label col-sm-3">								
							</div>
							<div class="controls col-sm-6">
								<button class="btn btn-primary" type="submit"><?php echo JText::_('COM_PAYCART_SAVE_CHANGES');?></button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>      	
	</div>
</div>