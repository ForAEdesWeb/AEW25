<?php
  $align            = $helper->get('image-position');
  $featuresLink     = $helper->get('features-link');
  $featuresLinkText = $helper->get('features-link-text');
?>

<div id="acm-features-<?php echo $module->id; ?>" class="acm-features style-2">
	<?php if($helper->get('img-features') && $align=='top') : ?>
	<div class="features-image top">
		<img src="<?php echo $helper->get('img-features'); ?>" />
	</div>
	<?php endif; ?>
	
	<div class="features-content text-center">
		<!--- Features Content -->
		<?php if($module->showtitle || $helper->get('features-intro')) : ?>
			<div class="ft-top">
					<?php if($module->showtitle): ?>
						<h3><?php echo $module->title ?></h3>
					<?php endif; ?>
					<div class="intro">
						<?php echo $helper->get('features-intro'); ?>
					</div>
          <?php if($featuresLink): ?>
            <div class="readmore">
              <a href="<?php echo $featuresLink; ?>" title="<?php echo $featuresLinkText; ?>"><?php echo $featuresLinkText; ?></a>
            </div>
          <?php endif; ?>
			</div>
		<?php endif ; ?>
	</div>

  <?php if($helper->get('img-features') && $align=='bottom') : ?>
  <div class="features-image bottom">
    <img src="<?php echo $helper->get('img-features'); ?>" />
  </div>
  <?php endif; ?>
</div>

<script>
(function($){
  $(document).ready(function(){ 
    if($('#acm-features-<?php echo $module->id; ?> .features-content').length > 0) { 
      
      $('#acm-features-<?php echo $module->id; ?> .features-content').imagesLoaded(function(){
        var heightscreen = $('#acm-features-<?php echo $module->id; ?> .features-image').outerHeight();

        $('#acm-features-<?php echo $module->id; ?> .features-content').css('height',heightscreen);
      });
      
      $(window).resize(function(){
        var heightscreen = $('#acm-features-<?php echo $module->id; ?> .features-image').outerHeight();

        $('#acm-features-<?php echo $module->id; ?> .features-content').css('height',heightscreen);

      });
    }

  });
})(jQuery);
</script>