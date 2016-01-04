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
class UserxtdControllerFieldDisplay extends \Windwalker\Controller\DisplayController
{
	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->app->setUserState("com_userxtd.edit.field.data", $this->input->getVar('jform'));

		$attrs = $this->app->getUserState("com_userxtd.edit.field.attrs", array());

		$postAttrs = $this->input->getVar('attrs', array());

		$attrs = new Registry($attrs);

		$attrs->loadArray($postAttrs);

		$this->app->setUserState("com_userxtd.edit.field.attrs", $attrs->toArray());
	}
}
 