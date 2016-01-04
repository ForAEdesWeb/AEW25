<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Class UserxtdControllerUserEditSave
 *
 * @since 1.0
 */
class UserxtdControllerUserEditSave extends \Windwalker\Controller\Edit\SaveController
{
	/**
	 * redirectToItem
	 *
	 * @param integer $recordId
	 * @param string  $urlVar
	 * @param string  $msg
	 * @param string  $type
	 *
	 * @return  void
	 */
	public function redirectToItem($recordId = null, $urlVar = 'id', $msg = null, $type = 'message')
	{
		$this->redirect(
			\Windwalker\Router\Route::_('com_userxtd.user_layout', array('id' => $recordId, 'layout' => 'edit')),
			$msg,
			$type
		);
	}

	/**
	 * redirectToList
	 *
	 * @param string $msg
	 * @param string $type
	 *
	 * @return  void
	 */
	public function redirectToList($msg = null, $type = 'message')
	{
		$this->redirect(
			\Windwalker\Router\Route::_('com_userxtd.user_id', array('id' => $this->recordId)),
			$msg,
			$type
		);
	}

	/**
	 * Method to check if you can save a new or existing record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 */
	protected function allowSave($data, $key = 'id')
	{
		return true;
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		return true;
	}
}
 