<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$t3doc = T3::getApp();
$doc = JFactory::getDocument();
$sitename  = $t3doc->params->get('sitename');

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}

// Footer Params
$fullWidth 									= $helper->get('full-width');
$footerBackground						= $helper->get('footer-background');
$footerImg									= $helper->get('img');
$footerBackgroundStyle  		= '';

if ($footerImg) {
	$footerBackgroundStyle  	= 'background-image: url("'.$footerImg.'"); background-repeat: no-repeat; background-size: cover;';
}

$enableBreadcrumbs 						= $helper->get('enable-breadcrumbs');
$enableBackToTop 							= $helper->get('enable-backtotop');
$footerLeftPosition 					= $helper->get('footer-left-position');
$footerRightPosition 					= $helper->get('footer-right-position');
?>

<?php if($enableBackToTop): ?>
<!-- BACK TOP TOP BUTTON -->
<div id="back-to-top-<?php echo $module->id; ?>" data-spy="affix" data-offset-top="300" class="back-to-top hidden-xs hidden-sm affix-top">
  <button class="btn btn-primary" title="Back to Top"><i class="fa fa-arrow-up"></i></button>
</div>
<script>
(function($) {
	// Back to top
	$('.back-to-top').on('click', function(){
		$("html, body").animate({scrollTop: 0}, 500);
		return false;
	});
})(jQuery);
</script>
<!-- BACK TO TOP BUTTON -->
<?php endif; ?>

<!-- FOOTER -->
<footer class="wrap uber-footer footer-2 <?php echo $footerBackground; ?>" <?php if($footerImg): ?> style="<?php echo $footerBackgroundStyle; ?>" <?php endif; ?>>
	<?php if($enableBreadcrumbs): ?>
	<nav class="wrap breadcrumb">
		<div class="container">
			<?php echo $helper->renderModule ('breadcrumbs') ?>
		</div>
	</nav>
	<?php endif; ?>
	
	<?php
		$spotlightCount = $helper->getRows('data.position');
	?>
	
	<?php $spotlightPosition= ""; 
		for ($i=0; $i<$spotlightCount; $i++) :
				$spotlightPosition = $helper->get('data.position',$i);
		endfor; 
	?>
	
	<?php if($spotlightCount && $spotlightPosition): ?>
	<div class="uber-spotlight uber-footnav">
		<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
			<div class="row">		
				<?php 
					for ($i=0; $i<$spotlightCount; $i++) :
					$screensXs = $helper->get('data.xs',$i);
					$screensSm = $helper->get('data.sm',$i);
					$screensMd = $helper->get('data.md',$i);
					$screensLg = $helper->get('data.lg',$i); 
				?>
				<div class="<?php echo $screensXs.' '.$screensSm.' '.$screensMd.' '.$screensLg; ?>">
						<?php echo $helper->renderModules ($helper->get('data.position',$i), array('style'=>'t3xhtml')) ?>
				</div>
				<?php endfor; ?>
				<hr />
			</div>
		<?php if(!$fullWidth): ?></div><?php endif; ?>
	</div>
	<?php endif; ?>
	
	<section class="uber-footer-inner">
		<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
			<div class="row">
				<div class="col-xs-12 col-md-7">
					<div class="footer-left">
						<?php if ($helper->countModules($footerLeftPosition)): ?>
							<?php echo $helper->renderModules ($footerLeftPosition) ?>
						<?php endif; ?>	
	
						<?php if ( $helper->get('facebook') || $helper->get('google-plus') || $helper->get('twitter') || $helper->get('pinterest') || $helper->get('linkedin') ): ?>
						<div class="uber-social">
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
				</div>
				
				<?php if ($helper->countModules($footerRightPosition)): ?>
				<div class="col-xs-12 col-md-5">					
					<div class="footer-right">				
						<?php echo $helper->renderModules ($footerRightPosition) ?>
					</div>
				</div>
				<?php endif; ?>
				
			</div>
		<?php if(!$fullWidth): ?></div><?php endif; ?>
	</section>

</footer>
<!-- //FOOTER -->