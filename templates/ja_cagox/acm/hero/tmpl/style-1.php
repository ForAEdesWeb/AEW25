<?php 
  $style					= $helper->get('acm-style');
  $blockTitle     = $helper->get('block-title');
  $blockIntro     = $helper->get('block-intro');
  $btnFirstText   = $helper->get('hero-btn1-text');
  $btnFirstLink   = $helper->get('hero-btn1-link');
  $btnFirstClass  = $helper->get('hero-btn1-class');
  $btnSecondText  = $helper->get('hero-btn2-text');
  $btnSecondLink  = $helper->get('hero-btn2-link');   
  $btnSecondClass = $helper->get('hero-btn2-class');
  $blockImg 			= $helper->get('block-bg');
	$slideScreen		= $helper->get('hero-screen');    
  $textAlign      = $helper->get('text-align');
  $contentPos     = $helper->get('content-position');
  
  if($contentPos=='position-right') {
    $contentPos = 'col-md-offset-6';
  } elseif ($contentPos=='position-center') {
    $contentPos = 'col-md-offset-3';
  } else {
    $contentPos = '';
  }

?>
<div class="block-hero full-screen <?php echo $helper->get('block-extra-class'); ?> <?php echo ($style .' '. $textAlign.' '. $slideScreen); ?>" data-style="<?php echo $style ?>">
  <?php if(trim($blockImg)): ?><div class="block-bg" style="background-image: url(<?php echo trim($blockImg); ?>);"></div><?php endif; ?>
  <div class="container">
    <div class="row">
      <div class="hero-content col-md-6 <?php echo $contentPos; ?>">
        <?php if($blockTitle): ?>
        <h2 class="block-title">
          <?php echo $blockTitle; ?>
        </h2>
  		  <?php endif; ?>

        <?php if($blockIntro): ?>
         <p class="block-intro"><?php echo $blockIntro; ?></p>
        <?php endif; ?>
      
        <?php if( trim($btnFirstText) || trim($btnSecondText) ) : ?>
        <div class="hero-btn-actions">
  			<?php if( trim($btnFirstText)): ?>
          <a href="<?php echo trim($btnFirstLink); ?>" title="<?php echo trim($btnFirstText); ?>" class="btn <?php echo trim($btnFirstClass); ?>"><?php echo trim($btnFirstText); ?></a>
  				<?php endif; ?>
  				
  				<?php if( trim($btnSecondLink)) :?>
          <a href="<?php echo trim($btnSecondLink); ?>" title="<?php echo trim($btnSecondText); ?>" class="btn <?php echo trim($btnSecondClass); ?>"><?php echo trim($btnSecondText); ?></a>
  				<?php endif; ?>	
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>