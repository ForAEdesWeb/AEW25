<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->countModules('section-2')) : ?>
	<!-- SECTIONS -->
	<nav class="wrap t3-section t3-section-2 <?php $this->_c('section-2') ?>">
		<div class="container">
			<jdoc:include type="modules" name="<?php $this->_p('section-2') ?>" style="xHtml" />
		</div>
	</nav>
	<!-- //SECTIONS -->
<?php endif ?>