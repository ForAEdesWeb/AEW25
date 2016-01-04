<?php
/**
 * Part of Component Userxtd files.
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
 * Userxtd Users View
 *
 * @since 1.0
 */
class UserxtdViewUsersHtml extends GridView
{
	/**
	 * The component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'userxtd';

	/**
	 * The component option name.
	 *
	 * @var string
	 */
	protected $option = 'com_userxtd';

	/**
	 * The text prefix for translate.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_USERXTD';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $name = 'users';

	/**
	 * The item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'user';

	/**
	 * The list name.
	 *
	 * @var  string
	 */
	protected $viewList = 'users';

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
			'option'    => 'com_userxtd',
			'view_name' => 'user',
			'view_item' => 'user',
			'view_list' => 'users',

			// Column which we allow to drag sort
			'order_column'   => 'user.catid, user.ordering',

			// Table id
			'order_table_id' => 'userList',

			// Ignore user access, allow all.
			'ignore_access'  => false
		);

		// Directly use php engine
		$this->engine = new PhpEngine;

		parent::__construct($model, $container, $config, $paths);
	}

	/**
	 * setTitle
	 *
	 * @param string $title
	 * @param string $icons
	 *
	 * @return  void
	 */
	protected function setTitle($title = null, $icons = 'stack')
	{
		parent::setTitle(\JText::_('COM_USERXTD_TITLE_USERS_LIST'), $icons);
	}

	/**
	 * prepareRender
	 *
	 * @return  void
	 */
	protected function prepareRender()
	{
		$data = $this->getData();

		$data->fields = $this->get('Fields');

		parent::prepareRender();

		$data->keys	= $data->state->get('profileKeys', array());
		$data->filteredFields = $data->state->get('filteredFields');
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
		$buttonSet = array(
			'preferences' => array(
				'handler' => 'preferences',
				'access'   => 'core.edit',
				'priority' => 100
			),

			'user_config' => array(
				'handler' => function()
					{
						\JToolbarHelper::preferences('com_users', 550, 875, 'COM_USERXTD_TOOLBAR_COM_USERS_CONFIG');
					},
				'access' => $this->container->get('user')->authorise('core.admin', 'com_users')
			)
		);

		return $buttonSet;
	}
}
