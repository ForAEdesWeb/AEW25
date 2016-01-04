<?php
$pricingStyle = $helper->get('pricing-style');
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
  
  <div class="acm-pricing <?php echo $pricingStyle; ?>">
  	<div class="container pricing-table style-3">
  		<div class="row row-header hidden-xs hidden-sm">
  			<?php
  			$colols_count = $helper->getCols('data');
  			$features_count = $helper->getRows('data');
  			if (!$colols_count || !$features_count) {
  				$colols_count = $helper->count('pricing-col-name');
  				$features_count = $helper->count('pricing-row-name');
  			}
  			?>
  			<div class="col col-md-<?php echo 12 / ($colols_count + 1); ?> no-padding"></div>
  			<?php for ($i = 0; $i < $colols_count; $i++) : ?>
  				<div
  					class="col col-md-<?php echo 12 / ($colols_count + 1); ?><?php if ($helper->get('data.pricing-col-featured', $i)): ?> col-featured <?php endif ?> no-padding">
  					<div class="col-header text-center">
  						<h2><?php echo $helper->get('data.pricing-col-name', $i) ?></h2>
  
  						<p><span class="big-number"><?php echo $helper->get('data.pricing-col-price', $i) ?></span></p>
  					</div>
  				</div>
  			<?php endfor; ?>
  		</div>
  
  		<div class="row row-body">
  			<div class="col col-md-3 no-padding hidden-xs hidden-sm">
  				<ul>
  					<?php for ($row = 0; $row < $features_count; $row++) :
  						$feature = $helper->getCell('data', $row, 0);
  						if (!$feature) $feature = $helper->get('data.pricing-row-name', $row);
  						?>
  						<li class="row<?php echo($row % 2); ?> yes"><?php echo $feature; ?></li>
  					<?php endfor; ?>
  				</ul>
  			</div>
  
  			<?php for ($col = 0; $col < $colols_count; $col++) : ?>
  				
  				<div class="col col-md-3 no-padding">
  					<div class="col-header text-center hidden-md hidden-lg">
  						<h2><?php echo $helper->get('data.pricing-col-name', $col) ?></h2>
  	
  						<p><span class="big-number"><?php echo $helper->get('data.pricing-col-price', $col) ?></span></p>
  					</div>
  					<ul>
  						<?php for ($row = 0; $row < $features_count; $row++) :
  							$feature = $helper->getCell('data', $row, 0);
  							$value = $helper->getCell('data', $row, $col + 1);
  							$type = $value[0];
  							if (!$feature) {
  								$feature = $helper->get('pricing-row-name', $row);
  								$tmp = $helper->get('pricing-row-supportfor', $row);
  								$value = ($tmp & pow(2, $col)) ? 'b1' : 'b0'; // b1: yes, b0: no
  								$type = 'b'; // boolean
  							}
  							?>
  						<?php if ($type == 't'): ?>
  							<li class="row<?php echo($row % 2); ?>"><?php echo substr($value, 1) ?></li>
  						<?php elseif ($value == 'b1'): ?>
  							<li class="row<?php echo($row % 2); ?>"><span class="hidden-md hidden-lg"><?php echo $feature; ?></span> <i class="fa fa-check-circle"></i></li>
  						<?php
  						else: ?>
  							<li class="row<?php echo($row % 2); ?> no"><span class="hidden-md hidden-lg"><?php echo $feature; ?></span> <i class="fa fa-times-circle"></i></li>
  						<?php endif ?>
  
  						<?php endfor ?>
  					</ul>
  					<div class="col-footer text-center">
  						<a
  							class="btn btn-lg<?php if ($helper->get('data.pricing-col-featured', $col)): ?> btn-success <?php else: ?> btn-default <?php endif ?>"
  							title="<?php echo $helper->get('data.pricing-col-button', $col); ?>"
  							href="<?php echo $helper->get('data.pricing-col-buttonlink', $col); ?>"><?php echo $helper->get('data.pricing-col-button', $col); ?></a>
  					</div>
  				</div>
  			<?php endfor ?>
  
  		</div>
  
  	</div>
  </div>
</div>