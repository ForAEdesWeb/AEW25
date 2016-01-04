<?php
/**
 * @version $Id: djtheme.php 99 2013-01-08 10:39:32Z michal $
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

defined('_JEXEC') or die();
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.filesystem.folder');

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

class JFormFieldDjtheme extends JFormField {
	
	protected $type = 'Djcategory';
	
	protected function getInput()
	{
		$attr = '';

		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		
		$themes = JFolder::listFolderTree(JPATH_SITE.DS.'components'.DS.'com_djcatalog2'.DS.'themes','',1);
		$default = $this->element['default'];
		$options = array();
		if ($default == '') {
			$options[] = JHTML::_('select.option', '', JText::_('JGLOBAL_USE_GLOBAL'));
		}
		foreach ($themes as $theme) {
			$options[] = JHTML::_('select.option', $theme['name'], $theme['name']);
		}			
		$out = JHTML::_('select.genericlist', $options, $this->name, null, 'value', 'text', $this->value);
		
		return ($out);
		
	}
}
?>