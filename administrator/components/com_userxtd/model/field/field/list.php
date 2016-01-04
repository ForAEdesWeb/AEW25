<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_LIBRARIES . '/windwalker/src/init.php';
JForm::addFieldPath(WINDWALKER_SOURCE . '/Form/Fields');
JFormHelper::loadFieldClass('itemlist');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldField_List extends JFormFieldItemlist
{
	/**
	 * The form field type.
	 *
	 * @var string
	 */
	public $type = 'Field_List';

	/**
	 * List name.
	 *
	 * @var string
	 */
	protected $view_list = 'fields';

	/**
	 * Item name.
	 *
	 * @var string
	 */
	protected $view_item = 'field';

	/**
	 * Extension name, eg: com_content.
	 *
	 * @var string
	 */
	protected $extension = 'com_userxtd';

	/**
	 * Set the published column name in table.
	 *
	 * @var string
	 */
	protected $published_field = 'state';

	/**
	 * Set the ordering column name in table.
	 *
	 * @var string
	 */
	protected $ordering_field = null;

	/**
	 * getOptions
	 *
	 * @return  array
	 */
	public function getOptions()
	{
		$this->element['key_field'] = 'name';
		$this->element['value_field'] = 'new_title';
		$this->element['select'] = "CONCAT( title, ' (', name, ')' ) AS new_title" ;

		return parent::getOptions();
	}
}
