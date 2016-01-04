<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class UserxtdControllerFieldsStatePublish
 *
 * @since 1.0
 */
class UserxtdControllerFieldsStateUnpublish extends \Windwalker\Controller\State\UnpublishController
{
	/**
	 * The data fields to update.
	 *
	 * @var string
	 */
	protected $stateData = array(
		'published' => 0
	);
}
 