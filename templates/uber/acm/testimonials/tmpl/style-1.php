<?php
  $count = $helper->getRows('data.testimonial-text');
  $textColor = $helper->get('text-color');
  $authorTextColor = $helper->get('author-info-color');
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
  
  <div class="acm-testimonials default <?php if($fullWidth): ?>full-width <?php endif; ?>">
    <?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
  
      <!-- BEGIN: TESTIMONIALS STYLE 1 -->
    	<div id="acm-testimonials-<?php echo $module->id ?>" class="testimonial-content carousel slide" data-ride="carousel" data-interval="false">
  
        <div class="carousel-inner">
         <?php for ($i=0; $i<$count; $i++) : ?>
          <div class="item <?php if($i<1) echo "active"; ?>">
          <i class="fa fa-quote-left fa-big-section" <?php if($textColor) : ?>style="color: <?php echo $textColor; ?>;"<?php endif; ?>></i>
          
          <?php if ($helper->get ('data.testimonial-text', $i)) : ?>
             <p class="testimonial-text" <?php if($textColor) : ?> style="color: <?php echo $textColor; ?>;" <?php endif; ?>><?php echo $helper->get ('data.testimonial-text', $i) ?></p>
          <?php endif; ?>
  
          <div class="author-info" style="color: <?php echo $authorTextColor; ?>;">
            <?php if ($helper->get ('data.author-img', $i)) : ?>
              <span class="author-image"><img src="<?php echo $helper->get ('data.author-img', $i) ?>" alt="Author Avatar" /></span>
            <?php endif; ?>
  
            <?php if ( ($helper->get ('data.author-name', $i)) || ($helper->get ('data.author-title', $i)) ) : ?>
              <div class="author-info-text">
                <span class="author-name"><?php echo $helper->get ('data.author-name', $i) ?> </span>
                
                <?php if ($helper->get ('data.author-title', $i)) : ?>
                  <span class="author-title"><?php echo $helper->get ('data.author-title', $i) ?></span>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
  
          </div>
         <?php endfor ?>
        </div>
  			
  			<!-- Controls -->
  			<?php if($helper->get('enable-controls')): ?>
  			<a data-slide="prev" role="button" href="#acm-testimonials-<?php echo $module->id ?>" class="left carousel-control"><i class="fa fa-angle-left"></i></a>
  			<a data-slide="next" role="button" href="#acm-testimonials-<?php echo $module->id ?>" class="right carousel-control"><i class="fa fa-angle-right"></i></a>
  			<?php endif; ?>
  
      </div>
      <!-- END: TESTIMONIALS STYLE 1 -->
      
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  
  </div>
</div>