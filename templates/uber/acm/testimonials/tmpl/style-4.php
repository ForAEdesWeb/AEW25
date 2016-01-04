<?php
  $count = $helper->getRows('data-special.testimonial-text');
  $textColor = $helper->get('text-color');
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
  
  <div class="acm-testimonials style-4 <?php if($fullWidth): ?>full-width <?php endif; ?>">
    <?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
  
      <!-- BEGIN: TESTIMONIALS STYLE 1 -->
    	<div id="acm-testimonials-<?php echo $module->id ?>" class="panel-container testimonial-content">
  
         
          <div class="testimonial-text" <?php if($textColor) : ?> style="color: <?php echo $textColor; ?>;" <?php endif; ?>>
            <?php for ($i=0; $i<$count; $i++) : ?>
            <div class="quote-item <?php if ($i == 0) echo 'active' ?>" data-ref="quote-<?php echo $i ?>">
              <p><?php echo $helper->get ('data-special.testimonial-text', $i) ?></p>
              <span <?php if($authorTextColor) : ?> style="color: <?php echo $authorTextColor; ?>;" <?php endif; ?>><?php echo $helper->get ('data-special.author-name', $i) ?></span>
            </div>
            <?php endfor ?>
          </div>
            
          <div class="author-avatars">
          <?php for ($i=0; $i<$count; $i++) : ?>
            <span class="author-img" data-ref="quote-<?php echo $i ?>">
              <img src="<?php echo $helper->get ('data-special.author-img', $i) ?>" title="<?php echo $helper->get ('data-special.author-name', $i) ?>" />
              <span class="mask"></span>
            </span>
          <?php endfor ?>
          </div>
  
      </div>
      <!-- END: TESTIMONIALS STYLE 1 -->
      
    <?php if(!$fullWidth): ?></div><?php endif; ?>
  
  </div>
</div>

<script type="text/javascript">

(function ($) {
  $(document).ready(function () {
    $('.author-img').each(function(i) {
      var image = $(this);
      
      image.hover(function() {
        var panel = $(this).parents('.panel-container:first');
        
        $('.quote-item', panel).removeClass('active');
        $('.quote-item[data-ref="' + image.attr('data-ref') + '"]', panel).addClass('active');
        
        $('.author-img', panel).removeClass('active');
        image.addClass('active');
      });
    });
  });
}) (jQuery);

</script>