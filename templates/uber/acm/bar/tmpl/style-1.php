<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$t3doc = T3::getApp();
$doc = JFactory::getDocument();

// Bar Params
$fullWidth 									= $helper->get('full-width');
$enableSticky 							= $helper->get('enable-sticky');
$enableClose								= $helper->get('enable-close');
$barLeftPosition 						= $helper->get('bar-left-position');
$barRightPosition 					= $helper->get('bar-right-position');
$barBackground 							= $helper->get('bar-background');
?>


<!-- BAR -->
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">
  <?php if($enableSticky): ?>
  <div id="hspace-<?php echo $module->id; ?>" class="hspace hspace-open"></div>
  <?php endif; ?>
  <div id="uber-bar-<?php echo $module->id; ?>" class="uber-bar bar-1 <?php echo $barBackground; ?> <?php if($enableSticky): echo 'affix'; endif; ?> <?php if($enableClose): echo 'bar-open'; endif; ?>">
  	<?php if(!$fullWidth): ?><div class="container">
  		<div class="row"><?php endif; ?>
  			<div class="col-xs-12 col-sm-8">
  				<div class="bar-left pull-left">
  					<?php if ($helper->countModules($barLeftPosition)): ?>
  						<?php echo $helper->renderModules ($barLeftPosition) ?>
  					<?php endif; ?>	
  				</div>
  			</div>
  			
  			<div class="col-xs-12 col-sm-4 pull-right">		
  				<?php if ($helper->countModules($barRightPosition)): ?>			
  				<div class="bar-right">				
  					<?php echo $helper->renderModules ($barRightPosition) ?>
  				</div>
  				<?php endif; ?>
  				
  				<?php if ( $helper->get('facebook') || $helper->get('google-plus') || $helper->get('twitter') || $helper->get('pinterest') || $helper->get('linkedin') ): ?>
  				<div class="uber-social pull-right">
  					<div class="addthis_toolbox">
  						<?php if($helper->get('facebook')): ?>
  							<a class="addthis_button_facebook_follow" addthis:userid="<?php echo $helper->get('facebook')?>"><i class="fa fa-facebook"></i></a>
  						<?php endif; ?>
  						
  						<?php if($helper->get('google-plus')): ?>
  						<a class="addthis_button_twitter_follow" addthis:userid="<?php echo $helper->get('google-plus')?>"><i class="fa fa-twitter"></i></a>
  						<?php endif; ?>
  						
  						<?php if($helper->get('twitter')): ?>
  						<a class="addthis_button_google_follow" addthis:userid="+<?php echo $helper->get('twitter')?>"><i class="fa fa-google-plus"></i></a>
  						<?php endif; ?>
  						
  						<?php if($helper->get('pinterest')): ?>
  						<a class="addthis_button_pinterest_follow" addthis:userid="+<?php echo $helper->get('pinterest')?>"><i class="fa fa-pinterest"></i></a>
  						<?php endif; ?>
  						
  						<?php if($helper->get('linkedin')): ?>
  						<a class="addthis_button_linkedin_follow" addthis:usertype="company" addthis:userid="<?php echo $helper->get('linkedin')?>"><i class="fa fa-linkedin"></i></a>
  						<?php endif; ?>
  					</div>
  					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52c4eb2a034cad83"></script>
  					<!-- AddThis Follow END -->
  				</div>
  				<?php endif; ?>	
  			</div>
  		<?php if(!$fullWidth): ?></div>
  	</div><?php endif; ?>
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