<?php
/**
 * @version $Id: customfield.php 272 2014-05-21 10:25:49Z michal $
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );




jimport('joomla.plugin.plugin');
class plgDJCatalog2Customfield extends JPlugin {
	
	public static $attributes = array();
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	function onPrepareItemDescription( &$row, &$params, $page=0 )
	{
		$app = JFactory::getApplication();
		if (empty(self::$attributes)) {
			$db = JFactory::getDbo();
			
			$query = $db->getQuery(true);
			
			$query->select('f.*, group_concat(fo.id separator \'|\') as options');
			$query->from('#__djc2_items_extra_fields as f');
			$query->join('LEFT', '#__djc2_items_extra_fields_options as fo ON fo.field_id=f.id');
				
			$query->where('f.published = 1');
			$query->group('f.id');
			$query->order('f.group_id asc, f.ordering asc');
			
			$db->setQuery($query);
			self::$attributes = $db->loadObjectList();
		}
		
		//$regex	= '/{djc2customfield\s+(.*?)}/i';
		$regex = '#{djc2customfield\s([a-z0-9_]+?)([^}]*?)}#iU';
		
		
		$row->_nulledExtrafields = array();

		preg_match_all($regex, $row->description, $matches, PREG_SET_ORDER);
		
		// No matches, skip this
		if ($matches) {
			foreach ($matches as $match) {
		
				$matcheslist =  explode(',',$match[1]);
		
				if (!array_key_exists(1, $matcheslist)) {
					$matcheslist[1] = null;
				}
		
				$attrib = trim($matcheslist[0]);
				$item_attrib = '_ef_'.$attrib;
				$output = null;
				
				$show_label = true;
				$unset_var = true;
					
				if (isset($match[2])) {
					$attrs = self::parseAttributes(trim($match[2]));
					if (isset($attrs['label']) && $attrs['label'] == '0') {
						$show_label = false;
					}
					if (isset($attrs['unset']) && $attrs['unset'] == '0') {
						$unset_var = false;
					}
				}
				
				if (!empty($row->$item_attrib)) {
					
					foreach (self::$attributes as $attribute) {
						if ($attribute->alias == $attrib) {
							$attributeData = $row->$item_attrib;
							//if ($attribute->type == 'checkbox') {
							if (is_array($attributeData)) {
								/*$listItems = $attributeData;
								$attributeData = '<ul class="djc_attribute_options">';
								foreach ($listItems as $key => $value) {
									$attributeData .= '<li class="djc_attribute_option '.preg_replace('#[^0-9a-zA-Z\-]#', '_', strtolower(trim($value))).'">'.$value.'</li>';
								}
								$attributeData .= '</ul>';*/
								$attributeData = implode(', ', $attributeData);
							}
							if ($show_label) {
								$output .= '<span class="djc_attribute '.preg_replace('#[^0-9a-zA-Z\-]#', '_', strtolower(trim($attribute->name))).'"><span class="djc_attribute-label">'.$attribute->name.': </span><span class="djc_attribute-value">'.$attributeData.'</span></span>';
							} else {
								$output .= '<span class="djc_attribute '.preg_replace('#[^0-9a-zA-Z\-]#', '_', strtolower(trim($attribute->name))).'"><span class="djc_attribute-value">'.$attributeData.'</span></span>';
							}
						}
					}
					$row->_nulledExtrafields[] = $attrib;
					$row->_nulledExtrafields[] = $item_attrib;
					
					if ($unset_var) {
						unset($row->$item_attrib);
					}
				}
				$row->description = preg_replace("|$match[0]|", addcslashes($output, '\\'), $row->description, 1);
			}
		}
		
		return true;
		
	}
	
	public static function parseAttributes($string) {
		$attr = array();
		$retarray = array();
	
		// Let's grab all the key/value pairs using a regular expression
		preg_match_all('/([\w:-]+)[\s]?=[\s]?"([^"]*)"/i', $string, $attr);
	
		if (is_array($attr))
		{
			$numPairs = count($attr[1]);
	
			for ($i = 0; $i < $numPairs; $i++) {
				$retarray[$attr[1][$i]] = $attr[2][$i];
				}
			}

			return $retarray;
	}
	
}


