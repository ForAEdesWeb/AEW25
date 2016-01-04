<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->countModules('countdown')) : ?>
<div class="wrap ja-countdown-page"><div id="countdown-page-inner">
	<div class="container">
		<div class="row">
			<div class="span12">
			  <div class="countdown-page">
					<jdoc:include type="modules" name="<?php $this->_p('countdown') ?>" style="raw" />
			  </div>
			</div>
	  </div>
	</div>
</div></div>
<?php endif ?>