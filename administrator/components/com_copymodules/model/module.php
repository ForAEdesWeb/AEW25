<?php
/**
 * Part of Component Copymodules files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Windwalker\Model\AdminModel;

// No direct access
defined('_JEXEC') or die;

/**
 * Copymodules Module model
 *
 * @since 1.0
 */
class CopymodulesModelModule extends AdminModel
{
	/**
	 * Component prefix.
	 *
	 * @var  string
	 */
	protected $prefix = 'copymodules';

	/**
	 * The URL option for the component.
	 *
	 * @var  string
	 */
	protected $option = 'com_copymodules';

	/**
	 * The prefix to use with messages.
	 *
	 * @var  string
	 */
	protected $textPrefix = 'COM_COPYMODULES';

	/**
	 * The model (base) name
	 *
	 * @var  string
	 */
	protected $name = 'module';

	/**
	 * Item name.
	 *
	 * @var  string
	 */
	protected $viewItem = 'module';

	/**
	 * List name.
	 *
	 * @var  string
	 */
	protected $viewList = 'modules';

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
        $pk = (!empty($pk)) ? $pk : $this->state->get($this->getName() . '.id');
        $table = $this->getTable();

        if (!empty($pk))
        {
            // Attempt to load the row.
            $table->load($pk);
        }

        // Convert to the JObject before adding other data.
        $properties = $table->getProperties(1);
        $item = \JArrayHelper::toObject($properties, 'stdClass');

        return $item;
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A reference to a JTable object.
	 *
	 * @return  void
	 */
	protected function prepareTable(\JTable $table)
	{
		parent::prepareTable($table);
	}

	/**
	 * Post save hook.
	 *
	 * @param JTable $table The table object.
	 *
	 * @return  void
	 */
	public function postSaveHook(\JTable $table)
	{
		parent::postSaveHook($table);
	}

	/**
	 * Method to set new item ordering as first or last.
	 *
	 * @param   JTable $table    Item table to save.
	 * @param   string $position 'first' or other are last.
	 *
	 * @return  void
	 */
	public function setOrderPosition($table, $position = 'last')
	{
		parent::setOrderPosition($table, $position);
	}
}
