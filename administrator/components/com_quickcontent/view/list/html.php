<?php
/**
 * Part of Component Quickcontent files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\DI\Container;
use Windwalker\Model\Model;
use Windwalker\View\Engine\PhpEngine;
use Windwalker\View\Html\EditView;
use Windwalker\Xul\XulEngine;

// No direct access
defined('_JEXEC') or die;

/**
 * Quickcontent Lists view
 *
 * @since 1.0
 */
class QuickcontentViewListHtml extends EditView
{
	/**
	 * The component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'quickcontent';

	/**
	 * The component option name.
	 *
	 * @var string
	 */
	protected $option = 'com_quickcontent';

	/**
	 * The text prefix for translate.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_QUICKCONTENT';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $name = 'list';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'list';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'lists';

	/**
	 * Method to instantiate the view.
	 *
	 * @param Model            $model     The model object.
	 * @param Container        $container DI Container.
	 * @param array            $config    View config.
	 * @param SplPriorityQueue $paths     Paths queue.
	 */
	public function __construct(Model $model = null, Container $container = null, $config = array(), \SplPriorityQueue $paths = null)
	{
		$this->engine = new PhpEngine;

		parent::__construct($model, $container, $config, $paths);
	}

	/**
	 * Prepare data hook.
	 *
	 * @return  void
	 */
	protected function prepareData()
	{
		parent::prepareData();

		// Load content language
		$lang = JFactory::getLanguage();

		$lang->load('com_content', JPATH_BASE, null, true, true);
		$lang->load('com_menus', JPATH_BASE, null, true, true);

		$data = $this->data;

		$data->params = \Windwalker\System\ExtensionHelper::getParams('com_quickcontent');
		$data->formParams = $this->get('FormParams');

		$data->listParams = $this->buildParamsInput('list');
		$data->blogParams = $this->buildParamsInput('blog');
		$data->articleParams = $this->buildParamsInput('article');

		// Set Editor
		$editor = \JEditor::getInstance($data->params->get('editor', 'tinymce'));
		$params['mode'] = 2;
		$this->editor = $editor->display('jform[content]', $data->item->content, '650px', '500px', 650, 500, false, null, null, null, $params);
	}

	/**
	 * buildParamsInput
	 *
	 * @param string $id
	 *
	 * @return  mixed
	 */
	protected function buildParamsInput($id)
	{
		$fieldSets = $this->data->formParams->$id->getFieldsets($id);
		$keys = array_keys($fieldSets);

		$data = array(
			'fieldsets' => $fieldSets,
			'keys' => $keys,
			'id' => $id,
			'formParams' => $this->data->formParams
		);

		return with(new \Windwalker\View\Layout\FileLayout('com_quickcontent.paramsinput'))->render($data);
	}
}
