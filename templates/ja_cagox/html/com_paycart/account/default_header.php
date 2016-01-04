<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );?>
<div class="well">
	<div class="row">
		<div class="col-sm-10">
			<img class="pc-account-header-avatar img-thumbnail pull-left" src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $buyer->getEmail() ) ) ) . "?size=48";?>" alt="<?php echo $buyer->getRealName();?>">						
			<h3 class="pc-account-header-name"><?php echo $buyer->getRealName();?></h3>
			<ul class="pc-account-header-details list-inline text-muted text-shadow-white">
				<li><i class="fa fa-envelope-o"></i> <?php echo JText::_('COM_PAYCART_EMAIL');?> : <?php echo $buyer->getEmail();?></li>
				<?php $phone = $buyer->getDefaultPhone();?>
				<?php if(!empty($phone)) :?>
					<li>|</li>
					<li><i class="fa fa-phone"></i> <?php echo JText::_('COM_PAYCART_CONTACT');?> : <?php echo $phone;?></li>
				<?php endif;?>
			</ul>
		</div>
		
		<?php if(PaycartFactory::getUser()->id) :?>
		<div class="col-sm-2 pull-right text-right pc-account-header-logout">
			<form id="login-form" method="post" action="index.php">
				<div class="logout-button">
					<button class="btn-link" for="logout-dashboard"><i class="fa fa-sign-out"></i> Logout</button>
					<input type="submit" value="Log out" class="hide" id="logout-dashboard" name="Submit">
					<input type="hidden" value="com_users" name="option">
					<input type="hidden" value="user.logout" name="task">
					<input type="hidden" value="<?php echo base64_encode(JRoute::_('index.php?option=com_paycart&view=account&task=login'));?>" name="return">
					<?php echo JHtml::_('form.token'); ?>
				</div>
			</form>
		</div>
		<?php endif;?>
	</div>
</div>
<?php 
