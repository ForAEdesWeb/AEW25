<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$records = (array)$products;
$appliedAttrIds = array_keys($filters->attribute->appliedAttr);
?>

<script type="text/javascript">
(function($){
	$(document).ready(function(){
		paycart.product.filter.bindActions();
	})
})(paycart.jQuery);
</script>

<!-- ==================================================================
       1. Top section : Search word & total result count and sorting
     ================================================================== -->
	<div class="row">
		<div class="pull-left col-sm-12">
			<?php if(!empty($searchWord)):?>
				<h3>
					<?php echo JText::_('COM_PAYCART_SEARCH')?> : <?php echo $searchWord ?>
					<span class="text-muted"><?php echo ' ('.$count.' '.(($count > 1)?JText::_("COM_PAYCART_ITEMS"):JText::_("COM_PAYCART_ITEM")).')';?></span>
				</h3>
			<?php elseif(!empty($filters->core->selectedCategoryId)):?>
				<h3>
					<?php echo JText::_('COM_PAYCART_CATEGORY')?> : <?php echo PaycartProductcategory::getInstance($filters->core->selectedCategoryId)->getTitle()?>
					<span class="text-muted"><?php echo ' ('.$count.' '.(($count > 1)?JText::_("COM_PAYCART_ITEMS"):JText::_("COM_PAYCART_ITEM")).')';?></span>
				</h3>
			<?php endif;?>
		</div>
	</div>
	<hr>
<!-- =============================================
       2. Top section :  Applied filters 
     ============================================= -->
    <?php if(!(empty($filters->core->appliedPriceRange) && empty($filters->core->appliedWeightRange) &&
    		   empty($filters->core->appliedInStock) && empty($filters->attribute->appliedAttr) )):?>
    	<div class="row">
			<div class="col-sm-12">
    			<?php echo $this->loadTemplate('applied_filter',compact('filters'));?>
    		</div>
		</div>
		<hr>
	<?php endif;?>
	
<!-- =============================================
       3. Search result content 
     ============================================= -->
	<div class="row">
		<!-- =============================================
       		      3.1 Left section : Product filters 
             ============================================= -->
        <div class="col-md-3 col-sm-4 pc-product-filter">
	        <?php 
			 	ob_start();
			?>
		 	<!-- category filters -->
		 	<?php echo $this->loadTemplate('category_filter',compact('filters','searchWord'));?>
			<?php if($showFilters):?>	
				<hr>
				<!-- custom attribute filterHtml -->
				<?php foreach ($filters->attribute->filterHtml as $id=>$filter):?>
					<div class="panel-group" id="accordion-id-<?php echo $id?>" role="tablist" aria-multiselectable="true">
					 	<div class="panel-default">
					 		<div role="tab" id="accordion-heading-<?php echo $id?>">
					 			<h3>
						 			<a data-toggle="collapse" data-parent="#accordion-id-<?php echo $id?>" 
						 			   href="#accordion-body-id-<?php echo $id?>" aria-expanded="true" 
						 			   aria-controls="accordion-body-id-<?php echo $id?>" class="accordion-toggle">		 				
						 				<span>&nbsp;<?php echo $filter['name']; ?></span>
						 			</a>
					 			</h3>		
					 		</div>
					 		<!-- use class "in" for keeping it open -->
					 		<div class="pc-product-filter-body">
						 		 <div id="accordion-body-id-<?php echo $id?>" class="accordion-body panel-collapse collapse in" role="tabpanel" aria-labelledby="accordion-heading-<?php echo $id?>">
						 		 	<div class="accordion-inner panel-body">
						 		 		
						 		 		<?php if(in_array($id,$appliedAttrIds )):?>
							 		 			<span class="badge label-default pull-right pc-cursor-pointer" data-pc-selector="remove" data-pc-filter-name="filters[attribute][<?php echo $id?>]"><?php echo Jtext::_('COM_PAYCART_RESET')?></span>
							 		 			<br>
						 		 		<?php endif;?>
						 		 		<?php echo $filter['html'];?>
						 		 	</div>
						 		 </div>
						 	</div>
					 	 </div>
					</div>
					<hr>
				<?php endforeach;?>
				
				<!-- range filters -->
				<?php echo $this->loadTemplate('range_filter',compact('filters','wieightUnit','currency'));?>
				
				<!-- exclude out-of-stock -->
				<div class="panel-group accordion" id="accordion-id-stock" role="tablist" aria-multiselectable="true">
				 	<div class="accordion-group panel panel-default">
				 		<div role="tab" class="accordion-heading" id="accordion-heading-stock">
				 			<h3>
					 			<a data-toggle="collapse" data-parent="#accordion-id-stock" 
					 			   href="#accordion-body-id-stock" aria-expanded="true" 
					 			   aria-controls="accordion-body-id-stock" class="accordion-toggle">		 				
					 					<span>&nbsp;<?php echo JText::_("COM_PAYCART_AVAILABILITY")?></span>
					 			</a>
				 			</h3>		
				 		</div>
				 		<!-- use class "in" for keeping it open -->
				 		<div class="pc-product-filter-body">
					 		 <div class="accordion-body panel-collapse collapse in" id="accordion-body-id-stock">
					 		 	<div class="accordion-inner panel-body" role="tabpanel" aria-labelledby="accordion-heading-stock">
									<div class="checkbox">
					 		 			<label class="help-block"><input type="checkbox" name="filters[core][in_stock]" value="In-Stock" data-pc-result="filter"
					       					<?php echo (!empty($filters->core->appliedInStock))?'checked=checked':'';?>/>
					       					<span><?php echo JText::_("COM_PAYCART_FILTER_EXCULDE_OUT_OF_STOCK");?></span>
										</label>
									</div>
					 		 	</div>
					 		 </div>
					 	</div>
				 	 </div>
				</div>
				
				<input type="hidden" name="filters[sort]" data-pc-filter="sort-destination" value="<?php echo $appliedSort;?>" />
				<input type="hidden" name="query" value="<?php echo $searchWord?>"/>
				<input type="hidden" name="pagination_start" value="<?php echo $start;?>"/>
			<?php endif;?>
			 <?php 
				 $filterHtml = ob_get_contents();
				 ob_get_clean();
			 ?>
			 
			<form class="pc-form-product-filter hidden-xs" data-pc-filter-form="desktop" method="post">
				<?php echo $filterHtml?>
			</form>	 	
		 	<div id="pc-mob-offcanvas" >
				<div class="sidebar-offcanvas" role="navigation" id="offcanvas-filter">
					<div class="sidebar-offcanvas-inner">
			 			<div class="pc-fixed-top">
			 				<table class="table">
			              		<thead>
			                	<tr>
			 						<th><a class="pc-cursor-pointer" data-toggle="offcanvas-filter" data-target="#offcanvas-filter"><?php echo JString::strtoupper(JText::_('COM_PAYCART_BACK'))?></a></th>
			                  		<th><?php echo JString::strtoupper(JText::_('COM_PAYCART_FILTER'));?></th>
			                  		<th><a class="pc-cursor-pointer" data-toggle="offcanvas-filter" data-target="#offcanvas-filter" data-pc-selector="removeAll"><?php echo JString::strtoupper(JText::_("COM_PAYCART_FILTER_RESET_ALL"));?></a></th>
			                	</tr>
			              		</thead>
			            	</table>
						</div>
						<form class="pc-form-product-filter" data-pc-filter-form="mobile" method="post">
							<?php echo $filterHtml?>
						</form>
						<hr>
						<div class="pc-fixed-bottom">
							<div class="pc-filter-apply-btn">
							 	<a class="btn btn-large btn-block btn-primary" type="button" data-pc-selector="applyFilters" data-toggle="offcanvas-filter" data-target="#offcanvas-filter">APPLY</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- =====================================================
       		     3.2 Right section : search result product list 
             ===================================================== -->
		<?php if(!empty($records)):?>
			<div class="col-md-9 col-sm-8 clearfix">
				<div class="clearfix">
					<div class="pull-left visible-xs-block form-group">
						<label class="control-label"><?php echo JText::_("COM_PAYCART_SEARCH_REFINE")?></label>
			  			<button class="btn btn-default form-control" data-toggle="offcanvas-filter" data-target="#offcanvas-filter"><i class="fa fa-filter fa-lg"></i> <?php echo JText::_('COM_PAYCART_FILTER');?></button>
	  				</div>
	  				
					<div class="pull-right form-group">
						<label class="control-label"><?php echo JText::_("COM_PAYCART_SEARCH_RESULT_SORT_BY")?></label>
						<?php echo PaycartHtml::_('select.genericlist',$sortingOptions, 'filter_sort', 'data-pc-selector="sortOption" data-pc-filter="sort-source" class="input-medium form-control"','','',$appliedSort);?>
					</div>						
				</div>
				 
				<?php $data           = new stdclass();?>
				<?php $data->products = $products;?>
				<?php $data->pagination_start = $start;?>
				<div class='pc-products-wrapper row clearfix'>
					<div id="pc-products" class ='pc-products' data-columns>
						<?php echo JLayoutHelper::render('paycart_product_list', $data);?>
					</div>
				</div>
				<?php if($count > $start):?>
					<div class="text-center pc-loadMore">
						<button class="btn btn-lg" data-pc-loadMore="click">
							<?php echo JText::_("COM_PAYCART_FILTER_SHOW_MORE_PRODUCTS")?>
						</button>
					</div>
				<?php endif;?>
			</div>
			
		<?php else:?>
			<div class="col-md-9 col-sm-8 clearfix"> 
				<div class="visible-xs-block pc-refine-filter-mobile">
					<span><small><?php echo JText::_("COM_PAYCART_SEARCH_REFINE")?>:</small></span>
		  			<button class="btn btn-default" data-toggle="offcanvas-filter" data-target="#offcanvas-filter" type="button"><i class="fa fa-filter fa-lg"></i> Filter</button>
  				</div>
				<div class="text-muted text-center well"><h3><?php echo JText::_("COM_PAYCART_FILTER_NO_MATCHING_RECORD");?></h3></div>
			</div>
		<?php endif;?>
	</div>
<?php 