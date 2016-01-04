<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_category
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<!-- script change button -->
<script type="text/javascript">
	window.addEvent('domready', function(){
		$$('.btn-horizontal').addEvent("click",function(){
			if($("ja-ul-lastnews").hasClass("vertical-layout")){
				$("ja-ul-lastnews").removeClass("vertical-layout");
			}
			if(!$("ja-ul-lastnews").hasClass("horizontal-layout")){
				$("ja-ul-lastnews").addClass("horizontal-layout");
			}
			
			$(this).addClass("active");
			
			if($$('.btn-vertical').hasClass("active")){
				$$('.btn-vertical').removeClass("active")
			}
			
		    
		});
		$$('.btn-vertical').addEvent("click",function(){
			if($("ja-ul-lastnews").hasClass("horizontal-layout")){
		    	$("ja-ul-lastnews").removeClass("horizontal-layout");
			}
			if(!$("ja-ul-lastnews").hasClass("vertical-layout")){
				$("ja-ul-lastnews").addClass("vertical-layout");
			}
			
			$(this).addClass("active");
			
			if($$('.btn-horizontal').hasClass("active")){
				$$('.btn-horizontal').removeClass("active")
			}
		});
	});
</script>
<!-- Button -->
<div id="button">	
	<span class="btn-vertical active">vertical</span>
	<span class="btn-horizontal">horizontal</span>
</div>
<!-- end button--> 
<ul id="ja-ul-lastnews" class="category-module<?php echo $moduleclass_sfx; ?> vertical-layout">
<!-- Group -->
<?php
if ($grouped) :
?>
	<?php foreach ($list as $group_name => $group) : ?>
	
	<li>
		
		
		<h<?php echo $item_heading; ?>><?php echo $group_name; ?></h<?php echo $item_heading; ?>>
		<ul>
			<?php foreach ($group as $item) : ?>
				<!-- Overridde add images of content -->
				<?php
					//Get images 
					$images = "";
					if (isset($item->images)) {
						$images = json_decode($item->images);
					}
					//Check images empty
					$cimgsempty = ''; 
					if(!(isset($images->image_intro) and !empty($images->image_intro))){
						$cimgsempty = ' class="no-images clearfix"';
					}
					
					
				?>
				<li<?php echo $cimgsempty;?> class="clearfix">

					<!-- Intro images -->
					<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
						<div class="img-intro">
							<img
								<?php if ($images->image_intro_caption):
									echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
								endif; ?>
								src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
						</div>
					<?php endif; ?>

					<!-- Full images-->
					<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
						<div class="img-details">
							<img
								<?php if ($images->image_fulltext_caption):
									echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
								endif; ?>
								src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/>
						</div>
					<?php endif; ?>

					<!-- End add -->
					<div class="jacontent clearfix">
					<h<?php echo $item_heading+1; ?>>
					   	<?php if ($params->get('link_titles') == 1) : ?>
						
						<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
						<?php echo $item->title; ?>
				        <?php if ($item->displayHits) :?>
							</a>
				        <?php else :?>
				        <?php echo $item->title; ?>
				        	</a>
				            <?php endif; ?>
			        </h<?php echo $item_heading+1; ?>>

				<div class="article-aside clearfix">
				<?php if ($params->get('show_author')) :?>
					<span class="mod-articles-category-writtenby">
					<strong>Written by</strong>
					<?php echo $item->displayAuthorName; ?>
					</span>
				<?php endif;?>

				<?php if ($item->displayCategoryTitle) :?>
					<span class="mod-articles-category-category">
					<strong>Published in</strong>
					<?php echo $item->displayCategoryTitle; ?>
					</span>
				<?php endif; ?>
				<?php if ($item->displayDate) : ?>
					<span class="mod-articles-category-date">
					<strong>Write on</strong>
					<?php echo $item->displayDate; ?>
					</span>
				<?php endif; ?>
				<?php endif; ?>
				<?php if ($item->displayHits) :?>
					<span class="mod-articles-category-hits">
				    <strong>Read</strong><?php echo $item->displayHits; ?>  </span>
				<?php endif; ?>
				</div>
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
						if ($params->get('show_readmore_title', 0) != 0) :
							echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
						endif;
					elseif ($params->get('show_readmore_title', 0) == 0) :
						echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE');
					else :

						echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE');
						echo JHtml::_('string.truncate', ($item->title), $params->get('readmore_limit'));
					endif; ?>
	        </a>
					</p>
				<?php endif; ?>
			</div>
		</li>
			<?php endforeach; ?>
		</ul>
	</li>
	<?php endforeach; ?>
<!-- End group -->	
<?php else : ?>
	<?php foreach ($list as $item) : ?>
		<!-- Overridde add images of content -->
		<?php
			//Get images 
			$images = "";
			if (isset($item->images)) {
				$images = json_decode($item->images);
			}
			//Check images empty
			$cimgsempty = '';
			if(!(isset($images->image_intro) and !empty($images->image_intro))){
				$cimgsempty = ' class="no-images"';
			}
			
			
		?>
	    <li<?php echo $cimgsempty;?> class="clearfix">
		<div class="showcategory">
			<!-- Intro images -->
			<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
				<div class="img-intro">
					<img
						<?php if ($images->image_intro_caption):
							echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
						endif; ?>
						src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
				</div>
			<?php endif; ?>
			<!-- Full images-->
			<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
				<div class="img-details">
					<img
						<?php if ($images->image_fulltext_caption):
							echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
						endif; ?>
						src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/>
				</div>
			<?php endif; ?>
			<!-- End add -->
			<div class="jacontent clearfix">
			   	<h<?php echo $item_heading+1; ?>>
					   	<?php if ($params->get('link_titles') == 1) : ?>
						<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
						<?php echo $item->title; ?>
				        </a>
				        <?php else :?>
				        <?php echo $item->title; ?>
				        	</a>
				            <?php endif; ?>
			        </h<?php echo $item_heading+1; ?>>
				
        <div class="article-aside clearfix">
		      <?php if ($params->get('show_author')) :?>
		      <span class="mod-articles-category-writtenby">
  					<strong>by </strong><?php echo $item->displayAuthorName; ?>
					</span>
  				<?php endif;?>

  				<?php if ($item->displayCategoryTitle) :?>
  					<span class="mod-articles-category-category">
  					<strong>in</strong><?php echo $item->displayCategoryTitle; ?>
  					</span>
  				<?php endif; ?>

  		    <?php if ($item->displayDate) : ?>
  					<span class="mod-articles-category-date">
  					<strong>on</strong><?php echo $item->displayDate; ?>
  					</span>
  				<?php endif; ?>

  				<?php if ($item->displayHits) :?>
  					<span class="mod-articles-category-hits">
  				    <?php echo $item->displayHits; ?> views
            </span>
  				<?php endif; ?>

				</div>

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
			</div>
	</li>
	<?php endforeach; ?>
<?php endif; ?>
</ul>
