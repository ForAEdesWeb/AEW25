<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>


<?php if ($this->countModules('slideshow')) : ?>
<!-- SLIDESHOW -->
<div class="wrap ja-slideshow">
	<div class="container">
		<div class="row">
			<div class="left-slideshow span8<?php $this->_c('slideshow')?>">
			  <div class="main-slideshow">
				<jdoc:include type="modules" name="<?php $this->_p('slideshow') ?>" style="raw" />
			  </div>
			</div>
			<div class="right-slideshow span4">
			  <div class="main-siderbar">
				<jdoc:include type="modules" name="<?php $this->_p('slidenews') ?>" style="T3Xhtml" />
			  </div>
			</div>
	  </div>
	</div>
	
	
</div>
<!-- //SLIDESHOW -->
<?php endif ?>