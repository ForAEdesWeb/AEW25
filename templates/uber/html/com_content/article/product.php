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

JLoader::register('JATempHelper',T3_TEMPLATE_PATH.'/templateHelper.php');

$extraContent = JATempHelper::loadParamsContents($this->item);

$title_caption = $extraContent['title_caption'];

$live_demo = $extraContent['live_demo'];

$download = $extraContent['download'];

$price = $extraContent['price'];

$version = $extraContent['version'];

$requirement = $extraContent['requirement'];

$bugtracker = $extraContent['bugtracker'];

$lastupdate = $extraContent['lastupdate'];

$document = $extraContent['document'];

$blogreview = $extraContent['blogreview'];
?>

<div class="item-page<?php echo $this->pageclass_sfx ?> clearfix">

<?php if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative) : ?>
	<?php echo $this->item->pagination; ?>
<?php endif; ?>

<!-- Article -->
<article itemscope itemtype="http://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />
	
	<header class="page-header product-header">
	 <div class="container">
			
			<?php if ($params->get('show_title')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.item_title', array('item' => $this->item, 'params' => $params, 'title-tag'=>'h1')); ?>
			<?php endif; ?>
			
			<?php if($title_caption): echo '<p class="product-tagline blockline">'.$title_caption.'</p>'; endif; ?>
			
			<?php if ($this->item->introtext): 
				$text = preg_replace("/<img[^>]+\>/i", " ", $this->item->introtext);
	
				echo '<div class="container-sm product-desc">'.$text.'</div>'; 
			
			endif; ?>
			
			<?php if($live_demo || $download): ?>
			<div class="product-cta">
	      <nav class="product-actions">
	      	
	      	<?php if($live_demo): ?>
					<a href="<?php echo $live_demo; ?>" class="btn btn-lg btn-default">
							<span>Live Demo</span> <i class="fa fa-eye"></i>
					</a>
					<?php endif; ?>
					
					<?php if($download): ?>
					<a href="<?php echo $download; ?>" class="btn btn-primary btn-lg">
						<span>Purchase</span> <i><span class="edd_price"><?php echo $price; ?></span></i>
					</a>
					<?php endif; ?>
				</nav>
	    </div>
	    <?php endif; ?>
	    
	    <div class="product-carousel">
	    	<?php	echo JATempHelper::ProductSlideImage($this->item->introtext); ?>
	    </div>
	    
		</div>
	</header>
	
	<section class="product-detail" itemprop="articleBody">
		<nav class="product-nav">
	    <div class="container">
	    	<ul class="nav nav-tabs" role="tablist">
				  <li class="active"><a href="#product-details" role="tab" data-toggle="tab">Details</a></li>
				  <li><a href="#product-comments" role="tab" data-toggle="tab">Comments</a></li>
				</ul>
				
				<div class="ja-bookmark pull-right">
					<?php echo $this->item->event->afterDisplayTitle; ?>
				</div>
	    </div>
		</nav>
		<div class="container">
			<!-- Tab panes -->
			<div class="tab-content">
			  <div class="tab-pane fade in active" id="product-details">
					<div class="detail-section quick-info">
						<div class="content">
					  	<h2 class="sr-only">Quick Info</h2>
	
					    <?php if($price): ?>
					   	<dl class="pricing">
					      <dt>Price</dt>
					      <dd><span class="edd_price"><?php echo $price; ?></span></dd>
					    </dl>
					    <?php endif; ?>
					    
					    <dl>
					      <dt>Rating</dt>
					      <dd>
					         <?php
		                if (isset($this->item->rating_sum) && $this->item->rating_count > 0) {
		                  $this->item->rating = round($this->item->rating_sum / $this->item->rating_count, 1);
		                  $this->item->rating_percentage = $this->item->rating_sum / $this->item->rating_count * 20;
		                } else {
		                  if (!isset($this->item->rating)) $this->item->rating = 0;
		                  if (!isset($this->item->rating_count)) $this->item->rating_count = 0;
		                  $this->item->rating_percentage = $this->item->rating * 20;
		                }
		                $uri = JUri::getInstance();
		                
		                ?>
		                <div itemtype="http://schema.org/AggregateRating" itemscope itemprop="aggregateRating" class="rating-info pd-rating-info">
		                  <form class="rating-form" method="POST" action="<?php echo htmlspecialchars($uri->toString()) ?>">
		                    <ul class="rating-list">
		                      <li class="rating-current" style="width:<?php echo $this->item->rating_percentage; ?>%;"></li>
		                      <li><a href="#" title="<?php echo JText::_('JA_1_STAR_OUT_OF_5'); ?>" class="one-star">1</a></li>
		                      <li><a href="#" title="<?php echo JText::_('JA_2_STARS_OUT_OF_5'); ?>" class="two-stars">2</a></li>
		                      <li><a href="#" title="<?php echo JText::_('JA_3_STARS_OUT_OF_5'); ?>" class="three-stars">3</a></li>
		                      <li><a href="#" title="<?php echo JText::_('JA_4_STARS_OUT_OF_5'); ?>" class="four-stars">4</a></li>
		                      <li><a href="#" title="<?php echo JText::_('JA_5_STARS_OUT_OF_5'); ?>" class="five-stars">5</a></li>
		                    </ul>
		                    <div class="rating-log">(<meta itemprop="bestRating" content="5" /><span itemprop="ratingValue"><?php echo $this->item->rating ?></span> / <span itemprop="ratingCount"><?php echo $this->item->rating_count; ?></span> votes)</div>
		                    <input type="hidden" name="task" value="article.vote" />
		                    <input type="hidden" name="hitcount" value="0" />
		                    <input type="hidden" name="user_rating" value="5" />
		                    <input type="hidden" name="url" value="<?php echo htmlspecialchars($uri->toString()) ?>" />
		                    <?php echo JHtml::_('form.token') ?>
		                  </form>
		                </div>
		                <!-- //Rating -->
		
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
					      </dd>
					    </dl>
					    
					    <?php if($version): ?>
					    <dl>
					      <dt>Version</dt>
					      <dd>
					        <?php echo $version; ?> <?php if($bugtracker): ?><a href="<?php echo $bugtracker; ?>" target="_blank" title="Changelog"><i class="fa fa-info-circle"></i></a> <?php endif; ?>
								</dd>
					    </dl>
					    <?php endif; ?>
					    
					    <?php if($lastupdate): ?>
					    <dl>
					      <dt>Last Update</dt>
					      <dd><?php echo $lastupdate; ?></dd>
					    </dl>
					    <?php endif; ?>
					    
					    <?php if($requirement): ?>
					    <dl>
					      <dt>Requirements</dt>
					      <dd><?php echo $requirement; ?></dd>
					    </dl>
					    <?php endif; ?>
					  </div>
					</div>
			  	
					<?php echo $this->item->text; ?>
				</div>
			  <div class="tab-pane fade" id="product-comments">
						<?php echo $this->item->event->afterDisplayContent; ?>
				</div>
			</div>
			
		</div>
	</section>

</article>
<!-- //Article -->

<?php if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative): ?>
	<?php echo $this->item->pagination; ?>
<?php endif; ?>

<!-- Load Modules with position "product-banner" -->
<?php 
		$ads_modules = 'product-banner';
		$attrs = array();
		$result = null;
		$renderer = JFactory::getDocument()->loadRenderer('modules');
		$ads = $renderer->render($ads_modules, $attrs, $result);
?>

<?php if($ads): ?>
<div class="container">
	<div class="row row-detail row-detail-banner">
  	<div class="col-md-12 img">
    	<?php echo $ads; ?>
  	</div>
	</div>
</div>
<?php endif;?>
<!-- End load -->

<!-- Load Modules with position "product-related" -->
<div class="sections-wrap text-center">
<?php if(JATempHelper::loadmodules('product-related','T3section')): ?>
    <?php echo JATempHelper::loadmodules('product-related','T3section'); ?>
<?php endif;?>
</div>
<!-- End load -->

<!-- Load Modules with position "section" -->
<div class="sections-wrap text-center">
<?php if(JATempHelper::loadmodules('section','T3section')): ?>
    <?php echo JATempHelper::loadmodules('section','T3section'); ?>
<?php endif;?>
</div>
<!-- End load -->

<section data-offset-top="600" data-spy="affix" class="section-bottom-bar hidden-xs">
      
  <div class="pull-left">
  	<?php if($document): ?>
    <a href="<?php echo $document; ?>" class="btn btn-default"><span>Documentation</span></a>
    <?php endif; ?>    
    
    <?php if($blogreview): ?>
    <a href="<?php echo $blogreview; ?>" class="btn btn-default"><span>Blog review</span></a>
    <?php endif; ?>  
  </div>

  <div class="current-product">
    <strong>You are viewing <span><?php echo $this->item->title; ?></span></strong>
  </div>

  <div class="pull-right">
  	<?php if($live_demo): ?>
    <a href="<?php echo $live_demo; ?>" class="btn btn-default btn-demo"><span>Live Demo</span> <i class="fa fa-eye"></i></a>
		<?php endif; ?>    	
		
		<?php if($download && $price): ?>
		<a href="<?php echo $download; ?>" class="btn btn-primary "><span>Purchase</span> <i class="hidden-xs"><span class="edd_price"><?php echo $price; ?></span></i></a>
		<?php endif; ?>    
  </div>
  
</section>

</div>