<?php
  $heroStyle      = $helper->get('hero-style');
  $heroTextPos    = $helper->get('hero-content-position');
  $heroTextAlign  = $helper->get('hero-text-align');
  $heroHeading    = $helper->get('hero-heading');
  $heroIntro      = $helper->get('hero-intro');
  $btnFirstText   = $helper->get('hero-btn1-text');
  $btnFirstLink   = $helper->get('hero-btn1-link');
  $heroBg         = $helper->get('hero-bg');
	$heroScreen			= $helper->get('hero-screen');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
  <div class="acm-hero style-6 <?php echo ($heroStyle .' '. $heroTextPos. ' '. $heroTextAlign.' '. $heroScreen); ?> <?php if( trim($heroHeading) ) echo ' show-intro'; ?>" style="background-image: url(<?php echo trim($heroBg); ?>);">
    <div class="container">
      <div class="hero-content<?php echo $helper->get('hero-effect'); ?>">
      
        <?php if( trim($heroHeading)) : ?>
        <div class="hero-heading">
          <?php echo $heroHeading; ?>
        </div>
        <?php endif; ?>
        
        <?php if( trim($heroIntro)) : ?>
        <div class="hero-intro">
          <?php echo $heroIntro; ?>
        </div>
        <?php endif; ?>
        
        <?php if( trim($btnFirstText) ) : ?>
        <div class="hero-btn-actions">
          <a href="<?php echo trim($btnFirstLink); ?>" title="<?php echo trim($btnFirstText); ?>" class="btn btn-halloween"><?php echo trim($btnFirstText); ?></a>
        </div>
        <?php endif; ?>
      </div>
  
    </div>
  </div>
</div>