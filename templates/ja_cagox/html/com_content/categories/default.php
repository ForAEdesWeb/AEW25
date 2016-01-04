<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');
?>
<?php
echo JLayoutHelper::render('joomla.content.categories_default', $this);
?>
<div class="categories-list row<?php echo $this->pageclass_sfx; ?>">
<?php
echo $this->loadTemplate('items');
?>
</div>
