<?php
/**
 * Part of Component Quickcontent files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Model\AdminModel;

// No direct access
defined('_JEXEC') or die;

/**
 * Quickcontent List model
 *
 * @since 1.0
 */
class QuickcontentModelList extends AdminModel
{
	/**
	 * Component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'quickcontent';

	/**
	 * The URL option for the component.
	 *
	 * @var  string
	 */
	protected $option = 'com_quickcontent';

	/**
	 * The prefix to use with messages.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_QUICKCONTENT';

	/**
	 * The model (base) name
	 *
	 * @var  string
	 */
	protected $name = 'list';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'list';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'lists';

	/**
	 * Property blogForm.
	 *
	 * @var  JForm
	 */
	protected $blogForm = null;

	/**
	 * Property blogForm.
	 *
	 * @var  JForm
	 */
	protected $listForm = null;

	/**
	 * Property blogForm.
	 *
	 * @var  JForm
	 */
	protected $articleForm = null;

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		return parent::getItem($pk);
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A reference to a JTable object.
	 *
	 * @return  void
	 */
	protected function prepareTable(\JTable $table)
	{
		$input = JFactory::getApplication()->input;

		$jform = $input->post->getRaw('jform');

		$jform['list'] = new JRegistry($jform['list']);
		$table->list = $jform['list'] = $jform['list']->toString();

		$jform['blog'] = new JRegistry($jform['blog']);
		$table->blog = $jform['blog'] = $jform['blog']->toString();

		$jform['article'] = new JRegistry($jform['article']);
		$table->article = $jform['article'] = $jform['article']->toString();

		$input->set('jform', $jform);

		$table->bind($jform);

		parent::prepareTable($table);
	}

	/**
	 * Post save hook.
	 *
	 * @param JTable $table The table object.
	 *
	 * @return  void
	 */
	public function postSaveHook(\JTable $table)
	{
		parent::postSaveHook($table);
	}

	/**
	 * Method to set new item ordering as first or last.
	 *
	 * @param   JTable $table    Item table to save.
	 * @param   string $position 'first' or other are last.
	 *
	 * @return  void
	 */
	public function setOrderPosition($table, $position = 'last')
	{
		parent::setOrderPosition($table, $position);
	}

	/**
	 * getFormParams
	 *
	 * @param array $data
	 * @param bool  $loadData
	 *
	 * @return  JObject
	 */
	public function getFormParams($data = array(), $loadData = true)
	{
		$form = new JObject;
		
		$form->blog    = $this->blogForm;
		$form->list    = $this->listForm;
		$form->article = $this->articleForm;

		return $form;
	}

	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @see     JFormField
	 * @since   11.1
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		if (!$data)
		{
			parent::preprocessForm($form, $data, $group);

			return;
		}

		$blogXML    = JPATH_ROOT . '/components/com_content/views/category/tmpl/blog.xml';
		$listXML    = JPATH_ROOT . '/components/com_content/views/category/tmpl/default.xml';
		$articleXML = JPATH_ROOT . '/components/com_content/views/article/tmpl/default.xml';
		$menuXML    = JPATH_BASE . '/components/com_menus/models/forms/item_component.xml';

		// set list params
		$listParams                    = simplexml_load_file($listXML);
		$listParams->fields[1]['name'] = 'list';

		// set blog params
		$blogParams                    = simplexml_load_file($blogXML);
		$blogParams->fields[1]['name'] = 'blog';

		// set article params
		$articleParams                    = simplexml_load_file($articleXML);
		$articleParams->fields[1]['name'] = 'article';

		// create Form
		$this->listForm    = JForm::getInstance('list', $listParams->asXML(), array('control' => 'jform'), true, '/metadata');
		$this->blogForm    = JForm::getInstance('blog', $blogParams->asXML(), array('control' => 'jform'), true, '/metadata');
		$this->articleForm = JForm::getInstance('article', $articleParams->asXML(), array('control' => 'jform'), true, '/metadata');

		// set menu xml
		$menuParams = simplexml_load_file($menuXML);

		$menuParams->fields[0]['name'] = 'list';
		$this->listForm->load($menuParams->asXML(), true, '/form');

		$menuParams->fields[0]['name'] = 'blog';
		$this->blogForm->load($menuParams->asXML(), true, '/form');

		$menuParams->fields[0]['name'] = 'article';
		$this->articleForm->load($menuParams->asXML(), true, '/form');

		// bind data
		$data->list    = json_decode($data->list);
		$data->blog    = json_decode($data->blog);
		$data->article = json_decode($data->article);

		$this->listForm->bind($data);
		$this->blogForm->bind($data);
		$this->articleForm->bind($data);

		parent::preprocessForm($form, $data, $group);
	}
}
