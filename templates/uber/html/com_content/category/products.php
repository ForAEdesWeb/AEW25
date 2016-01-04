<?php
/**
 * ------------------------------------------------------------------------
 * Uber Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::addIncludePath(T3_PATH.'/html/com_content');
JHtml::addIncludePath(dirname(dirname(__FILE__)));
JHtml::_('behavior.caption');

//register the helper class
JLoader::register('JATempHelper', T3_TEMPLATE_PATH . '/templateHelper.php');

$menu = JFactory::getApplication()->getMenu();
$active = $menu->getActive() ? $menu->getActive() : $menu->getDefault();
$params = $this->params;

?>

 <?php if ($this->params->get('show_page_heading', 1)) : ?>
  <div class="page-header clearfix">
    <h1 class="page-title"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
  </div>
  <?php endif; ?>
	
	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1) || $this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
	<div class="category-desc clearfix">
		
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
		<?php endif; ?>
		
		<?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
			<div class="page-subheader clearfix">
				<h2 class="page-subtitle"><?php echo $this->escape($this->params->get('page_subheading')); ?>
					<?php if ($this->params->get('show_category_title')) : ?>
					<small class="subheading-category"><?php echo $this->category->title;?></small>
					<?php endif; ?>
				</h2>
			</div>
		<?php endif; ?>
		
		<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<p><?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?></p>
		<?php endif; ?>
	</div>
	<?php endif; ?>

<div class="swiper-slide blog text-center clearfix <?php echo $this->pageclass_sfx;?>">
	<div class="item grid-sizer"></div>
	<?php if ($this->params->get('show_tags', 1) && !empty($this->category->tags->itemTags)) :
        $this->category->tagLayout = new JLayoutFile('joomla.content.tags');
        echo $this->category->tagLayout->render($this->category->tags->itemTags);
    endif; ?>
	
	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) :
        if ($this->params->get('show_no_articles', 1)) : ?>
			<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
		$introcount = (count($this->intro_items));
		$counter = 0;
		$numberColumn = $params->get('article_column',3);
	?>

	<?php if (!empty($this->intro_items)) : ?>
	<?php foreach ($this->intro_items as $key => &$item) : ?>
	  <?php
		$this->item = &$item;
		?>
		<div class="item col-xs-12 col-sm-<?php echo 12/$numberColumn; ?> <?php if($counter == $numberColumn): echo 'latest'; endif; ?>">
			<?php
			echo $this->loadTemplate('item');
			?>
		</div>
	<?php endforeach; ?>
	<?php endif; ?>

</div>

<?php echo JLayoutHelper::render('joomla.content.pagination', array('params'=>$this->params, 'pagination'=>$this->pagination)); ?>