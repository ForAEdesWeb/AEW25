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
?>
<?php
	if (!$this->getParam('addon_offcanvas_enable')) return ;	
?>

<button class="btn btn-primary off-canvas-toggle <?php $this->_c('off-canvas') ?>" type="button" data-pos="right" data-nav="#t3-off-canvas" data-effect="<?php echo $this->getParam('addon_offcanvas_effect', 'off-canvas-effect-4') ?>">
  <span><?php echo JText::_( 'TPL_MENU' ); ?></span> <i class="fa fa-bars"></i>
</button>

<?php
	if (defined ('T3_OFF_CANVAS_SIDEBAR')) return ;
	define('T3_OFF_CANVAS_SIDEBAR', 1);
?>
<!-- OFF-CANVAS SIDEBAR -->
<div id="t3-off-canvas" class="t3-off-canvas <?php $this->_c('off-canvas') ?>">

  <div class="t3-off-canvas-header">
      
    <?php if ($this->countModules('head-search')) : ?>
      <!-- HEAD SEARCH -->
      <div class="head-search <?php $this->_c('head-search') ?>">
        <jdoc:include type="modules" name="<?php $this->_p('head-search') ?>" style="raw" />
      </div>
      <!-- //HEAD SEARCH -->
    <?php endif ?>
        
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  </div>

  <div class="t3-off-canvas-body">
    <jdoc:include type="modules" name="<?php $this->_p('off-canvas') ?>" style="T3Xhtml" />

		<?php if ($this->getParam('t3-rmvlogo', 1)): ?>
			<div class="poweredby text-hide">
				<a class="t3-logo t3-logo-light" href="http://t3-framework.org" title="<?php echo JText::_('T3_POWER_BY_TEXT') ?>"
				   target="_blank" <?php echo method_exists('T3', 'isHome') && T3::isHome() ? '' : 'rel="nofollow"' ?>><?php echo JText::_('T3_POWER_BY_HTML') ?></a>
			</div>
		<?php endif; ?>
  </div>

</div>
<!-- //OFF-CANVAS SIDEBAR -->
