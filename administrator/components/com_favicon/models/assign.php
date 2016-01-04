<?php
/**
 * @copyright	Copyright (C) 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'favicons.php');

/**
 * @package		Joomla.Administrator
 * @subpackage	Templates
 * @since		1.5
 */
class FaviconModelAssign extends JModelForm
{
	/**
	 * Cache for the template information.
	 *
	 * @var		object
	 */

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_favicon.assign', 'assign', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
                $data->iconid = JRequest::getInt('id');
                $faviconsmodel = new FaviconModelFavicons;
                $pluginparams = json_decode($faviconsmodel->plugin->params,true);
                if(
                        is_array($pluginparams) &&
                        array_key_exists('assignments',$pluginparams) &&
                        is_array($pluginparams['assignments']) &&
                        array_key_exists($data->iconid,$pluginparams['assignments'])
                ) {
                    $data->menus = $pluginparams['assignments'][$data->iconid];
                }
		return $data;
	}

	/**
	 * Method to store the source file contents.
	 *
	 * @param	array	The souce data to save.
	 *
	 * @return	boolean	True on success, false otherwise and internal error set.
	 * @since	1.6
	 */
	public function save($data)
	{
            $faviconsmodel = new FaviconModelFavicons;
            $pluginparams = json_decode($faviconsmodel->plugin->params,true);
            if(!is_array($pluginparams['assignments'])) unset($pluginparams['assignments']);
            if(isset($data['menus']) && count($data['menus'])) {
                $pluginparams['assignments'][$data['iconid']]=$data['menus'];
            } else {
                unset($pluginparams['assignments'][$data['iconid']]);
            }
            $newparams = json_encode((object)$pluginparams);
            $db = JFactory::getDbo();
            $query="UPDATE #__extensions SET params = '".$newparams."' WHERE extension_id = ".$faviconsmodel->plugin->extension_id;
            $db->setQuery($query);
            $db->query();
            return true;
	}
}