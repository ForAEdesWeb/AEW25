<?php
/**
 * Part of Component Copymodules files.
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
 * Copymodules Modules View
 *
 * @since 1.0
 */
class CopymodulesViewModulesHtml extends GridView
{
	/**
	 * The component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'copymodules';

	/**
	 * The component option name.
	 *
	 * @var string
	 */
	protected $option = 'com_copymodules';

	/**
	 * The text prefix for translate.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_COPYMODULES';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $name = 'modules';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'module';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'modules';

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
			'option'    => 'com_copymodules',
			'view_name' => 'module',
			'view_item' => 'module',
			'view_list' => 'modules',

			// Column which we allow to drag sort
			'order_column'   => 'module.catid, module.ordering',

			// Table id
			'order_table_id' => 'moduleList',

			// Ignore user access, allow all.
			'ignore_access'  => true,

            // field
            'field' => array(
                'state' => 'published'
            )
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

		// In debug mode, we remove trash button but use delete button instead.
		if (JDEBUG)
		{
			$buttonSet['trash']['access']  = false;
			$buttonSet['delete']['access'] = true;
		}

		return $buttonSet;
	}
}
