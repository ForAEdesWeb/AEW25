<?php
/**
 * @version $Id: djcatalog2.php 272 2014-05-21 10:25:49Z michal $
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

// No direct access
defined('_JEXEC') or die;

class Djcatalog2Helper
{
	public static function addSubmenu($vName = 'cpanel')
	{
		$app = JFactory::getApplication();
		$version = new JVersion;
		$catalog_views = array('cpanel', 'items', 'categories', 'producers', 'fieldgroups', 'fields', 'thumbs','import');
		$customer_views = array('customers', 'customergroups');
		$order_views = array('orders');
		$price_views = array('prices','taxrates','taxrules');

		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_CPANEL'), 'index.php?option=com_djcatalog2&view=cpanel', $vName=='cpanel');
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_ITEMS'), 'index.php?option=com_djcatalog2&view=items', $vName=='items');
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_CATEGORIES'), 'index.php?option=com_djcatalog2&view=categories', $vName=='categories');
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_PRODUCERS'), 'index.php?option=com_djcatalog2&view=producers', $vName=='producers');
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_FIELDGROUPS'), 'index.php?option=com_djcatalog2&view=fieldgroups', $vName=='fieldgroups');
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_FIELDS'), 'index.php?option=com_djcatalog2&view=fields', $vName=='fields');
			
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_CUSTOMERS'), 'index.php?option=com_djcatalog2&view=customers', $vName=='customers');
			if (in_array($vName, $customer_views)) {
				//JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_CUSTOMERGROUPS'), 'index.php?option=com_djcatalog2&view=customergroups', $vName=='customergroups');
			}
				
			//JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_PRICES'), 'index.php?option=com_djcatalog2&view=prices', $vName=='prices');
			if (in_array($vName, $price_views)) {
				//JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_TAX_RATES'), 'index.php?option=com_djcatalog2&view=taxrates', $vName=='taxrates');
				//JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_TAX_RULES'), 'index.php?option=com_djcatalog2&view=taxrules&tax_rate_id='.$app->input->get('tax_rate_id'), $vName=='taxrules');
			}
				
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_COUNTRIES'), 'index.php?option=com_djcatalog2&view=countries', $vName=='countries');
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_QUERIES'), 'index.php?option=com_djcatalog2&view=queries', $vName=='queries');
			
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_IMAGES_MANAGER'), 'index.php?option=com_djcatalog2&view=thumbs', $vName=='thumbs');
			JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_IMPORT'), 'index.php?option=com_djcatalog2&view=import', $vName=='import');
			
			//JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_ORDERS'), 'index.php?option=com_djcatalog2&view=orders', $vName=='orders');
			
		} else {
			JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_CPANEL'), 'index.php?option=com_djcatalog2&view=cpanel', $vName=='cpanel');
			
			JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_ITEMS'), 'index.php?option=com_djcatalog2&view=items', $vName=='items');
			//if (in_array($vName, $catalog_views)) {
				JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_CATEGORIES'), 'index.php?option=com_djcatalog2&view=categories', $vName=='categories');
				JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_PRODUCERS'), 'index.php?option=com_djcatalog2&view=producers', $vName=='producers');
				JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_FIELDGROUPS'), 'index.php?option=com_djcatalog2&view=fieldgroups', $vName=='fieldgroups');
				JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_FIELDS'), 'index.php?option=com_djcatalog2&view=fields', $vName=='fields');
			//}
			
			JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_QUERIES'), 'index.php?option=com_djcatalog2&view=queries', $vName=='queries');
			
			//JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_ORDERS'), 'index.php?option=com_djcatalog2&view=orders', $vName=='orders');
			//if (in_array($vName, $order_views)) {
				// todo
			//}
			
			JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_CUSTOMERS'), 'index.php?option=com_djcatalog2&view=customers', $vName=='customers');
			//if (in_array($vName, $customer_views)) {
				//JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_CUSTOMERGROUPS'), 'index.php?option=com_djcatalog2&view=customergroups', $vName=='customergroups');
			//}
			
			//JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_PRICES'), 'index.php?option=com_djcatalog2&view=prices', $vName=='prices');
			//if (in_array($vName, $price_views)) {
				//JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_TAX_RATES'), 'index.php?option=com_djcatalog2&view=taxrates', $vName=='taxrates');
				//JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_TAX_RULES'), 'index.php?option=com_djcatalog2&view=taxrules&tax_rate_id='.$app->input->get('tax_rate_id'), $vName=='taxrules');
			//}
			JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_COUNTRIES'), 'index.php?option=com_djcatalog2&view=countries', $vName=='countries');
			
			JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_IMAGES_MANAGER'), 'index.php?option=com_djcatalog2&view=thumbs', $vName=='thumbs');
			JHtmlSidebar::addEntry(JText::_('COM_DJCATALOG2_IMPORT'), 'index.php?option=com_djcatalog2&view=import', $vName=='import');
		}
	}

	public static function getActions($asset = null, $assetId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if ( !$asset) {
			$assetName = 'com_djcatalog2';
		} else if ($assetId != 0){
			$assetName = 'com_djcatalog2.'.$asset.$assetId;
		} else {
			$assetName = 'com_djcatalog2.'.$asset;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);
		
		$actions = array(
			'catalog2.admin','core.admin'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
