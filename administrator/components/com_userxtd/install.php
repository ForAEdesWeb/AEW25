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
 * Script file of HelloWorld component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_userxtd
 */
class Com_UserxtdInstallerScript
{
	/**
	 * Method to install the component.
	 *
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function install(\JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * Method to uninstall the component.
	 *
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function uninstall(\JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * Method to update the component
	 *
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function update(\JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * ethod to run before an install/update/uninstall method
	 *
	 * @param string                     $type
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function preflight($type, \JInstallerAdapterComponent $parent)
	{
	}

	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @param string                     $type
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	public function postflight($type, \JInstallerAdapterComponent $parent)
	{
		if ($type == 'install')
		{
			$this->createExampleData($parent);
		}

		$db = JFactory::getDbo();

		// Get install manifest
		// ========================================================================
		$p_installer = $parent->getParent();
		$installer   = new JInstaller;
		$manifest    = $p_installer->manifest;
		$path        = $p_installer->getPath('source');
		$result      = array();

		$css = <<<CSS
<style type="text/css">
#ak-install-img
{
}

#ak-install-msg
{
}
</style>
CSS;

		echo $css;

		$installScript = dirname($path) . '/windwalker/src/System/installscript.php';

		if (!is_file($installScript))
		{
			$installScript = JPATH_LIBRARIES . '/windwalker/src/System/installscript.php';
		}

		include $installScript;
	}

	/**
	 * createExampleData
	 *
	 * @param JInstallerAdapterComponent $parent
	 *
	 * @return  void
	 */
	protected function createExampleData(\JInstallerAdapterComponent $parent)
	{
		// Prepare example data
		$cats   = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_userxtd/sql/example_categories.json');
		$basic  = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_userxtd/sql/example_basic.json');
		$living = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_userxtd/sql/example_living.json');

		$db       = JFactory::getDbo();
		$user     = JFactory::getUser();
		$date     = JFactory::getDate('now', JFactory::getConfig()->get('offset'));
		$cats     = json_decode($cats, true);
		$catTable = JTable::getInstance('Category');
		$catids   = array();

		// Import Categories
		foreach ($cats as $cat)
		{
			$catTable->bind($cat);
			$catTable->id               = null;
			$catTable->alias            = 'userxtd-' . $catTable->alias;
			$catTable->asset_id         = null;
			$catTable->lft              = null;
			$catTable->rgt              = null;
			$catTable->parent_id        = 1;
			$catTable->created_user_id  = $user->id;
			$catTable->modified_user_id = $user->id;
			$catTable->created_time     = $date->toSQL(true);
			$catTable->modified_time    = $db->getNullDate();
			$catTable->setLocation(1, 'last-child');

			$catTable->store();

			$catids[] = $catTable->id;

			$catTable->reset();
		}

		// Import Basic Fields
		$fields = json_decode($basic, true);

		include_once JPATH_ADMINISTRATOR . '/components/com_userxtd/table/field.php';

		$fieldTable = JTable::getInstance('Field', 'UserxtdTable');

		foreach ($fields as $field)
		{
			$fieldTable->bind($field);
			$fieldTable->id          = null;
			$fieldTable->asset_id    = null;
			$fieldTable->catid       = $catids[0];
			$fieldTable->created_by  = $user->id;
			$fieldTable->modified_by = $user->id;
			$fieldTable->created     = $date->toSQL(true);

			$fieldTable->store();

			$fieldTable->reset();
		}

		// Import Living Fields
		$fields = json_decode($living, true);

		$fieldTable = JTable::getInstance('Field', 'UserxtdTable');

		foreach ($fields as $field)
		{
			$fieldTable->bind($field);
			$fieldTable->id          = null;
			$fieldTable->asset_id    = null;
			$fieldTable->catid       = $catids[1];
			$fieldTable->created_by  = $user->id;
			$fieldTable->modified_by = $user->id;
			$fieldTable->created     = $date->toSQL(true);

			$fieldTable->store();

			$fieldTable->reset();
		}
	}
}
