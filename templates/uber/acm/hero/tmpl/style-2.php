<?php
  $heroStyle      = $helper->get('hero-style');
  $heroTextPos    = $helper->get('hero-content-position');
  $heroTextAlign  = $helper->get('hero-text-align');
  $heroHeading    = $helper->get('hero-heading');
  $heroIntro      = $helper->get('hero-intro');
	$heroQuote      = $helper->get('hero-quote');
  $heroBg         = $helper->get('hero-bg');
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
  <div class="acm-hero style-2 <?php echo ($heroStyle .' '. $heroTextPos. ' '. $heroTextAlign); ?> <?php if( trim($heroHeading) ) echo ' show-intro'; ?>" style="background-image: url(<?php echo $heroBg; ?>)">
  	<div class="hero-content">
  	
  		<div class="hero-description">
  			<?php if( trim($heroQuote )) : ?>
  			<div class="hero-quote">
  				<?php echo $heroQuote ; ?>
  			</div>
  			<?php endif; ?>
  			
  			<?php if( trim($heroHeading)) : ?>
  			<div class="hero-index">
  				<?php echo $heroHeading; ?>
  			</div>
  			<?php endif; ?>
  			
  			<?php if( trim($heroIntro)) : ?>
  			<div class="hero-des">
  				<?php echo $heroIntro; ?>
  			</div>
  			<?php endif; ?>
  		</div>
  	</div>
  </div>
</div>
