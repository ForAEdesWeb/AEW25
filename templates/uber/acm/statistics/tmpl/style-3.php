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
  
  <div class="uber-stats has-parallax style-3 <?php echo $helper->get('acm-style'); ?> <?php if($fullWidth): ?>full-width <?php endif; ?>">
  	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
    
    <?php if ($helper->get ('stats-title') || $helper->get ('stats-description')) : ?>
  	<header class="stats-header">
      <?php if ($helper->get ('stats-title')) : ?>
         <p class="small-head"><?php echo $helper->get ('stats-title') ?></p>
      <?php endif; ?>
      <?php if ($helper->get ('stats-description')) : ?>
          <h2 class="stats-title"><?php echo $helper->get ('stats-description') ?></h2>
      <?php endif; ?>
    </header>
    <?php endif; ?>
  
    <ul class="stats-list <?php if(!$fullWidth): ?>row<?php endif; ?>">
      <?php $count=$helper->getRows('data.stats-count'); ?>
      <?php for ($i=0; $i<$count; $i++) : ?>
      <?php if ($helper->get ('data.stats-count', $i)) : ?>
      <li class="col-xs-12 col-md-6 stats-asset">
  	   	<div>
  		    <span class="col-xs-8 pull-right stats-item-counter">
  		    	<span class="progressbar" style="<?php if($helper->get ('data.stats-color', $i)): ?> background-color: <?php echo $helper->get ('data.stats-color', $i) ?>; <?php endif; ?> width: <?php echo $helper->get ('data.stats-count', $i) ?>%">
  						<span><?php echo $helper->get ('data.stats-count', $i) ?>%</span>
  					</span>
  				</span>
  				
  		    <?php if ($helper->get ('data.stats-name', $i)) : ?>
  		      <span class="col-xs-4 pull-left stats-item-subject"><?php echo $helper->get ('data.stats-name', $i) ?></span>
  		    <?php endif; ?>
  	  </div>
      </li>
      <?php endif; ?>
    <?php endfor;?>
    </ul>
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  </div>
</div>