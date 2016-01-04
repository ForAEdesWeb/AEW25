<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Userxtd Component
 *
 * @since 1.0
 */
final class UserxtdComponent extends \Userxtd\Component\UserxtdComponent
{
	/**
	 * Default task name.
	 *
	 * @var string
	 */
	protected $defaultController = 'user.display';

	/**
	 * Prepare hook of this component.
	 *
	 * Do some customize initialise through extending this method.
	 *
	 * @return void
	 */
	public function prepare()
	{
		parent::prepare();

		// Prevent JToolbarHelper bug
		include_once JPATH_ADMINISTRATOR . '/includes/toolbar.php';

		// Load admin language
		$lang = JFactory::getLanguage();
		$lang->load('', JPATH_ADMINISTRATOR);

		// Load css
		$asset = $this->container->get('helper.asset');
		$asset->addCss('userxtd-component.css')
			->addCss('main.css');
	}
}
