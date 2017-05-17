<?php
/**
 * @version $Id: events.php 272 2014-05-21 10:25:49Z michal $
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

class Djcatalog2Event extends JEvent {
	
	public function onContentBeforeSave( $context, $table, $isNew ) {
		switch($context) {
			case 'com_djcatalog2.item' : {
				return $this->onItemBeforeSave($context, $table, $isNew);
				break;
			}
		}
	}
	
	public function onContentAfterSave( $context, $table, $isNew ) {
		switch($context) {
			case 'com_djcatalog2.item' : {
				return $this->onItemAfterSave($context, $table, $isNew);
				break;
			}
			case 'com_djcatalog2.category' : {
				return $this->onCategoryAfterSave($context, $table, $isNew);
				break;
			}
			case 'com_djcatalog2.producer' : {
				return $this->onProducerAfterSave($context, $table, $isNew);
				break;
			}
			case 'com_djcatalog2.field' : {
				return $this->onFieldAfterSave($context, $table, $isNew);
				break;
			}
		}
	}
	public function onContentAfterDelete( $context, $table) {
		switch($context) {
			case 'com_djcatalog2.item' : {
				return $this->onItemAfterDelete($context, $table);
				break;
			}
			case 'com_djcatalog2.category' : {
				return $this->onCategoryAfterDelete($context, $table);
				break;
			}
			case 'com_djcatalog2.producer' : {
				return $this->onProducerAfterDelete($context, $table);
				break;
			}
			case 'com_djcatalog2.field' : {
				return $this->onFieldAfterDelete($context, $table);
				break;
			}
		}
	}
	
	public function onItemBeforeSave( $context, $table, $isNew ) {
		$app = JFactory::getApplication();
		$attribs = $app->input->get('attribute',array(), 'array');
		$model = JModelAdmin::getInstance('Item', 'Djcatalog2Model', array());
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
		if ($db->query()) {
			if (!isset($data['categories'])) {
				$data['categories'] = array();
			}
			$data['categories'][] = $table->cat_id;
			if (!empty($data['categories'])) {
				$data['categories'] = array_unique($data['categories']);
				foreach ($data['categories'] as $cat_id) {
					$db->setQuery('INSERT INTO #__djc2_items_categories VALUES (\''.$table->id.'\', \''.$cat_id.'\')');
					$db->query();
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
		$model = JModelAdmin::getInstance('Item', 'Djcatalog2Model', array());
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
		
		$db->setQuery('DELETE FROM #__djc2_items_extra_fields_values_date WHERE item_id=\''.$table->id.'\'');
		$db->query();
		
		if (!DJCatalog2ImageHelper::deleteImages('item',$table->id)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_DELETING_IMAGES'),'error');
		}
		if (!DJCatalog2FileHelper::deleteFiles('item',$table->id)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_DELETING_FILE'),'error');
		}
	}
	public function onCategoryAfterSave( $context, $table, $isNew ) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		if (!DJCatalog2ImageHelper::saveImages('category',$table, $params, $isNew)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_SAVING_IMAGES'),'error');
		}
	}
	public function onCategoryAfterDelete( $context, $table) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		if (!DJCatalog2ImageHelper::deleteImages('category',$table->id)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_DELETING_IMAGES'),'error');
		}
	}
	public function onProducerAfterSave( $context, $table, $isNew ) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		if (!DJCatalog2ImageHelper::saveImages('producer',$table, $params, $isNew)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_SAVING_IMAGES'),'error');
		}
	}
	public function onProducerAfterDelete( $context, $table) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		if (!DJCatalog2ImageHelper::deleteImages('producer',$table->id)) {
			$app->enqueueMessage(JText::_('COM_DJCATALOG2_ERROR_DELETING_IMAGES'),'error');
		}
	}
	public function onFieldAfterSave( $context, $table, $isNew ) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		$values = ($app->input->get('fieldtype',array(),'array'));
		$model = JModelAdmin::getInstance('Field', 'Djcatalog2Model', array());
		if (!$model->saveOptions($values, $table, $isNew)) {
			$app->enqueueMessage($model->getError(),'error');
		}
	}
	public function onFieldAfterDelete( $context, $table) {
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		$app = JFactory::getApplication();
		$model = JModelAdmin::getInstance('Field', 'Djcatalog2Model', array());
		if (!$model->deleteOptions($table)) {
			$app->enqueueMessage($model->getError(),'error');
		}
	}
}