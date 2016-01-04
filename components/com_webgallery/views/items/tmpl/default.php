<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_webgallery
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
WebgalleryHelper::_('include.bootstrap', true);

$user   = JFactory::getUser();
$userId = $user->get('id');

$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canOrder   = $user->authorise('core.edit.state', 'com_webgallery');
$saveOrder  = $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_webgallery&view=items'); ?>" method="post" name="adminForm" id="adminForm">
    
    <div id="webgallery-wrap" class="windwalker list container-fluid items<?php echo $this->get('pageclass_sfx');?>">
        <div id="webgallery-wrap-inner">
            
            <!-- Heading -->
            <?php if ($this->params->get('show_page_heading', 1)) : ?>
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
            <?php endif; ?>
        
            <?php if ($this->params->get('show_category_title') or $this->params->get('page_subheading')) : ?>
            <h2>
                <?php echo $this->escape($this->params->get('page_subheading')); ?>
                <?php if ($this->params->get('show_category_title')) : ?>
                    <span class="subheading-category"><?php echo $this->category->title;?></span>
                <?php endif; ?>
            </h2>
            <?php endif; ?>
            <!-- Heading End -->
            
            
            
            <!-- Category Description -->
            <?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
                <div class="category-desc">
                <?php if ($this->params->get('show_description_image') && $this->category->params->get('image')) : ?>
                    <img src="<?php echo $this->category->params()->get('image'); ?>"/>
                <?php endif; ?>
                <?php if ($this->params->get('show_description') && $this->category->description) : ?>
                    <?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_webgallery.category'); ?>
                <?php endif; ?>
                <div class="clr"></div>
                </div>
            <?php endif; ?>
            <!-- Category Description End -->
            
            
            
            <!-- Items List -->
            <div id="items-wrap">
                
                
                <!--Lead items-->
                <?php if (!empty($this->lead_items)) : ?>
                
                    <?php foreach ($this->lead_items as &$item) : ?>
                    <div class="row-fluid">
                        <div class="item span12<?php echo $item->a_published == 0 ? ' well well-small' : null; ?>">
                            <?php
                                $this->item = &$item;
                                echo $this->loadTemplate('item');
                            ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                
                <?php endif; ?>
                <!--Lead items End-->
                
                
                
                <!--Columns-->
                <?php if (!empty($this->intro_items)) : ?>
                    
                    <?php
                        $counter   = 1 + $this->columns;
                        $span      = round( 12 / $this->columns );
                        $total     = count($this->intro_items) ;
                    ?>
                    
                    <?php foreach ($this->intro_items as $key => &$item) : ?>
                        
                        <?php $rowcount = $this->columns == 1 ? 1 : $counter % $this->columns; ?>
                        
                        <?php if ( $rowcount == 1 ) : ?>
                        <!-- LINE -->
                        <div class="row-fluid">
                        <?php endif; ?>
                        
                            <div class="item span<?php echo $span; ?><?php echo $item->a_published == 0 ? ' well well-small' : null; ?>">
                                <?php
                                    $this->item = &$item;
                                    echo $this->loadTemplate('item');
                                ?>
                            </div>
                    
                        <?php if ( $rowcount == 0 || $this->columns == 1 || ($counter == $total + $this->columns) ): ?>
                            <span class="row-separator"></span>
                        </div>
                        <!-- LINE END -->
            
                        <?php endif; ?>
                        
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                
                <?php endif; ?>
                <!--Columns End-->
                
                
                
                <!--Links-->
                <?php if (!empty($this->link_items)) : ?>
                
                    <?php echo $this->loadTemplate('links'); ?>
                
                <?php endif; ?>
                <!--Links End-->
                
                
                
                <!--Pagination-->
                <?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
                    <div class="pagination">
                        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                        <p class="counter">
                                <?php echo $this->pagination->getPagesCounter(); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php echo $this->pagination->getPagesLinks(); ?>
                    </div>
                <?php  endif; ?>
                <!--Pagination End-->
                
                
                
            </div>
            <!-- Items List End -->
            
        </div>
    </div>
    
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <input type="hidden" name="return" id="return_url" value="<?php echo base64_encode(JFactory::getURI()->toString()); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    
</form>