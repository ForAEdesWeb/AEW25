<?php
/**
 * @version $Id: text.php 270 2014-04-10 06:40:48Z michal $
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

class DJCatalog2CustomFieldText extends DJCatalog2CustomField {
	public $type = 'text';
	public $base_type = 'text';
	
	public function getFormInput($attribs = ''){
		$class = (int)$this->required == 1 ? 'input inputbox required' : 'input inputbox';
		$class = 'class="'.$class.'"';
		
		return '<input type="text" name="attribute['.$this->field_id.']" id="attribute_'.$this->field_id.'" value="'.$this->getValue().' '.$attribs.'" '.$class.'/>';
	}
}

