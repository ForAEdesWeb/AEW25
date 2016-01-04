<?php
/**
 * ------------------------------------------------------------------------
 * JA Bookmark plugin for J2.5 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

/**
 * JA Bookmark Plugin is a module using for Display icons for your online social networking sites.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>
<span id="jabookmark-container" style="display: none"
	class="ja-social-bookmarking<?php
echo $this->params->get('class_sfx')?>">
	<?php
echo $content_layout;
?>
</span>
