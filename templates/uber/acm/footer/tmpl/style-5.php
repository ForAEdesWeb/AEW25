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
$enableSticky 								= $helper->get('enable-sticky');
$footerLeftPosition 					= $helper->get('footer-left-position');
$footerRightPosition 					= $helper->get('footer-right-position');
?>

<!-- FOOTER -->
<footer class="wrap uber-footer footer-5 <?php echo $footerBackground; ?>" <?php if($footerImg): ?> style="<?php echo $footerBackgroundStyle; ?>" <?php endif; ?> <?php if($enableSticky): echo 'data-spy="affix"'; endif; ?>>
	<?php if($enableBreadcrumbs): ?>
	<nav class="wrap breadcrumb">
		<div class="container">
			<?php echo $helper->renderModule ('breadcrumbs') ?>
		</div>
	</nav>
	<?php endif; ?>
	
	<?php
		$spotlightCount = $helper->getRows('data.spotlight-position');
		$screens = array('lg', 'md', 'sm', 'xs');
		$arrWidths = array();
		foreach ($screens as $item) {
		    $widths = explode('-', $helper->get($item));
		    $dataWidth = array();
		    for ($i=0; $i<$spotlightCount; $i++) {
		        $dataWidth[] = isset($widths[$i])? trim($widths[$i]): '';
		    }
		    $arrWidths[$item] = $dataWidth;
		}

		$arrPositions = array();
		for ($i=0; $i<$spotlightCount; $i++) {
		    $colClass = array();
		    foreach ($screens as $key => $dataWidth) {
		        if (isset($arrWidths[$dataWidth][$i]) && $arrWidths[$dataWidth][$i]) {
		            $widthItem = $arrWidths[$dataWidth][$i];
		        } else {
		            $widthItem = '';
		        }
		        $colClass[$key] = array('class' => 'col-' . $dataWidth . '-', 'width' => $widthItem);
		
		        if (($key - 1) >= 0) {
		            if ($colClass[$key]['width'] == $colClass[$key - 1]['width']) {
		                unset($colClass[$key - 1]);
		            }
		        }
		    }
		    $newColClass = array();
		    foreach ($colClass as $itemClassCol) {
		        if (isset($itemClassCol['width']) && $itemClassCol['width']) {
		            $newColClass[] = $itemClassCol['class'] . $itemClassCol['width'];
		        }
		    }
		    $arrPositions[$i] = implode(' ', $newColClass);
		}
		
	?>
	
	<?php $spotlightPosition= ""; 
		for ($i=0; $i<$spotlightCount; $i++) :
				$spotlightPosition = $helper->get('data.spotlight-position',$i);
		endfor; 
	?>
	
	<?php if($spotlightCount && $spotlightPosition): ?>
	<div class="uber-spotlight uber-footnav">
		<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
			<div class="row">		
				<?php for ($i=0; $i<$spotlightCount; $i++) : ?>
				<div class="<?php echo $arrPositions[$i] ?>">
						<?php echo $helper->renderModules ($helper->get('data.spotlight-position',$i), array('style'=>'t3xhtml')) ?>
				</div>
				<?php endfor; ?>
				<hr />
			</div>
		<?php if(!$fullWidth): ?></div><?php endif; ?>
	</div>
	<?php endif; ?>
	
	<section class="uber-footer-inner">
		<?php if($enableBackToTop): ?>
		<!-- BACK TOP TOP BUTTON -->
		<div id="back-to-top-<?php echo $module->id; ?>" class="back-to-top affix-top">
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
		
		<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
		
			<div class="row">
				<div class="col-xs-12 col-md-6">
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
				<div class="col-xs-12 col-md-6">					
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