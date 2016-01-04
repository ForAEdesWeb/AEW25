<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->countModules('section-1')) : ?>
	<!-- SECTIONS -->
	<div class="wrap t3-section t3-section-1 <?php $this->_c('section-1') ?>">
			<jdoc:include type="modules" name="<?php $this->_p('section-1') ?>" style="raw" />
	</div>
	<!-- //SECTIONS -->
<?php endif ?>