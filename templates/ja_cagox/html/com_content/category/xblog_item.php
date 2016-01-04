<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.framework');

// Create a shortcut for params.
$params  = & $this->item->params;
$images  = json_decode($this->item->images);
$info    = $params->get('info_block_position', 2);
$aInfo1 = ($params->get('show_publish_date') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
$aInfo2 = ($params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_hits'));
$topInfo = ($aInfo1 && $info != 1) || ($aInfo2 && $info == 0);
$botInfo = ($aInfo1 && $info == 1) || ($aInfo2 && $info != 0);
$icons = $params->get('access-edit') || $params->get('show_print_icon') || $params->get('show_email_icon');
$widthContent = "col-md-12";
if(JLayoutHelper::render('joomla.content.intro_image', $this->item)) {
	$widthContent = "col-md-6";
}
// update catslug if not exists - compatible with 2.5
if (empty ($this->item->catslug)) {
  $this->item->catslug = $this->item->category_alias ? ($this->item->catid.':'.$this->item->category_alias) : $this->item->catid;
}
?>

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
<div class="system-unpublished">
<?php endif; ?>

	<!-- Article -->
	<article class="blog-item-view">
		<div class="row">
			<div class="hide">
					<!-- Aside -->
			    <?php if ($topInfo || $icons) : ?>
			    <aside class="article-aside clearfix">
			      <?php if ($topInfo): ?>
			      <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
			      <?php endif; ?>
			      
			      <?php if ($icons): ?>
			      <?php echo JLayoutHelper::render('joomla.content.icons', array('item' => $this->item, 'params' => $params)); ?>
			      <?php endif; ?>
			    </aside>  
			    <?php endif; ?>
			    <!-- //Aside -->

				

		    <!-- footer -->
		    <?php if ($botInfo) : ?>
		    <footer class="article-footer clearfix">
		      <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
		    </footer>
		    <?php endif; ?>
		    <!-- //footer -->
			</div>
			<div class="<?php echo $widthContent; ?>"  itemprop="articleBody">
		  
		    <?php if ($params->get('show_title')) : ?>
					<?php echo JLayoutHelper::render('joomla.content.item_title', array('item' => $this->item, 'params' => $params, 'title-tag'=>'h2')); ?>
		    <?php endif; ?>
		    <?php echo $this->item->event->beforeDisplayContent; ?>
		    <?php if ($params->get('show_vote')) :
      if (isset($item->rating_sum) && $item->rating_count > 0) {
        $item->rating = round($item->rating_sum / $item->rating_count, 1);
        $item->rating_percentage = $item->rating_sum / $item->rating_count * 20;
      } else {
        if (!isset($item->rating)) $item->rating = 0;
        if (!isset($item->rating_count)) $item->rating_count = 0;
        $item->rating_percentage = $item->rating * 20;
      }
      $uri = JUri::getInstance();
      
      ?>
      <div itemtype="http://schema.org/AggregateRating" itemscope itemprop="aggregateRating" class="rating-info pd-rating-info">
        <form class="rating-form" method="POST" action="<?php echo htmlspecialchars($uri->toString()) ?>">
          <ul class="rating-list">
            <li class="rating-current" style="width:<?php echo $item->rating_percentage; ?>%;"></li>
            <li><a href="#" title="<?php echo JText::_('JA_1_STAR_OUT_OF_5'); ?>" class="one-star">1</a></li>
            <li><a href="#" title="<?php echo JText::_('JA_2_STARS_OUT_OF_5'); ?>" class="two-stars">2</a></li>
            <li><a href="#" title="<?php echo JText::_('JA_3_STARS_OUT_OF_5'); ?>" class="three-stars">3</a></li>
            <li><a href="#" title="<?php echo JText::_('JA_4_STARS_OUT_OF_5'); ?>" class="four-stars">4</a></li>
            <li><a href="#" title="<?php echo JText::_('JA_5_STARS_OUT_OF_5'); ?>" class="five-stars">5</a></li>
          </ul>
          <div class="rating-log">(<meta itemprop="bestRating" content="5" /><span itemprop="ratingValue"><?php echo $item->rating ?></span> / <span itemprop="ratingCount"><?php echo $item->rating_count; ?></span> votes)</div>
          <input type="hidden" name="task" value="article.vote" />
          <input type="hidden" name="hitcount" value="0" />
          <input type="hidden" name="user_rating" value="5" />
          <input type="hidden" name="url" value="<?php echo htmlspecialchars($uri->toString()) ?>" />
          <?php echo JHtml::_('form.token') ?>
        </form>
      </div>
      
      <script type="text/javascript">
        !function($){
          $('.rating-form').each(function(){
            var form = this;
            $(this).find('.rating-list li a').click(function(event){
              event.preventDefault();
              form.user_rating.value = this.innerHTML;
              form.submit();
            });
          });
        }(window.jQuery);
      </script>
    <?php endif; ?>
		    <?php echo $this->item->introtext; ?>

				<?php if ($params->get('show_readmore') && $this->item->readmore) :
					if ($params->get('access-view')) :
						$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
					else :
						$menu      = JFactory::getApplication()->getMenu();
						$active    = $menu->getActive();
						$itemId    = $active->id;
						$link1     = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
						$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
						$link      = new JURI($link1);
						$link->setVar('return', base64_encode($returnURL));
					endif;
					?>
					<section class="readmore">
						<a class="btn btn-default" href="<?php echo $link; ?>">
							<span>
							<?php if (!$params->get('access-view')) :
								echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
							elseif ($readmore = $this->item->alternative_readmore) :
								echo $readmore;
								if ($params->get('show_readmore_title', 0) != 0) :
									echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
								endif;
							elseif ($params->get('show_readmore_title', 0) == 0) :
								echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
							else :
								echo JText::_('COM_CONTENT_READ_MORE');
								echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
							endif; ?>
							</span>
						</a>
					</section>
				<?php endif; ?>
			</div>
			
			<?php if(JLayoutHelper::render('joomla.content.intro_image', $this->item)) :?>
			<div class="col-md-6">
				<section class="article-intro clearfix">
					<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>

				</section>
			</div>
			<?php endif; ?>
		</div>
	</article>
	<!-- //Article -->

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
</div>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?> 
