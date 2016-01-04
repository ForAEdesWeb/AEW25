<?php
  $heroStyle      = $helper->get('hero-style');
  $heroTextPos    = $helper->get('hero-content-position');
  $heroTextAlign  = $helper->get('hero-text-align');
  $heroHeading    = $helper->get('hero-heading');
  $heroHeadingSize= $helper->get('hero-heading-size');
  $heroIntro      = $helper->get('hero-intro');
  $btnFirstText   = $helper->get('hero-btn1-text');
  $btnFirstLink   = $helper->get('hero-btn1-link');
  $btnFirstClass  = $helper->get('hero-btn1-class');
  $btnSecondText  = $helper->get('hero-btn2-text');
  $btnSecondLink  = $helper->get('hero-btn2-link');
  $heroBg         = $helper->get('hero-bg');
  $heroImg         = $helper->get('hero-img');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
  <div class="acm-hero style-5 <?php echo ($heroStyle .' '. $heroTextPos. ' '. $heroTextAlign); ?> <?php if( trim($heroHeading) ) echo ' show-intro'; ?>" style="background: url(<?php echo trim($heroBg); ?>) no-repeat center top;">
    <div class="container">
      <div class="hero-content <?php if( trim($heroImg)) : ?>row<?php endif; ?>">
      
      	<?php if( trim($heroImg)) : ?>
          <div class="col-sm-4">
            <div class="hero-img">
              <img src="<?php echo $heroImg; ?>" alt="" />
            </div>
          </div>
        <?php endif; ?>
      	
      	<?php if( trim($heroHeading) || trim($heroIntro) || trim($btnFirstText) || trim($btnSecondText) ) : ?>
  			<div class="<?php if( trim($heroImg)) : ?> col-sm-8 <?php endif; ?>">
  	      <?php if( trim($heroHeading)) : ?>
  	      <div class="hero-heading <?php echo $heroHeadingSize; ?>">
  	        <?php echo $heroHeading; ?>
  	      </div>
  	      <?php endif; ?>
  	      
  	      <?php if( trim($heroIntro)) : ?>
  	      <div class="hero-intro">
  	        <?php echo $heroIntro; ?>
  	      </div>
  	      <?php endif; ?>
  	      
  	      <?php if( trim($btnFirstText) || trim($btnSecondText) ) : ?>
  	      <div class="hero-btn-actions">
  	      	<?php if(trim($btnFirstText)): ?>
  	        <a href="<?php echo trim($btnFirstLink); ?>" title="<?php echo trim($btnFirstText); ?>" class="btn <?php echo trim($btnFirstClass); ?>"><?php echo trim($btnFirstText); ?><i class="fa fa-angle-right"></i></a>
  	        <?php endif; ?>
  	        <?php if(trim($btnSecondText)): ?>
  	        <a href="<?php echo trim($btnSecondLink); ?>" title="<?php echo trim($btnSecondText); ?>" class="btn"><?php echo trim($btnSecondText); ?><i class="fa fa-angle-right"></i></a>
  	        <?php endif; ?>
  	      </div>
  	      <?php endif; ?>
        </div>
        <?php endif; ?>
        
      </div>
  
    </div>
  </div>
</div>