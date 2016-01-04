<?php
/**
 * Part of Component Quickcontent files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\DI\Container;
use Windwalker\Model\Model;
use Windwalker\View\Engine\PhpEngine;
use Windwalker\View\Html\GridView;
use Windwalker\Xul\XulEngine;

// No direct access
defined('_JEXEC') or die;

/**
 * Quickcontent Lists View
 *
 * @since 1.0
 */
class QuickcontentViewListsHtml extends GridView
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
	protected $name = 'lists';

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
		$config['grid'] = array(
			// Some basic setting
			'option'    => 'com_quickcontent',
			'view_name' => 'list',
			'view_item' => 'list',
			'view_list' => 'lists',

			// Column which we allow to drag sort
			'order_column'   => 'list.catid, list.ordering',

			// Table id
			'order_table_id' => 'listList',

			// Ignore user access, allow all.
			'ignore_access'  => false
		);

		// Directly use php engine
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
	}

	/**
	 * Configure the toolbar button set.
	 *
	 * @param   array   $buttonSet Customize button set.
	 * @param   object  $canDo     Access object.
	 *
	 * @return  array
	 */
	protected function configureToolbar($buttonSet = array(), $canDo = null)
	{
		// Get default button set.
		$buttonSet = parent::configureToolbar($buttonSet, $canDo);

		$buttonSet['trash']['access']  = false;
		$buttonSet['delete']['access'] = true;
		$buttonSet['batch']['access'] = false;
		$buttonSet['publish']['access'] = false;
		$buttonSet['unpublish']['access'] = false;

		$buttonSet['clear'] = array(
			'handler' => function()
			{
				$msg = JText::_('COM_QUICKCONTENT_CLEAR_WHOLE_SITE_ALERT');
				$text = JText::_('COM_QUICKCONTENT_CLEAR_WHOLE_SITE');

				$html = <<<BTN
<button onclick="if (confirm('{$msg}')){Joomla.submitbutton('generator.deleteall');}" class="btn btn-small">
	<span class="icon-delete text-error"></span>
	<span class="text-error">{$text}</span>
</button>
BTN;


				$bar = JToolBar::getInstance('toolbar');
				$bar->appendButton('Custom', $html);
			}
		);

		return $buttonSet;
	}
}
