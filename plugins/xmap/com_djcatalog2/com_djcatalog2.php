<?php
/**
 * @version $Id: com_djcatalog2.php 152 2013-10-08 13:28:02Z michal $
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

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once JPATH_SITE . DS . 'components' . DS . 'com_djcatalog2' . DS . 'helpers' . DS . 'route.php';
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_djcatalog2' . DS . 'lib' . DS . 'categories.php';

class xmap_com_djcatalog2
{
	static function getTree( $xmap, $parent, &$params )
	{
		if ($xmap->isNews)
		return false;

		$link_query = parse_url( $parent->link );
		parse_str( html_entity_decode($link_query['query']), $link_vars);
		
		$view = self::getParam($link_vars,'view');
		

		$include_products = self::getParam($params,'include_products',1);
		$include_products = ( $include_products == 1
		|| ( $include_products == 2 && $xmap->view == 'xml')
		|| ( $include_products == 3 && $xmap->view == 'html')
		||   $xmap->view == 'navigator');
		$params['include_products'] = $include_products;

		$priority = self::getParam($params,'cat_priority',$parent->priority);
		$changefreq = self::getParam($params,'cat_changefreq',$parent->changefreq);
		if ($priority  == '-1')
		$priority = $parent->priority;
		if ($changefreq  == '-1')
		$changefreq = $parent->changefreq;

		$params['cat_priority'] = $priority;
		$params['cat_changefreq'] = $changefreq;

		$priority = self::getParam($params,'link_priority',$parent->priority);
		$changefreq = self::getParam($params,'link_changefreq',$parent->changefreq);
		if ($priority  == '-1')
		$priority = $parent->priority;

		if ($changefreq  == '-1')
		$changefreq = $parent->changefreq;

		$params['link_priority'] = $priority;
		$params['link_changefreq'] = $changefreq;
		
		switch ($view) {
			case 'items' : {
				$catid = self::getParam($link_vars,'cid',0);
				return self::getDJCatalog2Category($xmap,$parent,$params,$catid);
				break;
			}
			case 'item' : {
				$itemid = self::getParam($link_vars,'id',0);
				if ($itemid > 0) {
					return self::getDJCatalog2Item($xmap,$parent,$params,$itemid);
				} else {
					return false;
				}
				break;
			}
			case 'producer' : {
				$producerid = self::getParam($link_vars,'pid',0);
				if ($producerid > 0) {
					return self::getDJCatalog2Producer($xmap,$parent,$params,$producerid);
				} else {
					return false;
				}
				break;
			}
		}

	}

	/* Returns URLs of all Categories and links in of one category using recursion */
	static function getDJCatalog2Category (&$xmap, &$parent, &$params, &$catid )
	{
		$database = JFactory::getDBO();

		$djc2categories = Djc2Categories::getInstance(array('state'=>'1'));
		$category = $djc2categories->get($catid);
		if (!$category) {
			return false;
		}
		$categories = $category->getChildren();
		$xmap->changeLevel(1);
		foreach($categories as $row) {
			if( !$row->created ) {
				$row->created = $xmap->now;
			}

			$node = new stdclass;
			$node->name = $row->name;
			$node->link = DJCatalogHelperRoute::getCategoryRoute($row->id.':'.$row->alias);
			$node->id = $parent->id;
			$node->uid = 'com_djcatalog2c'.$row->id;
			$node->browserNav = $parent->browserNav;
			$node->modified = $row->created;
			$node->priority = $params['cat_priority'];
			$node->changefreq = $params['cat_changefreq'];
			$node->expandible = true;
			$node->secure = $parent->secure;

			if ( $xmap->printNode($node) !== FALSE) {
				self::getDJCatalog2Category($xmap,$parent,$params,$row->id);
			}
		}

		/* Returns URLs of all listings in the current category */
		if ($params['include_products']) {
			$query = " SELECT a.name, a.alias, a.id, a.cat_id, c.alias as cat_alias, UNIX_TIMESTAMP(a.created) as `created` \n".
                 " FROM #__djc2_items AS a \n".
            	 " INNER JOIN #__djc2_categories as c on c.id = a.cat_id".
                 " WHERE a.cat_id = ".(int)$catid.
                 " AND a.published=1" .
                 " ORDER BY a.ordering ASC, a.name ASC ";

			$database->setQuery($query);

			$rows = $database->loadObjectList();

			foreach($rows as $row) {
				$node = new stdclass;
				$node->name = $row->name;
				$node->link = DJCatalogHelperRoute::getItemRoute($row->id.':'.$row->alias, $row->cat_id.':'.$row->cat_alias);
				$node->id = $parent->id;
				$node->uid = 'com_djcatalog2i'.$row->id;
				$node->browserNav = $parent->browserNav;
				$node->modified = ($row->created);
				$node->priority = $params['link_priority'];
				$node->changefreq = $params['link_changefreq'];
				$node->expandible = false;
				$node->secure = $parent->secure;
				$xmap->printNode($node);
			}
		}
		$xmap->changeLevel(-1);
		return true;
	}
	
	static function getDJCatalog2Item (&$xmap, &$parent, &$params, &$itemid )
	{
		$database = JFactory::getDBO();
		$query = " SELECT a.name, a.alias, a.id, a.cat_id, c.alias as cat_alias, UNIX_TIMESTAMP(a.created) as `created` \n".
                 " FROM #__djc2_items AS a \n".
            	 " INNER JOIN #__djc2_categories as c on c.id = a.cat_id".
                 " WHERE a.id = ".(int)$itemid.
                 " AND a.published=1"
                 ;

		$database->setQuery($query);

		$row = $database->loadObject();
		$node = new stdclass;
		$node->name = $row->name;
		$node->link = DJCatalogHelperRoute::getItemRoute($row->id.':'.$row->alias, $row->cat_id.':'.$row->cat_alias);
		$node->id = $parent->id;
		$node->uid = 'com_djcatalog2i'.$row->id;
		$node->browserNav = $parent->browserNav;
		$node->modified = ($row->created);
		$node->priority = $params['link_priority'];
		$node->changefreq = $params['link_changefreq'];
		$node->expandible = false;
		$node->secure = $parent->secure;
		$xmap->printNode($node);
		
		return true;
	}
	
	static function getDJCatalog2Producer (&$xmap, &$parent, &$params, &$producerid )
	{
		$database = JFactory::getDBO();
		$query = " SELECT a.name, a.alias, a.id, UNIX_TIMESTAMP(a.created) as `created` \n".
                 " FROM #__djc2_producers AS a \n".
                 " WHERE a.id = ".(int)$producerid.
                 " AND a.published=1"
                 ;

		$database->setQuery($query);

		$row = $database->loadObject();
		$node = new stdclass;
		$node->name = $row->name;
		$node->link = DJCatalogHelperRoute::getProducerRoute($row->id.':'.$row->alias);
		$node->id = $parent->id;
		$node->uid = 'com_djcatalog2p'.$row->id;
		$node->browserNav = $parent->browserNav;
		$node->modified = ($row->created);
		$node->priority = $params['link_priority'];
		$node->changefreq = $params['link_changefreq'];
		$node->expandible = false;
		$node->secure = $parent->secure;
		$xmap->printNode($node);
		
		return true;
	}

	static function prepareMenuItem($node, &$params)
    {
        $db = JFactory::getDbo();
        $link_query = parse_url($node->link);
        if (!isset($link_query['query'])) {
            return;
        }

        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $view = JArrayHelper::getValue($link_vars, 'view', '');
        $layout = JArrayHelper::getValue($link_vars, 'layout', '');

        switch ($view) {
            case 'items':
            	$id = JArrayHelper::getValue($link_vars, 'cid', 0);
                $node->uid = 'com_djcatalog2c' . $id;
                $node->expandible = true;
                break;
            case 'item':
            	$id = JArrayHelper::getValue($link_vars, 'id', 0);
                $node->uid = 'com_djcatalog2i' . $id;
                $node->expandible = false;
                break;
            case 'producer':
            	$id = JArrayHelper::getValue($link_vars, 'pid', 0);
                $node->expandible = false;
                break;
        }
    }
	
	static function getParam($arr, $name, $def=null)
	{
		$var = JArrayHelper::getValue( $arr, $name, $def, '' );
		return $var;
	}
}
