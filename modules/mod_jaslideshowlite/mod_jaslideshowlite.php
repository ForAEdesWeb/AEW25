<?php
/**
 * ------------------------------------------------------------------------
 * JA Slideshow Lite Module for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */


defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__) .  '/helpers/'  . 'helper.php');
require_once (dirname(__FILE__) . '/helpers/' . 'jaimage.php');

// load mootools
JHTML::_('behavior.framework');

$template = JFactory::getApplication()->getTemplate();
$helper = ModJASlideshowLite::getInstance();

$animationType = $type = $params->get('type', 'fade');
$autoPlay = $params->get('autoplay', 1);
$showNavigation = $params->get('navigation', 0);
$showThumbnail = $params->get('thumbnail', 0);

//refine parameter
//at least 1 pixel image
if(($params->get('mainWidth') == 'auto' && $params->get('mainHeight') == 'auto')){
	$params->set('main_mode', 'none');
} else {
	$params->set('main_mode', 'crop');
	$params->set('mainWidth', max(1, intval($params->get('mainWidth'))));
	$params->set('mainHeight', max(1, intval($params->get('mainHeight'))));
}

$params->set('thumbWidth', max(1, intval($params->get('thumbWidth'))));
$params->set('thumbHeight', max(1, intval($params->get('thumbHeight'))));
$params->set('source-articles-images-main_mode', $params->get('main_mode', 'crop'));
if(substr($params->get('folder'), 0, 1) == '/'){
	$params->set('folder', substr($params->get('folder'), 1));
}

//load assets
if (!defined('_MODE_JASLIDESHOWLITE_ASSETS_')) {
    define('_MODE_JASLIDESHOWLITE_ASSETS_', 1);
	
	JHTML::stylesheet('modules/' . $module->module . '/assets/css/animate.css');
	if (is_file(JPATH_SITE .  '/templates/'. $template .  '/css/'  . $module->module . '.css')) {
        JHTML::stylesheet('templates/' . $template . '/css/' . $module->module . '.css');
	} else {
		JHTML::stylesheet('modules/' . $module->module . '/assets/css/' . $module->module . '.css');
	}
	
	JHTML::script('modules/' . $module->module . '/assets/js/script.js');
}

if (!defined('_MODE_JASLIDESHOWLITE_ASSETS_' . strtoupper($type))) {
	define('_MODE_JASLIDESHOWLITE_ASSETS_' . strtoupper($type), 1);
	
	// add extra css for selected type
	$fname = $module->module . '-' . $type . '.css';
	if (is_file(JPATH_SITE . '/templates/' . $template .  '/css/' . $fname)) {
        JHTML::stylesheet('templates/' . $template . '/css/' . $fname);
	} else if (is_file(JPATH_SITE .  '/modules/' . $module->module . '/assets/css/' . $fname)) {
		JHTML::stylesheet('modules/' . $module->module . '/assets/css/' . $fname);
	}
}

//get the image list
$list = $helper->callMethod('getListImages', $params);

if( !empty($list) ) :
	$images		   = $list['mainImageArray'];
	$thumbArray	   = $list['thumbArray'];
	$captionsArray = $list['captionsArray'];
	$urls		   = $list['urls'];
	$targets 	   = $list['targets'];
	$classes	   = $list['classes'];
	$titles		   = $list['titles'];
	
	//remove all unwanted images
	$timages 		= array();
	$tthumbArray 	= array();
	$tcaptionsArray = array();
	$turls 			= array();
	$ttargets 		= array();
	$ttitles 		= array();

	for($i = 0, $il = count($images); $i < $il; $i++){
		$iname = basename($images[$i]);
		if(strpos($iname, '-first') === false && strpos($iname, '-second') === false && strpos($iname, '-thumb') === false){
			$timages[] = $images[$i];
			$tthumbArray[] = $thumbArray[$i];
			$tcaptionsArray[] = $captionsArray[$i];
			$turls[] = $urls[$i];
			$ttargets[] = $targets[$i];
			$ttitles[] = $titles[$i];
		} 
	}
	
	if($type != 'custom'){			
		$images = $timages;
		$thumbArray = $tthumbArray;
		$captionsArray = $tcaptionsArray;
		$titles = $ttitles;
	}
	
	$urls = $turls;
	$targets = $ttargets;
	
	// get layout
	$layout = JModuleHelper::getLayoutPath( $module->module, $type );
	
	//if file not exist or layout is default.php in module folder, we must recheck again. This is getLayoutPath issue
	if (!file_exists($layout) || basename($layout) == 'default.php') {
		// use default layout
		$layout = JModuleHelper::getLayoutPath( $module->module );
	}
	
	require( $layout );
?>
<script type="text/javascript">
	window.addEvent('domready', function(){
		window.jassliteInst = window.jassliteInst || [];
		window.jassliteInst.push(new JASliderCSS('ja-ss-<?php echo $module->id;?>', {
			interval: 5000,
			duration: <?php echo ($type != 'custom' ? '1000' : '2200'); ?>,
			
			repeat: true,
			autoplay: <?php echo $autoPlay;?>,
			
			navigation: <?php echo $showNavigation;?>,
			thumbnail: <?php echo $showThumbnail;?>,
			
			urls:['<?php echo implode('\',\'', $urls); ?>'],
			targets:['<?php echo implode('\',\'', $targets); ?>']
		}));
	});
</script>
<!-- Fix animation in IE -->
<!--[if IE]>
	<script type="text/javascript">
		jassurl = '<?php echo JUri::base(true) . '/modules/' . $module->module ?>/assets/'; 
	</script>
	<script type="text/javascript" src="<?php echo JUri::base(true) . '/modules/' . $module->module ?>/assets/js/iefix.js"></script>
<![endif]-->

<?php
	unset($list);
	unset($images);
	unset($thumbArray);
	unset($captionsArray);
	unset($titles);
	unset($urls);
	unset($targets);
	
endif;
?>