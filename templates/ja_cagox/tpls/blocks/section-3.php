<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->countModules('section')) : ?>
	<!-- SECTIONS -->
	<nav class="wrap t3-section <?php $this->_c('section') ?>">
		<div class="container">
			<jdoc:include type="modules" name="<?php $this->_p('section') ?>" style="xHtml" />
		</div>
	</nav>
	<!-- //SECTIONS -->
<?php endif ?>