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
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.js');
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.css');

?>

<?php if(!function_exists('pc_mod_category_style')) :?>
	<?php function pc_mod_category_style(){?>
		<?php static $pc_mod_category_loaded = false; ?>
		<?php if($pc_mod_category_loaded == false) :?>
			<style>	
			.pc-mod-categories .pc-mod-category{
			    position: relative;
			    margin:10px;  
			    overflow: hidden;
			}  
			  
			.pc-mod-categories .pc-mod-category img {
			    width : 100%;
			    -webkit-transition: all 300ms ease-out;  
			    -moz-transition: all 300ms ease-out;  
			    -o-transition: all 300ms ease-out;  
			    -ms-transition: all 300ms ease-out;  
			    transition: all 300ms ease-out;  
			} 
			
			.pc-mod-categories .pc-mod-category .pc-mod-category-caption{
				position: absolute;
				background-color: rgba(0, 0, 0, 0.8);
			    color: #FFFFFF;
				z-index: 100;
				left: 0px;	
				right: 0px;
				top: 0px;
				bottom:0px;
				-webkit-transition: all 300ms ease-out 0s;  
			    -moz-transition: all 300ms ease-out 0s;  
			    -o-transition: all 300ms ease-out 0s;  
			    -ms-transition: all 300ms ease-out 0s;  
				transition: all 300ms ease-out 0s;	
				text-align:center;
				opacity: 0.7;
			}
			
			.pc-mod-categories .pc-mod-category .pc-mod-category-caption span{
				position: absolute;
				z-index: 101;
				top: 50%;
				left: 50%;
				margin-right: -50%;
				transform: translate(-50%, -50%);
				-moz-transform: translate(-50%, -50%);  
			    -o-transform: translate(-50%, -50%);  
			    -webkit-transform: translate(-50%, -50%); 
				font-size:24px;
			    line-height:30px;
			}	
				  
			.pc-mod-categories .pc-mod-category:hover .pc-mod-category-caption{    
			    opacity: 1;    
			}
			
			.pc-mod-categories .pc-mod-category:hover img {  
			       -moz-transform: scale(1.4);  
			       -o-transform: scale(1.4);  
			       -webkit-transform: scale(1.4);  
			       transform: scale(1.4);  
			}
			  
			.pc-mod-ellipsis{
			text-overflow: ellipsis;
				max-width:98%;
				overflow:hidden;
			}
		
			.customNavigation{
  				text-align: center;
			}
			.customNavigation i {
				cursor: pointer;
			}
			.product-head {margin:10px auto;}
			</style>
			<?php $pc_mod_category_loaded = true;?>
		<?php endif;?>
	<?php }?>
<?php endif;?>
<?php pc_mod_category_style();?>
<script>
(function($){
	$(document).ready(function() {
	 
	$("#pc-mod-categories-<?php echo $module->id;?>").owlCarousel({
	 
	autoPlay: false, //Set AutoPlay to 3 seconds
	 
	items : <?php echo $params->get('xl_cols', 5);?>,
	itemsDesktop : [1199,<?php echo $params->get('lg_cols', 4);?>],
	itemsDesktopSmall : [979,<?php echo $params->get('md_cols', 4);?>],
	itemsTablet : [768,<?php echo $params->get('sm_cols', 3);?>],
	itemsMobile : [400,<?php echo $params->get('xs_cols', 1);?>],
	navigation : false,
	pagination : false	
	});
	
	// Custom Navigation Events
	$("#pc-mod-cat-<?php echo $module->id;?> .next").click(function(){
		var owl = $("#pc-mod-categories-<?php echo $module->id;?>").data('owlCarousel'); 
		owl.next();
	 	});
	$("#pc-mod-cat-<?php echo $module->id;?> .prev").click(function(){
		var owl = $("#pc-mod-categories-<?php echo $module->id;?>").data('owlCarousel'); 
		owl.prev();
	});
	});
})(paycart.jQuery);


</script>
<?php if(!empty($selected_categories)):?>
	<?php $ids = explode(',', $selected_categories);?>	
<?php else:?>
	<?php $ids = array_keys($categories);?>
<?php endif;?>

<div class="pc-mod-cat" id="pc-mod-cat-<?php echo $module->id;?>">
	<div class="clearfix">
		<h3 class="pull-left product-head"><?php echo $module->title;?></h3>						
		<ul class="customNavigation pull-right inline list-inline">
		  <li><i class="prev fa fa-angle-left fa-3x"></i></li>
		  <li><i class="next fa fa-angle-right fa-3x"></i></li>
		</ul>
	</div>
	<div class="">
		<div id="pc-mod-categories-<?php echo $module->id;?>" class="pc-mod-categories">
			<?php foreach($ids as $id) : ?>
				<?php $instance 	= PaycartProductcategory::getInstance($categories[$id]->productcategory_id);?>
				<?php $media 		= $instance->getCoverMedia(true);?>
				<a href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$categories[$id]->productcategory_id);?>"
					title="<?php echo $categories[$id]->title;?>">
					<div class="pc-mod-category item">
						<img class="img-thumbnail" src="<?php echo $media['squared'];?>" alt="<?php echo $categories[$id]->title;?>">
						<span class="pc-mod-category-caption">
							<span class="pc-mod-ellipsis"><?php echo $categories[$id]->title;?>
							</span>
						</span>
					</div>
				</a>
			<?php endforeach;?>				
		</div>
	</div>
</div>
<?php 
