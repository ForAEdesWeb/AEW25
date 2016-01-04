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

<div class='pc-categories-wrapper clearfix'>
	<div id="pc-categories" class='pc-categories  clearfix'>
		<div class="row">		
		<?php foreach($categories as $c) : ?>
			<?php $instance = PaycartProductcategory::getInstance($c->productcategory_id, $c);?>
	
			<div class="col-sm-6 col-md-4 col-xs-12">
			<h2 class="text-center">
			<?php $media = $instance->getCoverMedia();?>
			<?php $url   = PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$c->productcategory_id);?>
				<a href="<?php echo $url?>" title="<?php echo $instance->getTitle();?>">
					<img src="<?php echo $media['squared'];?>">
					<br>
					<?php echo $instance->getTitle();?>																
				</a>
			</h2>
			</div>  
		<?php endforeach;?>
		</div>
	</div>
</div>
<?php

