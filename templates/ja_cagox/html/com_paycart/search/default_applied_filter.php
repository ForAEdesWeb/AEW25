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
defined('_JEXEC') or die();
  
/**
 * List of Populated Variables
 * $displayData : object of stdclass containing data to show applied filters
 */
$appliedAttr        = $filters->attribute->appliedAttr;
$attributeOptions   = $filters->attribute->appliedAttrDetail;
$appliedPriceRange  = $filters->core->appliedPriceRange;
$appliedWeightRange = $filters->core->appliedWeightRange;
$appliedInStock     = $filters->core->appliedInStock;
?>

<span class="text-muted"><i><?php echo JText::_('COM_PAYCART_FILTERED_BY').' : '?></i></span>

<!-- Custom attributes -->
<?php foreach ($appliedAttr as $id=>$data):?>
	<?php if(!empty($data)):?>
		<?php foreach ($data as $key=>$value):?>
			<span class="badge label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[attribute][<?php echo $id?>][<?php echo $value?>]">
				<?php echo $attributeOptions[$id][$value]->title ;?>&nbsp;&nbsp;<i class="fa fa-times-circle"></i>
			</span>&nbsp;
		<?php endforeach;?>
	<?php endif; ?>
<?php endforeach;?>

<!-- applied Price Range -->
<?php if(!empty($appliedPriceRange)):?>
    <?php $key   = key($appliedPriceRange);?>
    <?php $value = $appliedPriceRange[$key];?>
	<span class="badge label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[core][price]">
		<?php echo $value;?>&nbsp;&nbsp;<i class="fa fa-times-circle"></i>
	</span>&nbsp;
<?php endif;?>

<!-- applied Weight Range -->
<?php if(!empty($appliedWeightRange)):?>
	<?php $key   = key($appliedWeightRange);?>
    <?php $value = $appliedWeightRange[$key];?>
	<span class="badge label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[core][weight]">
		<?php echo $value;?>&nbsp;&nbsp;<i class="fa fa-times-circle"></i>
	</span>&nbsp;
<?php endif;?>

<!-- In stock -->
<?php if(!empty($appliedInStock)) :?>
	<?php $key   = key($appliedInStock);?>
    <?php $value = $appliedInStock[$key];?>
	<span class="badge label-default pc-cursor-pointer" data-pc-filter="remove" data-pc-filter-applied-ref="filters[core][<?php echo $key?>]">
		<?php echo $value;?>&nbsp;&nbsp;<i class="fa fa-times-circle"></i>
	</span>&nbsp;
<?php endif;?>


<span class="hidden-xs badge pull-right pc-cursor-pointer" data-pc-selector="removeAll">
	<?php echo JText::_("COM_PAYCART_FILTER_RESET_ALL")?>&nbsp;&nbsp;<i class="fa fa-times-circle"></i>
</span>

<?php 