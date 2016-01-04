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
class UserxtdControllerUserEditCancel extends \Windwalker\Controller\Edit\CancelController
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
}
 