<?php
/**
 * @version $Id: default.php 285 2014-06-13 10:03:52Z michal $
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

$app = JFactory::getApplication();
$menu = $app->getMenu();
$active = $menu->getActive();


$option = null;
$view = null;
$cid = 0;
$catalogView = false;

if (isset($active->query['option'])) {
    if ($active->query['option'] == 'com_djcatalog2' && $app->input->get('option', null, 'string') == 'com_djcatalog2') {
        if (isset($active->query['view'])) {
            if ($active->query['view'] == 'items' && $app->input->get('view', null, 'string') == 'items') {
                $catalogView = true;
                if ($app->input->get('cid', false, 'string') !== false) {
                	$cid = $app->input->get('cid', false, 'string');
                }
                else if (isset($active->query['cid'])) {
                    $cid = $active->query['cid'];
                }
            }
        } 
    }
}

JURI::reset();

$uri = null;
$juri = JURI::getInstance();
if (!$catalogView) {
    $uri = JURI::getInstance(JRoute::_(DJCatalogHelperRoute::getCategoryRoute($cid).'&cm=0'));
} else {
	if ($active->id == $menu->getDefault()->id) {
		$uri = JURI::getInstance($juri->toString());
		$temp_query = $uri->getQuery(true);
		$temp_query['cm'] = '0';
		$temp_query['option'] = 'com_djcatalog2';
		$temp_query['view'] = 'items';
		$temp_query['cid'] = $cid;
		$temp_query['Itemid'] = $active->id;
		$uri->setQuery($temp_query);

		$uri = JURI::getInstance(JRoute::_('index.php?'.$uri->getQuery(false)));
		
	}
	else {
		$uri = JURI::getInstance($juri->toString());
	}
} 

$query = $uri->getQuery(true);
$query['djcf'] = array();
foreach($query as $param=>$value) {
	if (strstr($param, 'f_')) {
		$qkey = substr($param, 2);
		$qval = (strstr($value,',') !== false) ? explode(',',$value) : $value;
		unset($query[$param]);
		$query['djcf'][$qkey] = $qval;
	}
}
unset($query['limitstart']);
//unset($query['search']);
unset($query['start']);
?>
<div class="mod_djc2filters">
<?php foreach ($data as $group) { ?>
	<?php if ($group->isempty == false) { ?>
		<?php if ($params->get('group_title', '1') == '1') { ?>
			<h4><?php echo JText::sprintf('MOD_DJC2FILTERS_GROUP_NAME', $group->group_name); ?></h4>
		<?php } ?>
		<dl>
		<?php foreach($group->attributes as $item) {
		    if (!empty($item->selectedOptions) || $item->availableOptions > 0) { ?>
		            <dt>
		                <?php echo $item->name; ?>
		                <?php if (!empty($item->selectedOptions)) {
		                    $filter_query = $query;
		                    unset($filter_query['djcf'][$item->alias]);
		                    if (empty($filter_query['djcf'])) {
		                        unset($filter_query['cm']);
		                    } else {
		                        $isEmpty = true;
		                        foreach ($filter_query['djcf'] as $k=>$v) {
		                            if (!empty($filter_query['djcf'][$k])) {
		                                $isEmpty = false;
		                                break;
		                            }
		                        }
		                        if ($isEmpty) {
		                            unset($filter_query['cm']);
		                        }
		                    }
		                	if (!empty($filter_query['djcf'])) {
		                    	$filters = array();
		                    	foreach ($filter_query['djcf'] as $a => $v) {
		                    		if (is_array($v)){
		                    			foreach ($v as $k=>$p) {
		                    				$v[$k] = (int)$p;
		                    			}
		                    			$filters['f_'.$a] = implode(',', $v);
		                    		} else {
		                    			$filters['f_'.$a] = (int)$v;
		                    		}
		                    	}
		                    	unset($filter_query['djcf']);
		                    	$filter_query = array_merge($filter_query, $filters);
		                    }
		                    
		                    $uri->setQuery($filter_query);
		                    ?>
		                    <a title="<?php echo JText::_('MOD_DJC2FILTERS_RESET_LABEL'); ?>" class="button" href="<?php echo htmlspecialchars($uri->toString()); ?>">
		                        <?php echo JText::_('MOD_DJC2FILTERS_RESET')?>
		                    </a>    
		                <?php } ?>
		            </dt>
		            <dd id="djc_filter_<?php echo JFilterOutput::stringURLSafe($item->name); ?>">
			            <ul class="menu nav">
			            <?php foreach ($item->optionsArray as $key=>$optionId) { ?>
			                <?php 
			                    //$optionAlias = preg_replace('#[^0-9a-zA-Z\-]#', '_',strtolower(trim($item->optionValuesArray[$key])));
			                    $optionAlias = JFilterOutput::stringURLSafe($item->optionValuesArray[$key]);
			                	$optionIdAlias = $optionId;//.':'.$optionAlias;
			                    $active = (in_array($optionId, $item->selectedOptions)) ? true:false;
			                    $class = ($active) ? 'class="active"' : '';
			                    $filter_query = $query;
			                    $filter_query['cm'] = '0';
			                    
			            		if (!array_key_exists('djcf', $filter_query)) {
			                    	$filter_query['djcf'] = array();
			            		}
			            		/*
			            		if (!array_key_exists($item->alias, $filter_query['djcf']) || !is_array($filter_query['djcf'][$item->alias])) {
									$filter_query['djcf'][$item->alias] = array();
								}
			                    */
			            		
			            		if (!array_key_exists($item->alias, $filter_query['djcf'])) {
			            			if ($item->type == 'checkbox') {
			            				$filter_query['djcf'][$item->alias] = array();
			            			} else {
			            				$filter_query['djcf'][$item->alias] = '';
			            			}	
								} else if ($item->type == 'checkbox' && is_scalar($filter_query['djcf'][$item->alias])) {
									$filter_query['djcf'][$item->alias] = explode(',', $filter_query['djcf'][$item->alias]);
								}
			                    
			                    if ($active) {
			                        if (array_key_exists('djcf', $filter_query)) {
			                            if (array_key_exists($item->alias, $filter_query['djcf'])) {
			                                if ($item->type == 'checkbox') {
			                                	$optionKey = array_search($optionIdAlias, $filter_query['djcf'][$item->alias]);
			                                    if ($optionKey >= 0) {
			                                        unset($filter_query['djcf'][$item->alias][$optionKey]);
			                                    }
			                                } else {
			                                    unset($filter_query['djcf'][$item->alias]);
			                                }
			                            }
			                        }
			                    }
			                    else {
			                        if ($item->type == 'checkbox') {
			                            $filter_query['djcf'][$item->alias][] = $optionIdAlias;
			                        } else {
			                            $filter_query['djcf'][$item->alias] = $optionIdAlias; 
			                        } 
			                    }
			                    
			                    if (empty($filter_query['djcf'])) {
			                        unset($filter_query['cm']);
			                    } else {
			                        $isEmpty = true;
			                        foreach ($filter_query['djcf'] as $k=>$v) {
			                            if (!empty($filter_query['djcf'][$k])) {
			                                $isEmpty = false;
			                                //break;
			                            }
			                            /*if (is_array($filter_query['djcf'][$k]) && !empty($filter_query['djcf'][$k])) {
			                            	$filter_query['djcf'][$k] = implode(',', $filter_query['djcf'][$k]);
			                            }*/
			                        }
			                        if ($isEmpty) {
			                            unset($filter_query['cm']);
			                        }
			                    }
			                    if (!empty($filter_query['djcf'])) {
			                    	$filters = array();
			                    	foreach ($filter_query['djcf'] as $a => $v) {
			                    		if (is_array($v)){
			                    			foreach ($v as $k=>$p) {
			                    				$v[$k] = (int)$p;
			                    			}
			                    			if (!empty($v)) {
			                    				$filters['f_'.$a] = implode(',', $v);
			                    			}
			                    		} else {
			                    			if ((int)$v) {
			                    				$filters['f_'.$a] = (int)$v;
			                    			}
			                    		}
			                    	}
			                    	unset($filter_query['djcf']);
			                    	$filter_query = array_merge($filter_query, $filters);
			                    }
			                    //echo '<pre>'.print_r($filters,true).'</pre>';
			                    $uri->setQuery($filter_query);
			                ?>
			                <?php if ($active || $item->optionCounterArray[$key] > 0) { ?>
			                <li <?php echo $class; ?>>
			                    <a href="<?php echo htmlspecialchars($uri->toString()); ?>"><?php echo $item->optionValuesArray[$key] ?> <small>[<?php echo $item->optionCounterArray[$key] ?>]</small></a>
			                </li>
			                <?php } ?>
			            <?php } ?>
			            </ul>
		            </dd>
		        <?php } ?>
		<?php } ?>
		</dl>
	<?php } ?>
<?php } ?>
</div>
<?php 
JURI::reset();
