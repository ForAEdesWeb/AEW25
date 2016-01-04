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

<?php if ($this->countModules('submenu')) : ?>
	<div class="wrap ja-submenu <?php $this->_c('submenu') ?>">
		<div class="container">
			<jdoc:include type="modules" name="<?php $this->_p('submenu') ?>" style="raw" />
		</div>
	</div>
<?php endif ?>
