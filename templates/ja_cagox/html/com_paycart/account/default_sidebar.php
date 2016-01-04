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
<ul class="nav nav-pills nav-stacked">
	<li class="<?php echo $task == 'display' || $task == 'order' ? 'active' : ''?>">
		<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=display');?>"><i class="fa fa-tags"> </i> <?php echo JText::_('COM_PAYCART_MY_ORDERS');?></a>
	</li>
	<li class="<?php echo $task == 'address' ? 'active' : ''?>">
		<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=address');?>"><i class="fa fa-home"> </i> <?php echo JText::_('COM_PAYCART_MANAGE_ADDRESS');?></a>
	</li>
	<li class="<?php echo $task == 'setting' ? 'active' : ''?>">
		<a href="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=setting');?>"><i class="fa fa-user"> </i> <?php echo JText::_('COM_PAYCART_ACCOUNT_SETTINGS');?></a>
	</li>
</ul>
<?php 
