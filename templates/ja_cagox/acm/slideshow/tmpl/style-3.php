<?php
  $count            = $helper->count('block-title');
  $fullScreen       = $helper->get('full-screen');
  $enableControl    = $helper->get('enable-control');
  $enableIndicators = $helper->get('enable-indicators');
?>

<div class="style-1 block-slideshow carousel slide vertical <?php echo $fullScreen; ?>" data-ride="carousel" data-wrap="false" data-interval="false" id="block-slideshow-<?php echo $module->id; ?>">
  <div role="listbox" class="carousel-inner">
  <?php for ($i=0; $i<$count; $i++) : ?>
  <?php 
    $style					= $helper->get('acm-style',$i);
    $animation			= $helper->get('animation-style',$i);
    $blockTitle     = $helper->get('block-title',$i);
    $blockIntro     = $helper->get('block-intro',$i);
    $btnFirstText   = $helper->get('slideshow-btn1-text',$i);
    $btnFirstLink   = $helper->get('slideshow-btn1-link',$i);
    $btnFirstClass  = $helper->get('slideshow-btn1-class',$i);
    $btnSecondText  = $helper->get('slideshow-btn2-text',$i);
    $btnSecondLink  = $helper->get('slideshow-btn2-link',$i);   
    $btnSecondClass = $helper->get('slideshow-btn2-class',$i);
    $blockImg 			= $helper->get('block-bg',$i);
  	$slideScreen		= $helper->get('slideshow-screen',$i);    
    $textAlign      = $helper->get('text-align',$i);
    $contentPos     = $helper->get('content-position',$i);
    
    if($contentPos=='position-right') {
      $contentPos = 'col-md-offset-6';
    } elseif ($contentPos=='position-center') {
      $contentPos = 'col-md-offset-3';
    } else {
      $contentPos = '';
    }
  
  ?>
  <div class="block-slideshow-item item <?php echo $animation; ?> <?php if($i==0): echo 'active'; endif; ?> <?php echo $helper->get('block-extra-class'); ?> <?php echo ($style .' '. $textAlign.' '. $slideScreen); ?>">
    <?php if(trim($blockImg)): ?><div class="block-bg" style="background-image: url(<?php echo trim($blockImg); ?>);"></div><?php endif; ?>
    <div class="container">
      <div class="row">
        <div class="slideshow-content col-md-6 <?php echo $contentPos; ?>">
          <?php if($blockTitle): ?>
          <h2 class="block-title">
            <?php echo $blockTitle; ?>
          </h2>
    		  <?php endif; ?>

          <?php if($blockIntro): ?>
           <p class="block-intro"><?php echo $blockIntro; ?></p>
          <?php endif; ?>
        
          <?php if( trim($btnFirstText) || trim($btnSecondText) ) : ?>
          <div class="slideshow-btn-actions">
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
  <?php endfor ?>
  </div>
  
  <?php if($enableControl): ?>
    <a data-slide="prev" role="button" href="#block-slideshow-<?php echo $module->id; ?>" class="left carousel-control">
      <span></span>
    </a>
    <a data-slide="next" role="button" href="#block-slideshow-<?php echo $module->id; ?>" class="right carousel-control">
      <span></span>
    </a>
  <?php endif; ?>
  
  <?php if($enableIndicators): ?>
  <div class="indicators">
    <ol class="carousel-indicators">
      <?php for ($i=0; $i<$count; $i++) : ?>
      <li data-slide-to="<?php echo $i; ?>" data-target="#block-slideshow-<?php echo $module->id; ?>" class="<?php if($i==0): echo 'active'; endif; ?>"></li>
      <?php endfor ?>
    </ol>
  </div>
  <?php endif; ?>
</div>