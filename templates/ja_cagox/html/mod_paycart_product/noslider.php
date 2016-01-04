<?php

/**
 * @copyright   Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Layouts
 * @contact	support+paycart@readybytes.in
 * @author 	Manish Trivedi  
 */

/**
 * List of Populated Variables
 * $displayData = have all required data 
 * $displayData->return_link		// link after logout 
 * 
 */

// no direct access
defined('_JEXEC') or die;

// load bootsrap, font-awesome
Rb_HelperTemplate::loadMedia(array('rb', 'font-awesome'));
Rb_HelperTemplate::loadSetupEnv();
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/paycart.js');

?>
<?php if(!function_exists('pc_mod_product_noslide_style')) :?>
	<?php function pc_mod_product_noslide_style(){?>
		<?php static $pc_mod_product_loaded = false; ?>
			<?php if($pc_mod_product_loaded == false) :?>
				<style>
					.pc-mod-product-noslide{
						margin-bottom: 20px;
					}				
					.pc-mod-product-noslide a img{
						display: inline-block;	
						width: 100%;			    	
					}	
					.pc-mod-product-noslide:hover img{
						opacity: 0.6;
						-webkit-transition: 0.5s;
					  	-o-transition: 0.5s;
					  	transition: 0.5s;
					}					
				</style>
				<?php $pc_mod_product_loaded = true;?>
			<?php endif;?>
		<?php }?>
	<?php endif;?>
<?php pc_mod_product_noslide_style();?>


<?php if(!empty($selected_products)):?>
	<?php $ids = explode(',', $selected_products);?>	
<?php else:?>
	<?php $ids = array_keys($products);?>
<?php endif;?>
<?php $formatter = PaycartFactory::getHelper('format');?>
<div class="pc-module-product-noslide" id="pc-module-products-<?php echo $module->id;?>">	
	<div id="pc-mod-products-<?php echo $module->id;?>" class="pc-mod-products-noslide">
		<div class="row">
		<?php $counter =0; ?>		
		<?php foreach($ids as $id) : ?>
			<?php $instance 	= PaycartProduct::getInstance($products[$id]->product_id);?>
			<?php $media 		= $instance->getCoverMedia(true);?>
			
			<div  class="col-sm-3  pc-mod-product-noslide">
			<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$products[$id]->product_id);?>"
				title="<?php echo $products[$id]->title;?>">
				<img class="img-responsive" src="<?php echo @$media['optimized'];?>" alt="<?php echo $products[$id]->title;?>">							
				<div class="text-center"><?php echo $products[$id]->title;?></div>
				<div class="text-center  text-muted"><small><?php echo $formatter->amount($products[$id]->price);?></small></div>				
			</a>
			</div>
			<?php $counter++;?>
			<?php if($counter%4==0):?>
				</div>
				<div class="row">
			<?php endif;?>
		<?php endforeach;?>		
		</div>		
	</div>
</div>
<?php 
