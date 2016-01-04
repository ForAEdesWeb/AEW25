<?php
/**
 * @version $Id: router.php 272 2014-05-21 10:25:49Z michal $
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

defined('_JEXEC') or die;

require_once(JPATH_BASE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_djcatalog2'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'route.php');

function DJCatalog2BuildRoute(&$query)
{
    $segments = array();
    
    $app        = JFactory::getApplication();
    $menu       = $app->getMenu('site');
    
    $params = JComponentHelper::getParams( 'com_djcatalog2' );
    $component_views = array('items' => 'items', 'item' => 'item',
    		'producer' => 'producer', 'myitems' => 'myitems',
    		'itemform' => 'itemform', 'producers' => 'producers',
    		'cart'=>'cart', 'orders'=>'orders', 'order'=>'order', 'checkout'=>'checkout', 'query' => 'query');
    foreach($component_views as $view_name => $seotag) {
    	$alias = $params->get('seo_'.$view_name.'_view', $view_name);
    	$alias = JApplication::stringURLSafe($alias);
    	if(trim(str_replace('-','',$alias)) != '') {
    		$component_views[$view_name] = trim($alias);
    	}
    } 
    
    $default_menu = $menu->getDefault();
	$menuItem = null;
	
    if (empty($query['Itemid'])) {
    	//JLog::add(' empty Itemid '.print_r($query, true));
    	unset($query['Itemid']);
        $menuItem = $menu->getActive();
    } else {
    	//JLog::add(' NOT empty Itemid '.print_r($query, true));
        $menuItem = $menu->getItem($query['Itemid']);
    }
    
    //JLog::add(' menu Item '.@$menuItem->component.' '.print_r(@$menuItem->query,true).' -----------------------');
    
    //$option = (empty($menuItem->component)) ? null : $menuItem->component;
    
    $mView  = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
    $mCatid = (empty($menuItem->query['cid'])) ? null : (int)$menuItem->query['cid'];
    $mProdid   = (empty($menuItem->query['pid'])) ? null : (int)$menuItem->query['pid'];
    $mId    = (empty($menuItem->query['id'])) ? null : (int)$menuItem->query['id'];
    
    $view = !empty($query['view']) ? $query['view'] : null;
    $cid = !empty($query['cid']) ? $query['cid'] : null;
    $pid = !empty($query['pid']) ? $query['pid'] : null;
    $id = !empty($query['id']) ? $query['id'] : null;
    
    $task = !empty($query['task']) ? $query['task'] : null;
    
    // JoomSEF bug workaround
    if (isset($query['start']) && isset($query['limitstart'])) {
    	if ((int)$query['limitstart'] != (int)$query['start'] && (int)$query['start'] > 0) {
    		// let's make it clear - 'limitstart' has higher priority than 'start' parameter, 
    		// however ARTIO JoomSEF doesn't seem to respect that.
    		$query['start'] = $query['limitstart'];
    		unset($query['limitstart']);
    	}
    }
    // JoomSEF workaround - end
    //if ($view && $option == 'com_djcatalog2') {
    if ($view) {
        if ($view != $mView || empty($query['Itemid'])) {
            $segments[] = $view;
        }
        
        unset($query['view']);
        
    	if ($view == 'item') {
        	if ($view == $mView && intval($id) > 0 && intval($id) == $mId) {
        		unset($query['id']);
        		unset($query['cid']);
        	} else if (($mView == 'items' || $mView === null) && intval($id) > 0) {
        		if (intval($cid) != intval($mCatid)) {
        			$segments[] = (int)$cid == 0 ? 'all' : DJCatalogHelperRoute::formatAlias($cid);
        		}
        		$segments[] = DJCatalogHelperRoute::formatAlias($id);
        		unset($query['id']);
        		unset($query['cid']);
        	}
        }
        
        if ($view == 'items') {
        	if ($cid === null) {
        		$cid = '0';
        	}
            if (intval($cid) != intval($mCatid) /*|| $mCatid === null*/) {
				$segments[] = (int)$cid == 0 ? 'all' : DJCatalogHelperRoute::formatAlias($cid);
			} else if ($menuItem->id == $default_menu->id && (int)$cid == 0 && (isset($query['cm']) || isset($query['ind']) || (int)$mProdid != (int)$pid) ) {
            	$segments[] = 'all';
            }
            unset($query['cid']);
            
            if ( (isset($query['pid']) && $query['pid'] === '') ||  (intval($mProdid) == intval($pid) /*&& (intval($mProdid) > 0 || intval($pid) > 0)*/)) {
            	unset($query['pid']);
            }
        }
        
        if ($view == 'producer') {
        	if (!($view == $mView && intval($pid) > 0 && intval($pid) == $mProdid) && $mView != 'producer') {
        		$segments[] = DJCatalogHelperRoute::formatAlias($pid);
        	}
            unset($query['pid']);
        }
        
        if ($view == 'itemform') {
        	if (intval($id) > 0) {
        		$segments[] = $id;
        		unset($query['id']);
        	}
        }
    }
	
    if (!empty($segments[0]) && array_key_exists($segments[0], $component_views)) {
    	$segments[0] = $component_views[$segments[0]];
    }
    
    return $segments;
}

function DJCatalog2ParseRoute($segments) {
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$activemenu = $menu->getActive();
	$db = JFactory::getDBO();
	$params = JComponentHelper::getParams( 'com_djcatalog2' );
	
	$catalogViews = array('item', 'items', 'producer', 'itemform', 'myitems', 'producers', 'cart', 'checkout', 'orders', 'order', 'query');
	$component_views = array('items' => 'items', 'item' => 'item',
			'producer' => 'producer', 'myitems' => 'myitems',
			'itemform' => 'itemform', 'producers' => 'producers',
			'cart'=>'cart', 'orders'=>'orders', 'order'=>'order', 'checkout'=>'checkout', 'query'=>'query');
	
	foreach($component_views as $view_name => $seotag) {
		$view_alias = $params->get('seo_'.$view_name.'_view', $view_name);
		$view_alias = JApplication::stringURLSafe(trim($view_alias));
		if (count($segments)) {
			if ($segments[0] == $view_alias || str_replace(':', '-', $segments[0]) == $view_alias) {
				$segments[0] = $view_name;
				break;
			}
		}
	}
	
	$query=array();
	if (count($segments)) {
		if (!in_array($segments[0], $catalogViews)) {
            if ($activemenu) {
                $temp=array();
                $temp[0] = $activemenu->query['view'];
                switch ($temp[0]) {
                	case 'item' : {
                        $temp[1] = @$activemenu->query['id'];
                        foreach ($segments as $k=>$v) {
                            $temp[$k+1] = $v;
                        }
                        break;
                    }
                    case 'items' : {
                        $temp[1] = @$activemenu->query['cid'];
                        foreach ($segments as $k=>$v) {
                            $temp[$k+1] = $v;
                        }
                        break;
                    }
                	case 'producer' : {
                        $temp[1] = @$activemenu->query['pid'];
                        foreach ($segments as $k=>$v) {
                            $temp[$k+1] = $v;
                        }
                        break;
                    }
                    case 'myitems' : {
                    	//$temp[1] = @$activemenu->query['id'];
                    	foreach ($segments as $k=>$v) {
                    		$temp[$k+1] = $v;
                    	}
                    	break;
                    }
                    case 'producers' : {
                    	foreach ($segments as $k=>$v) {
                    		$temp[$k+1] = $v;
                    	}
                    	break;
                    }
                    case 'itemform' : {
                    	foreach ($segments as $k=>$v) {
                    		$temp[$k+1] = $v;
                    	}
                    	break;
                    }
                    case 'cart' :
                    case 'checkout' :
                    case 'query' :
                    case 'orders' :
                    case 'order' : {
                    	foreach ($segments as $k=>$v) {
                    		$temp[$k+1] = $v;
                    	}
                    	break;
                    }
                }
                
                $segments = $temp;
            }
        } 
		if (isset($segments[0])) {
			switch($segments[0]) {
				case 'items': {
					$query['view'] = 'items';
					if (isset($segments[1])) {
						$query['cid']=($segments[1] == 'all') ? 0 : DJCatalogHelperRoute::parseAlias($segments[1]);
					} 
					break;
				}
				case 'itemstable': {
					$query['view'] = 'itemstable';
					if (isset($segments[1])) {
						$query['cid']=($segments[1] == 'all') ? 0 :DJCatalogHelperRoute::parseAlias($segments[1]);
					} 
					break;
				}
				case 'item': {
					$query['view'] = 'item';
					
					if (count($segments) > 2) {
						if (isset($segments[1])) {
							$query['cid']=($segments[1] == 'all') ? 0 : DJCatalogHelperRoute::parseAlias($segments[1]);
						}
						if (isset($segments[2])) {
							$query['id']= DJCatalogHelperRoute::parseAlias($segments[2]);
						}  
					} else if (isset($segments[1])) {
						$query['id']=  DJCatalogHelperRoute::parseAlias($segments[1]);
						if ($activemenu && $activemenu->query['option'] == 'com_djcatalog2' && $activemenu->query['view'] == 'items' && !empty($activemenu->query['cid'])) {
							$query['cid'] = $activemenu->query['cid'];
						}
					}
					break;
				}
				case 'producer': {
					$query['view'] = 'producer';
					if (isset($segments[1])) {
						$query['pid']=$segments[1];
					}  
					break;
				}
				case 'itemform': {
					$query['view'] = 'itemform';
					if (isset($segments[1])) {
						$query['id']=$segments[1];
					}
					break;
				}
				case 'myitems': {
					$query['view'] = 'myitems';
					break;
				}
				case 'producers': {
					$query['view'] = 'producers';
					break;
				}
				case 'cart': {
					$query['view'] = 'cart';
					break;
				}
				case 'orders': {
					$query['view'] = 'orders';
					break;
				}
				case 'checkout': {
					$query['view'] = 'checkout';
					break;
				}
				case 'query': {
					$query['view'] = 'query';
					break;
				}
				case 'order': {
					$query['view'] = 'order';
					if (isset($segments[1])) {
						$query['oid']=$segments[1];
					}
					break;
				}
			}
		}
	}
	
	return $query;
}
