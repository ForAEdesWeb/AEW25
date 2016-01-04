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

<?php if ($this->countModules('uber-hero')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-hero') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-hero') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-cta')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-cta') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-cta') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-testimonial')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-testimonial') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-testimonial') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-gallery')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-gallery') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-gallery') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-slideshow')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-slideshow') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-slideshow') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-container-slide')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-container-slide') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-container-slide') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-container-tabs')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-container-tabs') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-container-tabs') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-pricingtable')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-pricingtable') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-pricingtable') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-featuresintro')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-featuresintro') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-featuresintro') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-events')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-events') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-events') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-teams')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-teams') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-teams') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-stats')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-stats') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-stats') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-contact-info')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-contact-info') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-contact-info') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-menu')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-menu') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-menu') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-clients')) : ?>
<!-- HOME POSITION -->
<div class="wrap sections-wrap <?php $this->_c('uber-clients') ?>">
	<jdoc:include type="modules" name="<?php $this->_p('uber-clients') ?>" style="T3section" />
</div>
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-bar')) : ?>
<!-- HOME POSITION -->
	<jdoc:include type="modules" name="<?php $this->_p('uber-bar') ?>" style="raw" />
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-header')) : ?>
<!-- HOME POSITION -->
	<jdoc:include type="modules" name="<?php $this->_p('uber-header') ?>" style="raw" />
<!-- //HOME POSITION -->
<?php endif ?>

<?php if ($this->countModules('uber-footer')) : ?>
<!-- HOME POSITION -->
	<jdoc:include type="modules" name="<?php $this->_p('uber-footer') ?>" style="raw" />
<!-- //HOME POSITION -->
<?php endif ?>