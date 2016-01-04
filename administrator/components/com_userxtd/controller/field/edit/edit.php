<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
use Joomla\Registry\Registry;

/**
 * Class UserxtdControllerFieldDisplay
 *
 * @since 1.0
 */
class UserxtdControllerFieldEditEdit extends \Windwalker\Controller\Edit\EditController
{
	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->app->setUserState("com_userxtd.edit.field.attrs", array());
	}
}
 