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
  
  <div class="uber-stats style-7 <?php echo $helper->get('acm-style'); ?> <?php if($fullWidth): ?>full-width <?php endif; ?>">
  	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
    
  	<?php if ($helper->get ('stats-title') || $helper->get ('stats-description')) : ?>
  	<header class="stats-header text-center">
      <?php if ($helper->get ('stats-title')) : ?>
         <p class="small-head"><?php echo $helper->get ('stats-title') ?></p>
      <?php endif; ?>
      <?php if ($helper->get ('stats-description')) : ?>
          <h2 class="stats-title"><?php echo $helper->get ('stats-description') ?></h2>
      <?php endif; ?>
    </header>
    <?php endif; ?>
  
    <ul class="stats-list <?php if(!$fullWidth): ?>row<?php endif; ?>">
      <?php $count=$helper->getRows('data-style-7.stats-count'); ?>
      <?php for ($i=0; $i<$count; $i++) : ?>
      <?php if ($helper->get ('data-style-7.stats-count', $i)) : ?>
      
      <?php 
      	$colNumber = 2;
  			if($count<12 && (12%$count==0)) {
  				$colNumber = $count;
  			} 
  			$statsCount = $helper->get ('data-style-7.stats-count', $i);
  			$progressStyle = "";
  			$deg = 360*$statsCount/100;
  			$progressRotate = "transform: rotate(".$deg."deg); -webkit-transform: rotate(".$deg."deg);";
  			
  			$statsColor = $helper->get ('data-style-7.stats-color', $i);
  			$statsBg = $helper->get ('data-style-7.stats-bg', $i);
  			$statsShadow = $helper->get ('data-style-7.stats-shadow', $i);
  			
  			if ($statsCount > 50): 
  				$progressChartStyle =  "background-color:".$statsColor."";
  				$progressFillStyle =  "background-color:".$statsShadow."";
  			else: 
  				$progressChartStyle =  "background-color:".$statsShadow."";
  				$progressFillStyle =  "background-color:".$statsColor."";
  			endif;
  			
  		?>
      <li class="col-md-<?php echo (12/$colNumber) ?> col-sm-6 stats-asset">
      
      <div class="progress-pie-chart <?php if ($statsCount > 50): ?> gt-50 <?php endif; ?>" style="<?php echo $progressChartStyle; ?>">
  		  <div class="ppc-progress">
  		    <div class="ppc-progress-fill" style="<?php echo $progressRotate; echo $progressFillStyle; ?>"></div>
  		  </div>
  		  <div class="ppc-percents" style="background-color: <?php echo $statsBg; ?>">
  		    <div class="pcc-percents-wrapper">
  		      <span <?php if($statsColor): ?> style="color: <?php echo $statsColor; ?>;" <?php endif; ?> ><?php echo $statsCount ?></span>
  		    </div>
  		  </div>
  		</div>
  		
      <?php if ($helper->get ('data-style-7.stats-name', $i)) : ?>
        <span class="stats-subject"><?php echo $helper->get ('data-style-7.stats-name', $i) ?></span>
      <?php endif; ?>
      </li>
      <?php endif; ?>
    <?php endfor;?>
    </ul>
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  </div>
</div>