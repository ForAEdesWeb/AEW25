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

//load required javascripts
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/salvattore.js');
echo $this->loadTemplate('js');

?>

<?php if(!empty($categories)):?>
<!--	<div class="row"><h2 class="col-sm-12 page-header"><?php echo JText::_("COM_PAYCART_CATEGORIES");?></h2></div>-->
	<?php echo $this->loadTemplate('categories', compact('categories','formatter'));?>
<?php endif;?>

<?php if(count((array)$products)):?>
	<div class="row"><h2 class=" col-sm-12 page-header"><?php echo JText::_("COM_PAYCART_PRODUCTS");?></h2></div>
	<?php $data = new stdclass();?>
	<?php $data->products = $products;?>
	<div class='pc-products-wrapper row clearfix'>
		<div id="pc-products" class ='pc-products col-sm-12' data-columns>
			<?php echo JLayoutHelper::render('paycart_product_list', $data);?>
		</div>
	</div>
<?php endif;?>

<?php 