<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_category
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
if(isset($item_heading) || $item_heading=='') $item_heading = 4;
JLoader::register('JATempHelper',T3_TEMPLATE_PATH.'/templateHelper.php');
?>

<div class="category-module category-carousel <?php echo $moduleclass_sfx; ?>">
<div id="article-carousel<?php echo $module->id;?>" class="carousel slide" data-ride="carousel" itemscope itemtype="http://schema.org/Blog" data-interval="false">
  <div class="carousel-inner">
  <?php $count=0; 
		foreach ($list as $item) : ?>   
  		<div class="item item-<?php echo ($count+1); ?> <?php if($count==0): echo 'active'; endif; ?>">
	    	<div class="article-img">
					<?php  
					//Get images 
					$images = "";
					if (isset($item->images)) {
						$images = json_decode($item->images);
					}
					$imgexists = (isset($images->image_intro) and !empty($images->image_intro)) || (isset($images->image_fulltext) and !empty($images->image_fulltext));
					
					if ($imgexists) {			
					$images->image_intro = $images->image_intro?$images->image_intro:$images->image_fulltext;
					?>
						<div class="img-intro">
							<img
								<?php if ($images->image_intro_caption):
									echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
								endif; ?>
								src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
						</div>
					<?php } ?>
				</div>
				<div class="container">
				<div class="article-content">

				<?php echo $item->introtext; ?>
		
					<?php if ($params->get('show_readmore')) :?>
						<p class="mod-articles-category-readmore">
							<a class="mod-articles-category-title btn btn-link <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
					        <?php if ($item->params->get('access-view')== FALSE) :
									echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE');
								elseif ($readmore = $item->alternative_readmore) :
									echo $readmore;
									echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit'));
								elseif ($params->get('show_readmore_title', 0) == 0) :
									echo JText::sprintf('TPL_MOD_ARTICLES_CATEGORY_READ_MORE_TITLE');
								else :
									echo JText::_('TPL_MOD_ARTICLES_CATEGORY_READ_MORE');
									echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit'));
								endif; ?>
				        </a>
						</p>
					<?php endif; ?>
				</div></div>
	</div>
	<?php $count++; 
	endforeach; ?>
	</div>
	
  <!-- Controls -->
  <ol class="carousel-indicators">
  <?php
		for($i=0;$i<count($list);$i++){
		
		$slideshow = JATempHelper::loadParamsContents($list[$i]);
		
		$title_caption = $slideshow['title_caption'];
		
		$active = '';
		
		if($i==0) $active=' class="active"';
		echo '<li data-target="#article-carousel'.$module->id.'" style="width : '.(100/count($list)).'%" data-slide-to="'.$i.'"'.$active.'><span class="number">'.($i+1).'</span><span class="title">'.$title_caption.'<strong>'.$list[$i]->title.'</strong></span></li>';
	}
  ?>
  </ol>
  
  <a data-slide="prev" href="#article-carousel<?php echo $module->id;?>" class="left carousel-control"><span><i class="fa fa-angle-left"></i></span></a>
  <a data-slide="next" href="#article-carousel<?php echo $module->id;?>" class="right carousel-control"><span><i class="fa fa-angle-right"></i></span></a>
</div>
</div>
