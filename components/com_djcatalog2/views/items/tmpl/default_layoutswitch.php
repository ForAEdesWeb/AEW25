<?php
/**
 * @version $Id: default_layoutswitch.php 265 2014-03-17 08:27:32Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined ('_JEXEC') or die('Restricted access');
?>
<?php

JURI::reset();

$app = JFactory::getApplication();
$menu = $app->getMenu();
$juri = JURI::getInstance();
$uri = JURI::getInstance($juri->toString());
$query = $uri->getQuery(true);
$active = $app->input->get('l',$this->params->get('list_layout','items'));
unset($query['l']);

$query['option'] = 'com_djcatalog2';
$query['view'] = 'items';
$query['Itemid'] = $menu->getActive() ? $menu->getActive()->id : null;
$cid = $app->input->get('cid', false, 'string');
$pid = $app->input->get('pid', false, 'string');

if ($cid) {
	$query['cid'] = $cid;
}
if ($pid) {
	$query['pid'] = $pid;
}

$uri->setQuery($query);

$layoutUrl = 'index.php?'.$uri->getQuery(false);

/*
$layoutUrl = htmlspecialchars($uri->toString());

if (strpos($layoutUrl,'?') === false ) {
$layoutUrl .= '?';
} else {
$layoutUrl .= '&amp;';
}
*/

JURI::reset();

?>
<div class="djc_layout_switch_in">
    <ul class="djc_layout_buttons djc_clearfix btn-group">
		<li><a class="btn<?php if ($active == 'items') echo ' active'; ?>" href="<?php echo JRoute::_( $layoutUrl.'&l=items'); ?>" title="<?php echo JText::_('COM_DJCATALOG2_GRID_LAYOUT'); ?>"><img src="<?php echo DJCatalog2ThemeHelper::getThemeImage('grid.png');?>" alt="<?php echo JText::_('COM_DJCATALOG2_GRID_LAYOUT'); ?>" /></a></li>
		<li><a class="btn<?php if ($active == 'table') echo ' active'; ?>" href="<?php echo JRoute::_( $layoutUrl.'&l=table'); ?>" title="<?php echo JText::_('COM_DJCATALOG2_TABLE_LAYOUT'); ?>"><img src="<?php echo DJCatalog2ThemeHelper::getThemeImage('table.png');?>" alt="<?php echo JText::_('COM_DJCATALOG2_TABLE_LAYOUT'); ?>" /></a></li>
	</ul>
</div>
