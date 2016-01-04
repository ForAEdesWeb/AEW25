<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );?>

<?php 
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.min.js');
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/owl.carousel.css');

echo $this->loadTemplate('js');

/**
 * Template Parameters
 * @param $isAvailableInStock
 * @param $product
 * @param $positions
 * 
 */

$attributes = $product->getAttributes();
$postionedAttributes = (array)$product->getPositionedAttributes();

 echo  Rb_HelperTemplate::renderLayout('paycart_spinner'); 

?>
<script>
paycart.queue.push('$("#pc-screenshots-carousel").owlCarousel({ lazyLoad : true, singleItem:true, autoHeight : true, pagination:true });');
</script>

<div class='pc-product-fullview-wrapper clearfix'>

	<h1 class="visible-xs-block pc-break-word"><?php echo $product->getTitle(); ?></h1>
	 
	 <div class="row">
	 
		 <!-- ======================
				Left Layout
		 =========================== -->
		 <div class="col-sm-12 col-md-6">
		 	<div id="pc-screenshots-carousel" class="owl-carousel pc-screenshots center text-center">
			 	<?php $counter = 0; ?>
			    <?php foreach($product->getImages() as $mediaId => $detail):?>
				    <div>
				    	<img class="lazyOwl" data-src="<?php echo $detail['original'];?>" />
				    </div>
				    <?php $counter++; ?>
				<?php endforeach;?>
	 		</div>
		 </div>
	
		 <!-- ======================
				Right Layout
		 =========================== -->
		 <div class="col-sm-12 col-md-6">
				<h1 class="hidden-xs pc-break-word pc-product-detail-title"><?php echo $product->getTitle(); ?></h1>	
		 		<h3><?php echo JText::_("COM_PAYCART_PRICE");?> : 
		 			<span><?php echo $formatter->amount($product->getPrice(),true);?></span>	
		 		</h3>
		 		
		 		<!-- ======================
				Position == product-overview	
		 		=========================== -->		 		
		 		<div class="pc-product-overview">
		 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>
		 				<ul>
		 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
		 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
		 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
								<?php $options 	= $instance->getOptions();?> 
								<li><?php echo $options[$attributes[$attributeId]]->title;?></li>
							<?php endif?>	                         
		 				<?php endforeach;?>
		 				</ul>
		 			<?php endif;?>
		 		</div>
		 		
		 		
		 		<!-- Filterable Attributes -->
		 		<?php if(!empty($selectors)):?>
		 		<div>
		 		    <form class="pc-product-selector" method="post">
		 		    	 <fieldset>		 		    	 	
					    	<?php foreach ($selectors as $productAttributeId => $data):?>
					    		<?php $instance  = PaycartProductAttribute::getInstance($productAttributeId);?>
					    		<hr/>
					    		<div class="row">
					    		<div class="form-group pull-left col-xs-12">
					    			<label class="text-muted"><?php echo $instance->getTitle();?>:</label>
					    			<?php echo $product->getAttributeHtml('selector', $productAttributeId, $data['selectedvalue'],$data['options']);?>
					    		 </div>
					    		 </div>
					        <?php endforeach;?>							
							<!-- -->
							<input type="hidden" name="pc-product-base-attribute" class="pc-product-base-attribute"/>
					    </fieldset>
				    </form>
    			</div>
    			<?php endif;?>
		 		<hr/>
		 		<!-- buy now -->
                <?php if($isAvailableInStock):?>                     
				<div class="row clearfix">
                	<div class="col-sm-12 col-md-6">                 
                    	<?php if(!$isExistInCart):?>
                        	<a class="btn btn-block btn-lg btn-primary pc-btn-buynow" href="<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=buy&product_id='.$product->getId()); ?>">
                            	<?php echo JText::_("COM_PAYCART_PRODUCT_BUY_NOW");?>
                            </a>
                        <?php else:?>
                        	<div class='text-center text-info'><strong><?php echo JText::_('COM_PAYCART_PRODUCT_ADDED_TO_CART')?></strong></div>
                        <?php endif;?>
                    </div>
                    <div class="col-sm-12 col-md-6">    
                    	<?php if(!$isExistInCart):?>            
                        	<button class="btn btn-block btn-lg pc-btn-addtocart btn-default" onClick="paycart.product.addtocart(<?php echo $product->getId();?>);">
                        		<?php echo JText::_("COM_PAYCART_PRODUCT_ADD_TO_CART");?>
                            </button>
                        <?php else:?>
                        	<button class="btn btn-block btn-lg pc-btn-addtocart btn-default" onClick='rb.url.redirect("<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=display'); ?>"); return false;'>
                        		<?php echo JText::_('COM_PAYCART_CART_VIEW')?>&nbsp;&nbsp; <i class='fa fa-chevron-right'></i>
                        	</button>
                        <?php endif;?>
                    </div>
              	</div>
              	<?php else :?>
                	<div class="row">
                		<div class="col-sm-12 col-md-12">
                    	<h2 class="text-error"><?php echo JText::_("COM_PAYCART_PRODUCT_IS_OUT_OF_STOCK");?></h2>
                    	</div>
                    </div>
                <?php endif;?>
                <hr/>
                
                <!-- ======================
				Position == product-addons	
		 		=========================== -->		 		
		 		<div class="row">		 		
		 		<div class="pc-product-addons col-xs-12"> 			
		 			<?php if(isset($postionedAttributes['product-addons']) && !empty($postionedAttributes['product-addons'])) : ?>
		 				<ul>
		 				<?php foreach($postionedAttributes['product-addons'] as $attributeId) : ?>
		 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
		 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
								<?php $options 	= $instance->getOptions();?> 
								<li><?php echo $options[$attributes[$attributeId]]->title;?></li>
							<?php endif?>	                         
		 				<?php endforeach;?>
		 				</ul>
		 			<?php endif;?>
		 		</div>
		 		</div>
		 </div>
	 </div>
	 
	 <br>
	 
	 <!-- ===============================
	 		    Full layout 
	 ==================================== -->
	 <div class="row">	 
	  <div class="col-sm-12">
	  	<?php $description = $product->getDescription();?>
	  	<?php if(!empty($description) || (isset($postionedAttributes['product-details']) && !empty($postionedAttributes['product-details']))):?>
		 	<!-- accordion1 Detail description of product -->
		 	<div class="accordion panel-group" id="accordion-details-id">		 	
		 		<div class="accordion-group panel panel-default">
		 			<div class="accordion-heading panel-heading" role="tab" id="accordion-details-heading">			 		
			 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-details-id" aria-controls="accordion-details-body-id" href="#accordion-details-body-id">
			 				<div class="panel-title"> <?php echo JText::_("COM_PAYCART_DETAILS");?>		 				
			 				<div class="accordian-icon pull-right "></div></div>
			 			</a>		
			 		</div>
			 		<!-- use class "in" for keeping it open -->
			 		 <div class="accordion-body collapse in panel-collapse" id="accordion-details-body-id" role="tabpanel" aria-labelledby="accordion-details-heading">
			 		 	<div class="accordion-inner panel-body">
			 		 		<div class="pc-product-details">
				 		 		<?php if(!empty($description)) : ?>
					 		 		<div class="row">
					 		 			<div class="col-sm-12">
					 		 				<?php echo $description;?>
					 		 			</div>
					 		 		</div>
				 		 		<?php endif;?>
			 		 		
			 		 			<div class="row">
			 		 				<div class="col-sm-12">						 		
						 			<p>&nbsp;</p>		 			
						 			<?php if(isset($postionedAttributes['product-details']) && !empty($postionedAttributes['product-details'])) : ?>
						 				<ul>
						 				<?php foreach($postionedAttributes['product-details'] as $attributeId) : ?>
						 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
						 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
												<?php $options 	= $instance->getOptions();?> 
												<li><?php echo $options[$attributes[$attributeId]]->title;?></li>
											<?php endif?>	                         
						 				<?php endforeach;?>
						 				</ul>
						 			<?php endif;?>
						 			</div>
						 		</div>
						 	</div>
			 		 	</div>
			 		 </div>
		 		 </div>
		 	</div>
		<?php endif;?>
	 	
	 	
	 <?php if(isset($postionedAttributes['product-specifications']) && !empty($postionedAttributes['product-specifications'])) : ?>
		 	<!-- Specification -->		 	
		 	<div class="accordion panel-group" id="accordion-specification-id">
		 		<div class="accordion-group panel panel-default">
			 		<div class="accordion-heading panel-heading" role="tab" id="accordion-specification-heading">
			 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-specification-id" aria-controls="accordion-specification-body-id" href="#accordion-specification-body-id">
			 				<div class="panel-title"><?php echo JText::_("COM_PAYCART_PRODUCT_SPECIFICATION");?><div class="accordian-icon pull-right "></div></div>
			 			</a>		
			 		</div>
			 		
			 		 <div class="accordion-body collapse in panel-collapse" id="accordion-specification-body-id">
			 		 	<div class="accordion-inner panel-body">
	                        <table class="pc-product-specification table table-responsive">
	                          	
	                            <?php foreach ($postionedAttributes['product-specifications'] as $attributeId):?>
	                            	<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
		                                <?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
		                                <?php $options = $instance->getOptions();?>
		                                <?php if($instance->getType() == 'header') : ?>
		                                	</table>
		                               		<table class="pc-product-specification table table-responsive">
			                             	   	<tr>
		                          					<th colspan="2" class="pc-product-attribute-header">
		                          					<?php echo $options[$attributes[$attributeId]]->title;?>
		                          					</th>
		                          				</tr>
		                                <?php else : ?>
			                                <tr>
			                                	<td width="25%">
			                                    	<?php echo $instance->getTitle();?>
			                                	</td>
			                                    <td width="75%">
			                                         <?php echo $options[$attributes[$attributeId]]->title;?>
			                                    </td>
			                                </tr>
										<?php endif;?>
	                                <?php endif;?>         
	                              <?php endforeach;?>
	                        </table>
			 		 	</div>
			 		 </div>
		 		 </div>
	 		</div>
	 	<?php endif;?>
	 	
	 	<!-- <div class="accordion" id="accordion-id3">
	 		<div class="accordion-group">
		 		<div class="accordion-heading">
		 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id3" data-target=".accordion-body-id3">
		 				<h2>Shipping</h2>
		 			</a>		
		 		</div>
		 		
		 		 <div class="accordion-body collapse accordion-body-id3">
		 		 	<div class="accordion-inner">
		 		 		<h3>content of a page when looking at its layout.</h3>
		 		 	 	<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
		 		 	</div>
		 		 </div>
	 		 </div>
	 	</div>
	 	
	 	--></div>
	 </div>
</div>
<?php 