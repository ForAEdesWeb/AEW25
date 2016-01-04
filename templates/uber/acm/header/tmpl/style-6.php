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

// parse jdoc after render
$params->set('parse-jdoc', 1);


// use with T3
$t3doc = T3::getApp();
$doc = JFactory::getDocument();

// get params
$tplparams = JFactory::getApplication()->getTemplate(true)->params;
$sitename  = $tplparams->get('sitename');
$slogan    = $tplparams->get('slogan', '');
$logotype  = $tplparams->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $tplparams->get('logoimage', T3Path::getUrl('images/logo.png', '', true)) : '';
$logoimgsm = ($logotype == 'image' && $tplparams->get('enable_logoimage_sm', 0)) ? $tplparams->get('logoimage_sm', T3Path::getUrl('images/logo-sm.png', '', true)) : false;
$logolink  = $tplparams->get('logolink');

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}

$headright = $doc->countModules('head-search or languageswitcherload or right-menu');

// get logo url
$logourl = JURI::base(true);
if ($logolink == 'page') {
	$logopageid = $tplparams->get('logolink_id');
	$_item = JFactory::getApplication()->getMenu()->getItem ($logopageid);
	if ($_item) {
		$logourl = JRoute::_('index.php?Itemid=' . $logopageid);
	}
}

// Header Params
$fullWidth 									= $helper->get('full-width');
$headerBackground						= $helper->get('header-background');
$enableSticky 							= $helper->get('enable-sticky');
$headerHero 								= $helper->get('header-hero-position');
$logoImage									= $helper->get('logo-image');
?>
<!-- HEADER -->
<?php if ($helper->countModules($headerHero)): ?>
<div class="uber-header-wrap <?php echo $headerBackground; ?> <?php if($enableSticky): echo 'has-affix'; endif; ?>">
<?php endif; ?>
<header id ="uber-header-<?php echo $module->id; ?>" class="wrap uber-header header-6 dark-color" <?php if($enableSticky): echo 'data-spy="affix"'; endif; ?>>
	<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
	<div class="row">
		<!-- LOGO -->
		<div class="col-xs-6 col-sm-2 logo">
			<?php if($logoImage): ?>
			<div class="logo-image">
				<a href="<?php echo $logourl ?>" title="<?php echo strip_tags($sitename) ?>">
						<img class="logo-img" src="<?php echo $logoImage; ?>" alt="<?php echo strip_tags($sitename) ?>" />
					<span><?php echo $sitename ?></span>
				</a>
				<small class="site-slogan"><?php echo $slogan ?></small>
			</div>
			<?php else: ?>
			<div class="logo-<?php echo $logotype, ($logoimgsm ? ' logo-control' : '') ?>">
				<a href="<?php echo $logourl ?>" title="<?php echo strip_tags($sitename) ?>">
					<?php if($logotype == 'image'): ?>
						<img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
					<?php endif ?>
					<?php if($logoimgsm) : ?>
						<img class="logo-img-sm" src="<?php echo JURI::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
					<?php endif ?>
					<span><?php echo $sitename ?></span>
				</a>
				<small class="site-slogan"><?php echo $slogan ?></small>
			</div>
			<?php endif; ?>
		</div>
		<!-- //LOGO -->
		
		<?php if ($headright): ?>
			<div class="col-xs-6 col-sm-2 site-navigation-right pull-right">
				<?php if ($doc->countModules('right-menu')) : ?>
					<!-- RIGHT MENU -->
					<div class="right-menu">
						<?php echo $helper->renderModules ('right-menu') ?>
					</div>
					<!-- //RIGHT MENU -->
				<?php endif ?>

				<?php if ($doc->countModules('languageswitcherload')) : ?>
					<!-- LANGUAGE SWITCHER -->
					<div class="languageswitcherload">
						<?php echo $helper->renderModules ('languageswitcherload') ?>
					</div>
					<!-- //LANGUAGE SWITCHER -->
				<?php endif ?>
			</div>
		<?php endif ?>
	</div>
	<div class="row">
		<?php $t3doc->loadBlock ('mainnav') ?>
		
		<?php if ($tplparams->get('addon_offcanvas_enable')) : ?>
			<?php $t3doc->loadBlock ('off-canvas') ?>
		<?php endif ?>
	</div>
	<?php if(!$fullWidth): ?></div><?php endif; ?>
</header>
<!-- //HEADER -->

<?php if ($helper->countModules($headerHero)): ?>
	<?php echo $helper->renderModules ($headerHero) ?>
</div>
<?php endif; ?>	

<?php if($enableSticky): ?>
<script>
(function ($) {
	$(document).ready(function(){
		$('#uber-header-<?php echo ($module->id) ?>').affix({
      offset: {
        top: $('#uber-header-<?php echo ($module->id) ?>').offset().top
      }
		});	
	});
})(jQuery);
</script>
<?php endif; ?>