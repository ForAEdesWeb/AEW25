<?php
/**
 * @version $Id: itemevents.php 191 2013-11-03 07:15:27Z michal $
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

// No direct access.
defined('_JEXEC') or die;

class Djcatalog2Itemevents extends JEvent {
	
	public function onContentBeforeSave( $context, $table, $isNew ) {
		switch($context) {
			case 'com_djcatalog2.itemform' : {
				return $this->onItemBeforeSave($context, $table, $isNew);
				break;
			}
		}
	}
	
	public function onContentAfterSave( $context, $table, $isNew ) {
		switch($context) {
			case 'com_djcatalog2.itemform' : {
				return $this->onItemAfterSave($context, $table, $isNew);
				break;
			}
		}
	}
	public function onContentAfterDelete( $context, $table) {
		switch($context) {
			case 'com_djcatalog2.itemform' : {
				return $this->onItemAfterDelete($context, $table);
				break;
			}
		}
	}
	
	public function onItemBeforeSave( $context, $table, $isNew ) {
		$app = JFactory::getApplication();
		$attribs = $app->input->get('attribute',array(), 'array');
		$model = JModelAdmin::getInstance('Itemform', 'Djcatalog2Model', array());
		if (!$model->validateAttributes($attribs, $table)) {
			$errors = $model->getErrors();
			foreach ($errors as $error) {
				$app->enqueueMessage($error,'error');
			}
			$table->setError(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));
			return false;
		}
	}

	public function onItemAfterSave( $context, $table, $isNew ) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		$data		= $app->input->get('jform', array(), 'array');
		
		// saving additional categories
		$db = JFactory::getDbo();
		$db->setQuery('DELETE FROM #__djc2_items_categories WHERE item_id=\''.$table->id.'\'');
		
		$category_limit = (int)$params->get('fed_multiple_categories_limit',3);
		if ($db->query()) {
			if (!isset($data['categories'])) {
				$data['categories'] = array();
			}
			
			if ($category_limit > 0) {
				$data['categories'] = array_slice($data['categories'], 0, $category_limit);
			}
			
			$data['categories'][] = $table->cat_id;
			
			if (!empty($data['categories'])) {
				$data['categories'] = array_unique($data['categories']);
				foreach ($data['categories'] as $cat_id) {
					if ($cat_id > 0) {
						$db->setQuery('INSERT INTO #__djc2_items_categories VALUES (\''.$table->id.'\', \''.$cat_id.'\')');
						$db->query();
					}
				}
			}
		}
		
		// saving images
		if (!DJCatalog2ImageHelper::saveImages('item',$table, $params, $isNew)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_SAVING_IMAGES'),'error');
		}
		
		// saving attachments
		if (!DJCatalog2FileHelper::saveFiles('item',$table, $params, $isNew)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_SAVING_FILES'),'error');
		}
		
		// saving additional attributes
		$attribs = $app->input->get('attribute',array(), 'array');
		$model = JModelAdmin::getInstance('Itemform', 'Djcatalog2Model', array());
		if (!$model->saveAttributes($attribs, $table)) {
			$app->enqueueMessage($model->getError(),'error');
		}
	}
	public function onItemAfterDelete( $context, $table) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		
		$db = JFactory::getDbo();
		$db->setQuery('DELETE FROM #__djc2_items_categories WHERE item_id=\''.$table->id.'\'');
		$db->query();
		
		$db->setQuery('DELETE FROM #__djc2_items_related WHERE item_id=\''.$table->id.'\' OR related_item=\''.$table->id.'\'');
		$db->query();
		
		$db->setQuery('DELETE FROM #__djc2_items_extra_fields_values_text WHERE item_id=\''.$table->id.'\'');
		$db->query();
		
		$db->setQuery('DELETE FROM #__djc2_items_extra_fields_values_int WHERE item_id=\''.$table->id.'\'');
		$db->query();
		
		if (!DJCatalog2ImageHelper::deleteImages('item',$table->id)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_DELETING_IMAGES'),'error');
		}
		if (!DJCatalog2FileHelper::deleteFiles('item',$table->id)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_DELETING_FILE'),'error');
		}
	}
}