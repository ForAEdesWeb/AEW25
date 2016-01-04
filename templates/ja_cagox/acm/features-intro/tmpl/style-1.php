<?php
  $align            = $helper->get('align');
  $featuresLink     = $helper->get('features-link');
  $featuresLinkText = $helper->get('features-link-text');
  
  if ($align==1): 
  	$contentAlign = "content-right";
  	$featuresContentPull = "col-sm-12 col-xs-12 col-md-12 pull-right";
  	$featuresImgPull = " hidden-sm hidden-xs col-md-6 pull-left";

    if($helper->get('img-features')) :
      $featuresContentPull = "col-sm-12 col-xs-12 col-md-6 pull-right";
    endif;
  elseif ($align==0): 
  	$contentAlign = "content-left";
  	$featuresContentPull = "col-sm-12 col-xs-12 col-md-12 pull-left";
  	$featuresImgPull = " hidden-sm hidden-xs col-md-6 pull-right";

    if($helper->get('img-features')) :
      $featuresContentPull = "col-sm-12 col-xs-12 col-md-6 pull-left";
    endif;
	else: 
		$contentAlign = "container";
  	$featuresContentPull = "";
  	$featuresImgPull = ""; 
  endif;
?>


<div id="acm-features-<?php echo $module->id; ?>" class="acm-features style-1 row <?php echo $helper->get('features-style'); ?>">
	<div class="<?php echo $contentAlign; ?>">
		<?php if($helper->get('img-features')) : ?>
		<div class="features-image <?php echo $featuresImgPull; ?>">
			<img src="<?php echo $helper->get('img-features'); ?>" />
		</div>
		<?php endif; ?>
		
		<div class="features-content text-center <?php echo $featuresContentPull; ?>">
			<!--- Features Content -->
			<?php if($module->showtitle || $helper->get('features-intro')) : ?>
				<div class="row ft-top">
					<div class="col-sm-12 <?php if($helper->get('img-features')) : ?> col-md-6 col-md-offset-3 <?php endif; ?>">
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
				</div>
			<?php endif ; ?>
		</div>
	</div>
</div>

<script>
(function($){
  $(document).ready(function(){ 
    if($('#acm-features-<?php echo $module->id; ?> .features-content').length > 0) { 
      var heightscreen = $('#acm-features-<?php echo $module->id; ?>').outerHeight(),
          pdcenter     = (heightscreen - $('#acm-features-<?php echo $module->id; ?> .features-content').height())/2;

      $('#acm-features-<?php echo $module->id; ?> .features-content').css('padding-top',pdcenter);
      
      $(window).resize(function(){
        var heightscreen = $('#acm-features-<?php echo $module->id; ?>').outerHeight(),
          pdcenter     = (heightscreen - $('#acm-features-<?php echo $module->id; ?> .features-content').height())/2;

        $('#acm-features-<?php echo $module->id; ?> .features-content').css('padding-top',pdcenter);

      });
    }

  });
})(jQuery);
</script>