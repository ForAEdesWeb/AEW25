<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_category
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
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
	<ul class="category-module<?php echo $moduleclass_sfx; ?>">
	
	<?php if ($grouped) : ?>
		<?php foreach ($list as $group_name => $group) : ?>
		<li>
			<h<?php echo $item_heading; ?>><?php echo $group_name; ?></h<?php echo $item_heading; ?>>
			<ul>
				<?php foreach ($group as $item) : ?>
				
					<li>
						<h<?php echo $item_heading+1; ?>>
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
				        </h<?php echo $item_heading+1; ?>>
	
	
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
				<?php echo $item->fulltext; ?>
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
			</li>
				<?php endforeach; ?>
			</ul>
		</li>
		<?php endforeach; ?>
	<?php else : ?>
	<div class="panel-group" id="accordion">
		<?php foreach ($list as $item) : ?>
			<?php $extrafields = new JRegistry($item->attribs); ?>
			<div class="panel panel-default">
		    <div class="panel-title">
			<!-- Title Override -->
					<a class="mod-articles-category-title <?php echo $item->active; ?>" href="#<?php echo $item->id ;?>" data-toggle="collapse" data-parent="#accordion">
						<div class="container">
						<div class="pull-left">
							<i class="<?php echo $extrafields->get('icon'); ?>"></i>
							<?php echo $item->title; ?>
							
							<p class="small">
							<?php if ($item->displayHits) :?>
									<span class="mod-articles-category-hits">
	                (<?php echo $item->displayHits; ?>)  </span> 
	            <?php endif; ?>
							
								<?php if ($params->get('show_author')) :?>
									- <span class="mod-articles-category-writtenby">
										<?php echo $item->displayAuthorName; ?>
									</span>
									<?php endif;?>
			
									<?php if ($item->displayDate) : ?>
										- <span class="mod-articles-category-date"><?php echo $item->displayDate; ?></span>
									<?php endif; ?>
									</p>
						</div>
			
	
							
							<div class="text-right"><?php echo $extrafields->get('position'); ?></div>
						</div>
					</a>
			</div>
			
			<!-- End Title Override -->
	
			<!-- Content Article override -->
				<div id="<?php echo $item->id ;?>" class="panel-collapse collapse container">
				
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-7 col-md-8">
							<?php echo $item->introtext; ?>
						</div>
					
						<div class="col-sm-5 col-md-4">
							<div class="text-box-hightlight">
								<?php if($extrafields->get('location')) :?>
									<div class="element">
										<i class="fa fa-map-marker"></i>
										<b><?php echo JText::_('TPL_LOCATION'); ?>: </b>
										<?php echo $extrafields->get('location'); ?>
									</div>
								<?php endif ;?>
								
								<?php if($extrafields->get('employment')) :?>
									<div class="element">
										<i class="fa fa-clock-o"></i>
										<b><?php echo JText::_('TPL_EMPLOYMENT'); ?>: </b>
										<?php echo $extrafields->get('employment'); ?>
									</div>
								<?php endif ;?>
								
								<?php if($extrafields->get('job')) :?>
									<div class="element">
										<i class="fa fa-credit-card"></i>
										<b><?php echo JText::_('TPL_JOB'); ?>: </b>
										<?php echo $extrafields->get('job'); ?>
									</div>
								<?php endif ;?>
								
								<?php if($extrafields->get('salary')) :?>
									<div class="element">
										<i class="fa fa-shield"></i>
										<b><?php echo JText::_('TPL_SALARY'); ?>: </b>
										<?php echo $extrafields->get('salary'); ?>
									</div>
								<?php endif ;?>
								
								<?php if($extrafields->get('closing')) :?>
									<div class="element">
										<i class="fa fa-calendar"></i>
										<b><?php echo JText::_('TPL_CLOSING'); ?>: </b>
										<?php echo $extrafields->get('closing'); ?>
									</div>
								<?php endif ;?>
								
								<?php if($extrafields->get('soft')) :?>
									<div class="element">
										<i class="fa fa-check-square-o"></i>
										<b><?php echo JText::_('TPL_SOFT'); ?>: </b>
										<div><?php echo $extrafields->get('soft'); ?></div>
									</div>
								<?php endif ;?>	
							</div>
							
							<?php if($extrafields->get('link_1') || $extrafields->get('link_1') || $extrafields->get('link_1') || $extrafields->get('link_1')): ?>
								<div class="social">
									<?php echo $extrafields->get('link_1'); ?>
									<?php echo $extrafields->get('link_2'); ?>
									<?php echo $extrafields->get('link_3'); ?>
									<?php echo $extrafields->get('link_4'); ?>
								</div>
							<?php endif ;?>
						</div>
						
						<div class="col-sm-12">
							<div class="text-left">
								<h3><?php echo JText::_('TPL_APPLY') ;?></h3>
								<p><?php echo JText::_('TPL_APPLY_DESC') ;?></p>
							</div>
							
							<a class="btn btn-primary btn-lg btn-info" href="<?php echo $extrafields->get('link_5'); ?>">
								<?php echo JText::_('TPL_BUTTON_APPLY') ;?> <i class="fa fa-angle-right"></i>
							</a>
						</div>
					</div>
				</div>
				 
				</div>
			<!-- End Content Article override -->
			</div>	
		</li>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	</ul>
</div>