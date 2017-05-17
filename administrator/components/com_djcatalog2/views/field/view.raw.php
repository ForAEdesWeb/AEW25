<?php
/**
 * @version $Id: view.raw.php 132 2013-05-20 07:12:44Z michal $
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

jimport('joomla.application.component.view');

class Djcatalog2ViewField extends JViewLegacy {
	
	protected $fieldtype;
	protected $suffix;
	protected $fieldId;
	
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		
		$this->fieldtype = $app->input->get('fieldtype', null, 'string');
		$this->fieldId = $app->input->get('fieldId', 0, 'int');
		$this->suffix = $app->input->get('suffix', null, ' string');
		
		$db = JFactory::getDbo();
		
		if ($this->fieldId > 0) {
			$db->setQuery('SELECT * FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$this->fieldId.' ORDER BY ordering ASC');
			$this->fieldoptions = $db->loadObjectList();
		}
		
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$tpl = 'legacy';
		}	
		parent::display($tpl);
	}
	
}
?>