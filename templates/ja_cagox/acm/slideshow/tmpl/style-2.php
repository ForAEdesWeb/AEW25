<?php
  $count          = $helper->count('block-bg');
  $fullScreen     = $helper->get('full-screen');
  $style          = $helper->get('acm-style');
  $blockTitle     = $helper->get('block-title');
  $blockIntro     = $helper->get('block-intro');
  $btnFirstText   = $helper->get('slideshow-btn1-text');
  $btnFirstLink   = $helper->get('slideshow-btn1-link');
  $btnFirstClass  = $helper->get('slideshow-btn1-class');
  $btnSecondText  = $helper->get('slideshow-btn2-text');
  $btnSecondLink  = $helper->get('slideshow-btn2-link');   
  $btnSecondClass = $helper->get('slideshow-btn2-class');
  $slideScreen    = $helper->get('slideshow-screen');    
  $contentPos     = $helper->get('content-position');
  
  if($contentPos=='position-right') {
    $contentPos = 'col-md-offset-6';
  } elseif ($contentPos=='position-center') {
    $contentPos = 'col-md-offset-3';
  } else {
    $contentPos = '';
  }
?>

<div class="style-2 block-slideshow <?php echo $fullScreen; ?>" id="block-slideshow-<?php echo $module->id; ?>">
  <div id="block-slider-<?php echo $module->id; ?>">
      <?php for ($i=0; $i<$count; $i++) : 
        $blockImg = $helper->get('block-bg',$i); 
        if(trim($blockImg)): ?>
        <div><img src="<?php echo trim($blockImg); ?>" alt="" /></div>
        <?php endif; ?>
      <?php endfor ?>
  </div>
  
	<script type="text/javascript">
	  (function ($) {
		$(document).ready(function(){
		  $('#block-slider-<?php echo $module->id; ?>').slick({
			dots: true,
			infinite: false,
			speed: 300,
			slidesToShow: 2,
			slidesToScroll: 1,
      responsive: [
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
          // You can unslick at a given breakpoint now by adding:
          // settings: "unslick"
          // instead of a settings object
        ]
		  });
		});
	  })(jQuery);
	</script>
  
  <div class="slideshow-content col-sm-12 col-md-6 col-lg-4 <?php echo $contentPos; ?> <?php echo $style; ?>">
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