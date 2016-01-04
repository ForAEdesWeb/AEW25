<?php
  $count = $helper->getRows('data-carousel.carousel');
  $authorTextColor = $helper->get('author-info-color');
  $authorName = $helper->get('author-name');
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
  
  <div class="acm-testimonials style-6">
  	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
  		<?php echo $helper->get('text-1') ?>
  		<div class="word-wrap">
  			<div id="intro-home" class="carousel slide" data-ride="carousel">
  				<div class="carousel-inner">
  				<?php for ($i=0; $i<$count; $i++) :  $textColor = $helper->get('data-carousel.color'); ?>
  					<div class="item <?php if($i<1) echo "active"; ?>">
  						<p <?php if($textColor) : ?> style="color: <?php echo $helper->get('data-carousel.color', $i); ?>;" <?php endif; ?>><?php echo $helper->get('data-carousel.carousel', $i) ?></p>
  					</div>
  				<?php endfor ?>
  				</div>
  			</div>
  		</div>
    <?php if(!$fullWidth) : ?></div><?php endif; ?>
  </div>
</div>