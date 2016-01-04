<?php
/**
 * ------------------------------------------------------------------------
 * Uber Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$doc->addScript (T3_TEMPLATE_URL.'/acm/gallery/js/isotope.pkgd.min.js');
$doc->addScript (T3_TEMPLATE_URL.'/acm/gallery/js/ekko-lightbox.js');
$doc->addScript (T3_TEMPLATE_URL.'/acm/gallery/js/imagesloaded.pkgd.min.js');
?>

<?php 
	$col 					= $helper->get('col') ;
	$btnText			= $helper->get('btn-text');
	$btnClass			= $helper->get('btn-class');
	$btnLink			= $helper->get('btn-link'); 
	$col 								= $helper->get('col') ;
	$hoverAnimation			= $helper->get('hover-animation');
	
	if(!$hoverAnimation) {
		$hoverAnimation = 'none';
	}   
	
	$blockImg 				= $helper->get('block-bg');
	$blockImgBg  			= 'background-image: url("'.$blockImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($blockImg): echo 'style="'.$blockImgBg.'"'; endif; ?>>	
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
	<div class="acm-gallery style-2 style-<?php echo $hoverAnimation; ?>">
		<div class="isotope-layout<?php echo $helper->get('fullwidth'); ?>">
			<div class="isotope" style="margin: 0 -<?php echo $helper->get('gutter')/2; ?>px;">
				<?php if($helper->get('text-1')) :?>
					<div class="mask"></div>
				<?php endif ; ?>
				<div class="item grid-sizer grid-xs-<?php echo $helper->get('colmb'); ?> grid-sm-<?php echo $helper->get('coltb'); ?> grid-md-<?php echo $helper->get('coldt'); ?>"></div>
				
				<?php
					$count = $helper->getRows('gallery.img'); 
				 ?>
				 
				 <?php for ($i=0; $i<$count; $i++) : ?>
				 <?php 
					$itemsize 		= $helper->get('gallery.selectitem', $i); 
					$itemTitle		= $helper->get('gallery.title', $i);
					$itemDetails	= $helper->get('gallery.details', $i);
					$itemLink			= $helper->get('gallery.link', $i);
				 ?>
					<?php if($helper->get ('gallery.img', $i)):?>
						<div class="item item-<?php echo $itemsize; ?> grid-xs-<?php echo $helper->get('colmb'); ?> grid-sm-<?php echo $helper->get('coltb'); ?> grid-md-<?php echo $helper->get('coldt'); ?> <?php echo $helper->get('animation') ?>" style="padding: 0 <?php echo $helper->get('gutter')/2; ?>px <?php echo $helper->get('gutter'); ?>px;">
							<a class="item-mask" href="<?php echo $itemLink; ?>"></a>
							<div class="item-image" <?php if($hoverAnimation=='swiper'): ?> style="background: url(<?php echo $helper->get ('gallery.img', $i) ?>)" <?php endif; ?> >
								<?php if($hoverAnimation=='swiper'): ?>
									<a class="item-mask" href="<?php echo $itemLink; ?>"></a>
								<?php endif ; ?>
								<?php if($hoverAnimation!='swiper'): ?>
									<?php if($itemLink):?><a href="<?php echo $itemLink; ?>" title="<?php echo $itemTitle; ?>"><?php endif ; ?>
										<img src="<?php echo $helper->get ('gallery.img', $i) ?>" >
									<?php if($itemLink):?></a><?php endif ; ?>
								<?php endif ; ?>
							</div>
							<?php if($itemTitle || $itemDetails): ?>
							<div class="item-details">
								<?php if($itemTitle): ?><h4><?php if($itemLink):?><a href="<?php echo $itemLink; ?>" title="<?php echo $itemTitle; ?>"><?php endif ; ?><?php echo $itemTitle; ?><?php if($itemLink):?></a><?php endif ; ?></h4><?php endif ; ?>
								<?php if($itemDetails): ?><span><?php echo $itemDetails; ?></span><?php endif ; ?>
							</div>
							<?php endif ; ?>
						</div>
					<?php endif ; ?>
				<?php endfor ?>
				
			</div>
	
			<?php if($helper->get('text-1')): ?>
				<div class="caption">
					<p><?php echo $helper->get('text-1') ?></p>
				</div>
			<?php endif ;?>
			
			<?php if($btnText): ?>
			<a class="btn <?php echo $btnClass; ?>" href="<?php echo $btnLink; ?>"><?php echo $btnText; ?> <i class="fa fa-angle-right"></i></a>
			<?php endif ;?>
		</div>
	</div>
</div>