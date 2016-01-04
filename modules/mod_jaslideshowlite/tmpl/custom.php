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

// rebuild data 
$imgtypes = array('bg', 'first', 'second', 'thumb');
$items = array();

for ($i = 0, $il = count($images); $i < $il; $i++) {
	
	$iname = basename($images[$i]);
	$itype = 'img';
	$count = 0;
	$ext = array('png','gif','jpg','jpeg','PNG','GIF','JPG','JPEG');
	//check if exist
	str_replace($imgtypes, '', $iname, $count);

	if ($count && ($match = preg_split ('/[-_.]/', $iname))) {
		$iname = $match[0];
		$itype = (!empty($match[1]) && in_array($match[1], $imgtypes)) ? $match[1] : 'img';
	}

	if (!isset($items[$iname])) {
		$items[$iname] = new stdClass;
		$items[$iname]->caption = '';
		$items[$iname]->title = '';
		$items[$iname]->cls = 'leftright';
	}

	$items[$iname]->$itype = $images[$i];
	if(strlen(trim($captionsArray[$i]))) {
		$items[$iname]->caption = trim($captionsArray[$i]);
	}
	
	if($itype == 'img' || $itype == 'bg'){
		if(!empty($classes[$i])){
			$items[$iname]->cls = $classes[$i];
		}

		if(!empty($titles[$i])){
			$items[$iname]->title = $titles[$i];
		}
	}
	foreach($ext as $imgType){
		if( file_exists(JPATH_SITE . '/' . $params->get('folder') . '/' . $iname . '-first.' . $imgType) ){
			$items[$iname]->first = $params->get('folder') . '/' . $iname . '-first.' . $imgType ;
			break;
		}
	}
	
	foreach($ext as $imgType){
		if( file_exists(JPATH_SITE . '/' . $params->get('folder') . '/' . $iname . '-second.' . $imgType )){
			$items[$iname]->second = $params->get('folder') . '/' . $iname . '-second.' . $imgType ;
			break;
		}
	}
	
	if ($showThumbnail && ($itype == 'img' || $itype == 'bg')) {
		$items[$iname]->thumb = $thumbArray[$i];
	}
}

?>
<div id="ja-ss-<?php echo $module->id;?>" class="ja-ss<?php echo $params->get( 'moduleclass_sfx' );?> ja-ss-wrap <?php echo $type; ?>"  style="visibility: hidden">
	<div class="ja-ss-items">
	<?php foreach ($items as $item): ?>
		<div class="ja-ss-item <?php echo $item->cls; ?>">
			
			<?php if(isset($item->bg) || isset($item->img)) : ?>
			<img class="ja-ss-item-bg" src="<?php echo (isset($item->bg)? $item->bg : $item->img);?>" alt=""/>
			<?php endif; ?>
			
			<?php if(isset($item->first)): ?>
			<div class="ja-ss-sprite first animate delay500 adelay1500 duration500">
				<img class="ja-ss-item-img" src="<?php echo $item->first;?>" alt=""/>
			</div>
			<?php endif; ?>

			<?php if(isset($item->second)): ?>
			<div class="ja-ss-sprite second animate delay500 adelay2000 duration500">
				<img class="ja-ss-item-img" src="<?php echo $item->second;?>" alt="<?php echo str_replace('"', '"/', strip_tags($item->caption) );?>"/>
			</div>
			<?php endif; ?>

			<?php if($item->caption || $item->title): ?>
			<div class="ja-ss-desc animate delay500 adelay2500 duration500">
				<?php if($item->title) : ?>
				<h3><?php echo $item->title ?></h3> 
				<?php endif; ?>

				<?php echo $item->caption ?>
			</div>
			<?php endif; ?>
			<div class="ja-ss-mask"></div>
		</div>
	<?php endforeach; ?>
	</div>

	<?php if ($showThumbnail == 1) : ?>
	<div class="ja-ss-thumbs-wrap">
		<div class="ja-ss-thumbs"><!--
		<?php foreach ($items as $item): ?>
			--><div class="ja-ss-thumb">
				<img src="<?php echo $item->thumb; ?>" alt="Photo Thumbnail" />
			</div><!-- //ja-ss-thumb
		<?php endforeach; ?>
		--></div>
	</div>
	<?php endif; ?>

	<?php if ($showNavigation): ?>
	<div class="ja-ss-btns clearfix">
		<span class="ja-ss-prev">&laquo; <?php echo JText::_('PREVIOUS');?></span>
		<span class="ja-ss-playback">&lsaquo; <?php echo JText::_('PLAYBACK');?></span>
		<span class="ja-ss-stop"><?php echo JText::_('STOP');?></span>
		<span class="ja-ss-play"><?php echo JText::_('PLAY');?> &rsaquo;</span>
		<span class="ja-ss-next"><?php echo JText::_('NEXT');?>  &raquo;</span>
	</div>
	<?php endif; ?>
</div>