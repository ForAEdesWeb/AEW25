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

$catids = $params->get('catid');
if(isset($catids) && $catids['0'] != ''){
	$catid = $catids[0];	
	$jacategoriesModel = JCategories::getInstance('content');
	$jacategory = $jacategoriesModel->get($catid);
}

JLoader::register('JATempHelper',T3_TEMPLATE_PATH.'/templateHelper.php');

?>

<div class="section-inner <?php echo $params->get('moduleclass_sfx'); ?>">
	<?php if($module->showtitle || $params->get('module-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
		<?php if($params->get('module-intro')): ?>
			<p class="container-sm section-intro hidden-xs"><?php echo $params->get('module-intro'); ?></p>
		<?php endif; ?>	
	</h3>
	<?php endif; ?>
	<div class="container swiper-slide<?php echo $moduleclass_sfx; ?>"><div class="row">
		<?php 
			$numberColumn = $params->get('article_column',3);
			$count = 1;
		?>
		<?php foreach ($list as $item) : ?>
		<div class="item col-xs-12 col-sm-<?php echo 12/$numberColumn; ?> <?php if($count == $numberColumn): echo 'latest'; endif; ?>">
	
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
	
				<a class="article-img" href="<?php echo $item->link; ?>">
				<div style="background-image: url(<?php echo htmlspecialchars($images->image_intro); ?>);"></div>
				</a>
				<?php } ?>	
		
		   	<h<?php echo $item_heading; ?> class="article-title">
		   	<?php if ($params->get('link_titles') == 1) : ?>
					<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
				<?php echo $item->title; ?>
	        <?php if ($item->displayHits) :?>
				<span class="mod-articles-category-hits">
	            (<?php echo $item->displayHits; ?>)  </span>
	        <?php endif; ?></a>
	        <?php else :?>
	        <?php echo $item->title; ?>
	        	<?php if ($item->displayHits) :?>
				<span class="mod-articles-category-hits">
	            (<?php echo $item->displayHits; ?>)  </span>
	        <?php endif; ?></a>
	            <?php endif; ?>
	        </h<?php echo $item_heading; ?>>
	        
	        <div class="article-title-caption">
	        <?php 
					$extraContent = JATempHelper::loadParamsContents($item);
					
					$title_caption = $extraContent['title_caption'];
					
					if($title_caption):
						echo $title_caption;
					endif;
					?>
					</div>
	
	       	<?php if ($params->get('show_author')) :?>
	       		<span class="mod-articles-category-writtenby">
				<?php echo $item->displayAuthorName; ?>
				</span>
			<?php endif;?>
			
			<?php if ($item->displayCategoryTitle) :?>
				<span class="mod-articles-category-category">
				(<?php echo $item->displayCategoryTitle; ?>)
				</span>
			<?php endif; ?>
			
	    <?php if ($item->displayDate) : ?>
				<span class="mod-articles-category-date"><?php echo $item->displayDate; ?></span>
			<?php endif; ?>
			
			<?php if ($params->get('show_introtext')) :?>
				<p class="mod-articles-category-introtext">
				<?php echo $item->displayIntrotext; ?>
				</p>
			<?php endif; ?>
	
			<?php if ($params->get('show_readmore')) :?>
				<p class="mod-articles-category-readmore">
					<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
			        <?php if ($item->params->get('access-view')== FALSE) :
							echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE');
						elseif ($readmore = $item->alternative_readmore) :
							echo $readmore;
							echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit'));
						elseif ($params->get('show_readmore_title', 0) == 0) :
							echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE');
						else :
							echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE');
							echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit'));
						endif; ?>
		        </a>
				</p>
			<?php endif; ?>
			
		</div>
		<?php $count++;  endforeach; ?>
	</div>
	<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($jacategory->id));?>" class="btn btn-rounded btn-primary btn-lg smooth-scroll">Browse Our Themes <i class="fa fa-angle-right"></i></a>
	</div>
</div>