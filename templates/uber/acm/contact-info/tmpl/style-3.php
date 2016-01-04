<?php 
	$fullWidth 							= $helper->get('full-width');
	$contacInfoMap 					= $helper->get('contact-info-googlemap');
	$contacInfoImage 				= $helper->get('contact-info-image');
	$contacInfoDescription1 = $helper->get('contact-info-description1');
	$contacInfoPosition 		= $helper->get('contact-info-position');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
		<?php if($helper->get('block-intro')): ?>
			<p class="container-sm section-intro hidden-xs"><?php echo $helper->get('block-intro'); ?></p>
		<?php endif; ?>	
	</h3>
	<?php endif; ?>
    
  <div id="uber-contact-<?php echo $module->id; ?>" class="uber-contact-info style-3 <?php if(!($contacInfoImage || $contacInfoMap)): ?> no-background <?php endif; ?> <?php if($fullWidth): ?>full-width <?php endif; ?>">
  	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
    
    <?php if($contacInfoImage || $contacInfoMap): ?>
    <div class="info-bg">
    	<?php echo $contacInfoMap; ?>
    	<?php if($contacInfoImage): ?><img src="<?php echo $contacInfoImage; ?>" alt="" /><?php endif; ?>
    </div>
    <?php endif; ?>
  	<div class="info row <?php echo $helper->get('contact-info-position'); ?>">
  		<div class="col-xs-12 col-md-6">
  			<h4><?php echo JText::_('ACM_GET_IN_TOUCH') ?></h4>
  			<dl class="info-list">
  			  <?php $count= $helper->getRows('contact-info-item.contact-info-name'); ?>
  			  
  			  <?php for ($i=0; $i<$count; $i++) : ?>
  			  
  				<dt>
  					<?php if($helper->get ('contact-info-item.contact-info-icon', $i)): ?><i class="fa <?php echo $helper->get ('contact-info-item.contact-info-icon', $i); ?>"></i><?php endif; ?>
  					<?php echo $helper->get ('contact-info-item.contact-info-name', $i) ?>
  				</dt>
  			
  		  	<?php if ($helper->get ('contact-info-item.contact-info-value', $i)) : ?>
  		    <dd><?php echo $helper->get ('contact-info-item.contact-info-value', $i) ?></dd>
  		  	<?php endif; ?>
  		  	
  				<?php endfor; ?>
  				
  			</dl>
  		</div>
  		<div class="col-xs-12 col-md-6">
  			<?php echo $contacInfoDescription1; ?>
  		</div>
  	</div>
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  </div>
</div>