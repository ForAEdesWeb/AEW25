<?php
/**
 * @version $Id: order_status.php 272 2014-05-21 10:25:49Z michal $
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

defined('_JEXEC') or die('Restricted access');

$params = JComponentHelper::getParams('com_djcatalog2');
require_once JPATH_ROOT.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'route.php';
?>

<div style="width: 800px; margin: 0 auto">
<p>
<?php echo JText::sprintf('COM_DJCATALOG2_EMAIL_ORDER_STATUS_CLIENT_HEADER', 
		$data['firstname'], 
		JText::_('COM_DJCATALOG2_ORDER_STATUS_'.$data['status']),
		str_pad($data['order_number'], 5, '0', STR_PAD_LEFT)
		); ?>
</p>

<p>
<?php echo JText::_('COM_DJCATALOG2_EMAIL_ORDER_CLIENT_FOOTER'); ?>
<a href="<?php echo JUri::root(false).DJCatalogHelperRoute::getOrderRoute($data['id']); ?>">
<?php echo JText::_('COM_DJCATALOG2_EMAIL_ORDER_CLIENT_LINK');?></a>
</p>
</div>