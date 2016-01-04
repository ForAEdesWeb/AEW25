<?php
  $ctaBgColor       = $helper->get('cta-bg');
  $ctaIcon          = $helper->get('cta-icon');
  $ctaLink          = $helper->get('link');
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
	<div class="acm-cta style-4 <?php echo $ctaBgColor; ?>">
	  <div class="container">
			<div class="row">
				<a href="<?php echo $ctaLink; ?>" title="<?php echo $module->title ?>"><?php echo $module->title ?><i class="<?php echo $ctaIcon; ?>"></i></a>
			</div>
	  </div>
	</div>
</div>