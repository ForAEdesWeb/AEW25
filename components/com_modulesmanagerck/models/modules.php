<?php
/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Templateck records.
 */
class ModulesmanagerckModelModules extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {

        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $this->setState('list.start', $limitstart);

        // List state information.
        parent::populateState();
    }


	/**
	 * private function to load the fonts in the page
	 */
	function getData() {
		$db = JFactory::getDBO();
		$query = "SELECT *
				FROM #__extensions
				WHERE type='module'
				AND client_id = 1
				AND enabled= 1
				ORDER BY ordering";
		$db->setQuery((string) $query);
		try {
			$rows = $db->loadObjectList();
		} catch (Exception $e) {
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}
		return $rows;
	}

	/*
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.extension_id, a.name, a.element AS module');
		$query->from($db->quoteName('#__extensions').' AS a');

		// Filter by module
		$query->where('a.type = '.$db->Quote('module'));

		// Filter by client.
		$clientId = $this->getState('filter.client_id');
		$query->where('a.client_id = '.(int) $clientId);

		// Filter by enabled
		// $query->where('a.enabled = 1');

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.ordering')).' '.$db->escape($this->getState('list.direction', 'ASC')));
// var_dump($query);
		//echo nl2br(str_replace('#__','jos_',$query));

		$query = "SELECT *,element AS module FROM #__extensions WHERE client_id=0 AND enabled = 1 AND type='module'";
		return $query;
	}

	/**
	 * Method to get a list of items.
	 *
	 * @return	mixed	An array of objects on success, false on failure.
	 */
	public function getItems()
	{
		// Get the list of items from the database.
		// $items = parent::getItems();

		$db = JFactory::getDBO();
		$query = "SELECT *,element AS module
				FROM #__extensions
				WHERE type='module'
				AND client_id = 0
				AND enabled= 1
				ORDER BY name";
		$db->setQuery((string) $query);
		try {
			$items = $db->loadObjectList();
		} catch (Exception $e) {
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}

		$client = JApplicationHelper::getClientInfo($this->getState('filter.client_id', 0));
		$lang	= JFactory::getLanguage();

		// Loop through the results to add the XML metadata,
		// and load language support.
		foreach ($items as &$item) {
			$path = JPath::clean($client->path.'/modules/'.$item->module.'/'.$item->module.'.xml');
			if (file_exists($path)) {
				$item->xml = simplexml_load_file($path);
			} else {
				$item->xml = null;
			}

					// 1.5 Format; Core files or language packs then
			// 1.6 3PD Extension Support
				$lang->load($item->module.'.sys', $client->path, null, false, false)
			||	$lang->load($item->module.'.sys', $client->path.'/modules/'.$item->module, null, false, false)
			||	$lang->load($item->module.'.sys', $client->path, $lang->getDefault(), false, false)
			||	$lang->load($item->module.'.sys', $client->path.'/modules/'.$item->module, $lang->getDefault(), false, false);
			$item->name	= JText::_($item->name);

			if (isset($item->xml) && $text = trim($item->xml->description)) {
				$item->desc = JText::_($text);
			}
			else {
				$item->desc = JText::_('COM_MODULES_NODESCRIPTION');
			}
		}
		$items = JArrayHelper::sortObjects($items, 'name', 1, true, $lang->getLocale());

		// TODO: Use the cached XML from the extensions table?

		return $items;
	}

}
