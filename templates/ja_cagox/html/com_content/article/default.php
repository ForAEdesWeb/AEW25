<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::addIncludePath(T3_PATH . '/html/com_content');
JHtml::addIncludePath(dirname(dirname(__FILE__)));

// Create shortcuts to some parameters.
$params   = $this->item->params;
$images   = json_decode($this->item->images);
$urls     = json_decode($this->item->urls);
$canEdit  = $params->get('access-edit');
$user     = JFactory::getUser();
$info    = $params->get('info_block_position', 2);
$aInfo1 = ($params->get('show_publish_date') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
$aInfo2 = ($params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_hits'));
$topInfo = ($aInfo1 && $info != 1) || ($aInfo2 && $info == 0);
$botInfo = ($aInfo1 && $info == 1) || ($aInfo2 && $info != 0);
$icons = !empty($this->print) || $canEdit || $params->get('show_print_icon') || $params->get('show_email_icon');

JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');
?>

<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header clearfix">
		<h1 class="page-title"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
<?php endif; ?>

<div class="item-page<?php echo $this->pageclass_sfx ?> clearfix">

<?php if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative) : ?>
	<?php echo $this->item->pagination; ?>
<?php endif; ?>

<!-- Article -->
<article itemscope itemtype="http://schema.org/Article" class="blog-item-view">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />




<div class="media article-detail-view">
	<div class="media-object pull-left">
		<!-- Aside -->
		<?php if ($topInfo || $icons) : ?>
		<aside class="article-aside clearfix">
		  <?php if ($topInfo): ?>
		  <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
		  <?php endif; ?>
		  
		  <?php if ($icons): ?>
		  <?php echo JLayoutHelper::render('joomla.content.icons_blog', array('item' => $this->item, 'params' => $params, 'print' => $this->print)); ?>
		  <?php endif; ?>
		</aside>  
		<?php endif; ?>
		<!-- //Aside -->
	</div>
	<div class="media-body">
		<?php if ($params->get('show_title')) : ?>
			<?php echo JLayoutHelper::render('joomla.content.item_title', array('item' => $this->item, 'params' => $params, 'title-tag'=>'h1')); ?>
		<?php endif; ?>
		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
		<?php endif; ?>
		 <!-- footer -->
		  <?php if ($botInfo) : ?>
		  <footer class="article-footer clearfix">
		    <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
		  </footer>
		  <?php endif; ?>
		  <!-- //footer -->

		<?php if (isset ($this->item->toc)) : ?>
			<?php echo $this->item->toc; ?>
		<?php endif; ?>

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

		<?php if (!$params->get('show_intro')) : ?>
			<?php echo $this->item->event->afterDisplayTitle; ?>
		<?php endif; ?>

		<?php echo $this->item->event->beforeDisplayContent; ?>

		<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position))) || (empty($urls->urls_position) && (!$params->get('urls_position')))): ?>
			<?php echo $this->loadTemplate('links'); ?>
		<?php endif; ?>

		<?php	if ($params->get('access-view')): ?>

			<?php echo JLayoutHelper::render('joomla.content.fulltext_image', array('item' => $this->item, 'params' => $params)); ?>

			<?php	if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative):
				echo $this->item->pagination;
			endif; ?>

			<section class="article-content clearfix" itemprop="articleBody">
				<?php echo $this->item->text; ?>
			</section>

		 

			<?php
			if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative): ?>
				<?php
				echo '<hr class="divider-vertical" />';
				echo $this->item->pagination;
				?>
			<?php endif; ?>

			<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))): ?>
				<?php echo $this->loadTemplate('links'); ?>
			<?php endif; ?>

			<?php //optional teaser intro text for guests ?>
		<?php elseif ($params->get('show_noauth') == true and  $user->get('guest')) : ?>

			<?php echo $this->item->introtext; ?>
			<?php //Optional link to let them register to see the whole article. ?>
			<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
				$link1 = JRoute::_('index.php?option=com_users&view=login');
				$link = new JURI($link1);
				?>
				<section class="readmore">
					<a href="<?php echo $link; ?>" itemprop="url">
								<span>
								<?php $attribs = json_decode($this->item->attribs); ?>
								<?php
								if ($attribs->alternative_readmore == null) :
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
		<?php endif; ?>
		</div>
</div>


</article>
<!-- //Article -->

<?php if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative): ?>
	<?php echo $this->item->pagination; ?>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
</div>