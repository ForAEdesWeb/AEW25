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
  
  <div class="uber-stats style-2 <?php echo $helper->get('acm-style'); ?> <?php if($fullWidth): ?>full-width <?php endif; ?>">
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
      <?php $count=$helper->getRows('data-style-2.stats-count'); ?>
      <?php for ($i=0; $i<$count; $i++) : ?>
      <?php if ($helper->get ('data-style-2.stats-count', $i)) : ?>
      <?php 
      	$colNumber = 2;
  			if($count<12 && (12%$count==0)) {
  				$colNumber = $count;
  			} elseif(12%$count!=0) {
  				$colNumber = $count-1;
  			}
  		?>
      <li class="col-md-<?php echo (12/$colNumber) ?> col-sm-6 stats-asset" <?php if(12%$count!=0 && $i==($count-1)): ?>style="margin-top: 40px;"<?php endif; ?> >
      <span class="stats-item-icon" <?php if($helper->get ('data-style-2.stats-color', $i)): ?> style="color: <?php echo $helper->get ('data-style-2.stats-color', $i) ?>;" <?php endif; ?>>
      	<i class="fa <?php echo $helper->get ('data-style-2.font-icon', $i) ?>"></i>
      </span>
      
      <span class="stats-item-counter" data-to="<?php echo $helper->get ('data-style-2.stats-count', $i) ?>" data-from="0" data-speed="2000" data-refresh-interval="20">
  			<?php echo $helper->get ('data-style-2.stats-count', $i) ?>
  		</span>
  		
      <?php if ($helper->get ('data-style-2.stats-name', $i)) : ?>
        <span class="stats-subject"><?php echo $helper->get ('data-style-2.stats-name', $i) ?></span>
      <?php endif; ?>
      </li>
      <?php endif; ?>
    <?php endfor;?>
    </ul>
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  </div>
</div>