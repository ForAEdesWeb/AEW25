<?php 
	$fullWidth = $helper->get('full-width');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($helper->get('block-bg')) : ?>style="background-image: url("<?php echo $helper->get('block-bg'); ?>")"<?php endif; ?> >
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
  
  <div class="uber-stats has-parallax style-6 <?php echo $helper->get('acm-style'); ?> <?php if($fullWidth): ?>full-width <?php endif; ?>">
  	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
  	<div class="row"><div class="col-md-6 col-xs-12 <?php echo $helper->get('acm-col-offset'); ?>">
  	  
  		<?php if ($helper->get ('stats-title') || $helper->get ('stats-description')) : ?>
  		<header class="stats-header">
  	    <?php if ($helper->get ('stats-title')) : ?>
  	       <h2 class="stats-title"><?php echo $helper->get ('stats-title') ?></h2>
  	    <?php endif; ?>
  	    <?php if ($helper->get ('stats-description')) : ?>
  	        <p class="stats-description"><?php echo $helper->get ('stats-description') ?></p>
  	    <?php endif; ?>
  	  </header>
  	  <?php endif; ?>
  	
  	  <ul class="stats-list">
  	    <?php $count=$helper->getRows('data.stats-count'); ?>
  	    <?php for ($i=0; $i<$count; $i++) : ?>
  	    <?php if ($helper->get ('data.stats-count', $i)) : ?>
  	    <li class="stats-asset">
  		   	<div>
  			  
  			    <?php if ($helper->get ('data.stats-name', $i)) : ?>
  			      <span class="stats-item-subject"><?php echo $helper->get ('data.stats-name', $i) ?></span>
  			    <?php endif; ?>
  			    
  			    <span class="stats-item-counter">
  			    	<span class="progressbar" style="width: <?php echo $helper->get ('data.stats-count', $i) ?>%">
  							<span <?php if($helper->get ('data.stats-color', $i)): ?> style="background-color: <?php echo $helper->get ('data.stats-color', $i) ?>;" <?php endif; ?>></span>
  						</span>
  					</span>
  					
  		  </div>
  	    </li>
  	    <?php endif; ?>
  	  <?php endfor;?>
  	  </ul>
    </div></div>
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  </div>
</div>