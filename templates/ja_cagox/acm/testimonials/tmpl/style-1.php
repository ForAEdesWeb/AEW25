<?php
  $count = $helper->getRows('data.testimonial-text');
  $textColor = $helper->get('text-color');
  $authorTextColor = $helper->get('author-info-color');
  $style          = $helper->get('acm-style');
  $numberColumns  = $helper->get('columns');
?>

<div class="section-inner <?php echo $style; ?>" data-style="<?php echo $style ?>">
	<?php if($module->showtitle): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
	</h3>
	<?php endif; ?>
  
  <div class="acm-testimonials">
    <div class="container">
  
      <!-- BEGIN: TESTIMONIALS STYLE 1 -->
    	<div id="acm-testimonials-<?php echo $module->id ?>" class="testimonial-content">
         <?php for ($i=0; $i<$count; $i++) : 
          if ($i%$numberColumns==0) echo '<div class="row">'; ?>
          <div class="item col-md-<?php echo 12/$numberColumns; ?>">
            <?php if ($helper->get ('data.author-avatar', $i)) : ?>
               <img class="author-avatar" src="<?php echo $helper->get ('data.author-avatar', $i) ?>" />
            <?php endif; ?>

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
          <?php if ( ($i%$numberColumns==($numberColumns-1)) || $i==($count-1) )  echo '</div>'; ?>
         <?php endfor ?>
        </div>
  			
      <!-- END: TESTIMONIALS STYLE 1 -->
      
    </div>
  
  </div>
</div>