<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Bar Params
$fullWidth 								= $helper->get('full-width');
$enableSticky 						= $helper->get('enable-sticky');
$enableClose							= $helper->get('enable-close');
$barMessage 							= $helper->get('bar-message');
$barBackground 						= $helper->get('bar-background');
?>


<!-- BAR -->
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">     
  <?php if($enableSticky): ?>
  <div id="hspace-<?php echo $module->id; ?>" class="hspace hspace-open"></div>
  <?php endif; ?>
  <div id="uber-bar-<?php echo $module->id; ?>" class="uber-bar bar-2 <?php echo $barBackground; ?> <?php if($enableSticky): echo 'affix'; endif; ?> <?php if($enableClose): echo 'bar-open'; endif; ?>">
  	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
  		<div class="col-xs-12 col-md-6 bar-center text-center">
  				<?php echo $barMessage; ?>
  		</div>
  	<?php if(!$fullWidth): ?></div><?php endif; ?>
  	<?php if($enableClose): ?>
  	<a class="btn btn-close" href="#"><i class="fa fa-remove"></i></a>
  	<?php endif; ?>
  </div>
</div>
<!-- //BAR -->
<?php if($enableSticky): ?>
<script>
(function ($) {
	$(document).ready(function(){
		if($('#uber-bar-<?php echo ($module->id) ?>').hasClass('affix') ) {
			var elmheight = $('#uber-bar-<?php echo ($module->id) ?>').outerHeight();
			$('#hspace-<?php echo ($module->id) ?>').css('height', elmheight);
			$(document.body).addClass('affixbar-show');
		}
	});
})(jQuery);
</script>
<?php endif; ?>

<script>
(function ($) {
	$(document).ready(function(){
		$('#uber-bar-<?php echo ($module->id) ?> .btn-close').on('click', function () {
			if($('#uber-bar-<?php echo ($module->id) ?>').hasClass('bar-open')) {
				$('#uber-bar-<?php echo ($module->id) ?>').removeClass('bar-open').addClass('bar-close');
				$('#hspace-<?php echo ($module->id) ?>').removeClass('hspace-open').addClass('hspace-close');
				$(document.body).removeClass('affixbar-show');
			} else {
				$('#uber-bar-<?php echo ($module->id) ?>').removeClass('bar-close').addClass('bar-open');
				$('#hspace-<?php echo ($module->id) ?>').removeClass('hspace-close').addClass('hspace-open');
			}
			return false;
		});
	});
})(jQuery);
</script>