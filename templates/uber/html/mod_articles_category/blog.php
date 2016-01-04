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
	<div class="container">
	
	<div class="blog-style-1 category-module<?php echo $moduleclass_sfx; ?>">
		<div class="small-head">
			<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($jacategory->id));?>" title=""><?php echo JText::_( 'TPL_FROM_FOR_BLOG' ); ?></a>
		</div>
		<div class="row">
		<?php 
			$numberColumn = $params->get('article_column',3);
			$count = 1;
		?>
		<?php foreach ($list as $item) : ?>
		  <div class="col-xs-12 col-sm-<?php echo 12/$numberColumn; ?> <?php if($count == $numberColumn): echo 'latest'; endif; ?>">
		  <div class="row">
			  <div class="col-sm-7">
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
		
				<div class="article-meta">
		      <?php if ($params->get('show_author')) :?>
		       	<span class="mod-articles-category-writtenby">
							<i class="fa fa-user"></i> <?php echo $item->displayAuthorName; ?>
						</span>
					<?php endif;?>
				
					<?php if ($item->displayCategoryTitle) :?>
						<span class="mod-articles-category-category">
						<i class="fa fa-folder-open"></i> <?php echo $item->displayCategoryTitle; ?>
						</span>
					<?php endif; ?>
			
			    <?php if ($item->displayDate) : ?>
						<span class="mod-articles-category-date">
						<i class="fa fa-calendar"></i> <?php echo $item->displayDate; ?>
						</span>
					<?php endif; ?>
				</div>
		
				<?php if ($params->get('show_introtext')) :?>
					<p class="article-introtext">
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
								echo JText::sprintf('TPL_MOD_ARTICLES_CATEGORY_READ_MORE_TITLE');
							else :
								echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE');
								echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit'));
							endif; ?>
			        </a>
					</p>
				<?php endif; ?>
				</div>
				<div class="col-sm-5">
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
					<img src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo $item->title; ?>" />
				</a>
				<?php } ?>	
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		</div>
	</div>
	
	</div>
</div>