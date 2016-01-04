<?php

/**
 * @name		Modules Manager CK
 * @package		com_modulesmanagerck
 * @copyright	Copyright (C) 2013. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class TemplateckModelMobile extends JModelList {

	/**
	 * mobile layout id
	 *
	 * @var int
	 */
	var $_id;

	/**
	 * Template id
	 *
	 * @var int
	 */
	var $_templateid;

	/**
	 * Template object
	 *
	 * @var object
	 */
	var $_data;

	/**
	 * Template mobile object
	 *
	 * @var object
	 */
	var $_datamobile;

	/**
	 * Template names object
	 *
	 * @var object
	 */
	var $_templates;

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct() {
		parent::__construct();

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);
		$this->_templateid = JRequest::getInt('templateid');
	}

	/**
	 * Method to set the template identifier
	 *
	 * @access	public
	 * @param	int category identifier
	 * @return	void
	 */
	function setId($id) {
		// Set id and wipe data
		$this->_id = $id;
		$this->_data = null;
	}

	/**
	 * Method to get the mobile layout identifier
	 *
	 * @access	public
	 * @return	void
	 */
	function getId() {
		return $this->_id;
	}

	/**
	 * Method to get the template identifier
	 *
	 * @access	public
	 * @return	void
	 */
	function getTemplateId() {
		return $this->_templateid;
	}

	/**
	 * Method to get a template
	 * @return object with data
	 */
	function &getTemplate() {
		// Load the data
		if (empty($this->_data)) {
			$query = ' SELECT * FROM #__templateck_templates' .
					' WHERE id = ' . $this->_templateid;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->joomlaversion = 'j15';
			$this->_data->name = null;
			$this->_data->ordering = null;
			$this->_data->published = 1;
			$this->_data->creationDate = null;
			$this->_data->author = null;
			$this->_data->authorEmail = null;
			$this->_data->authorUrl = null;
			$this->_data->copyright = null;
			$this->_data->license = null;
			$this->_data->version = null;
			$this->_data->description = null;
			$this->_data->htmlcode = '<div id="body" class="body"><div class="controlCss isControl" id="bodycss">BODY CSS</div><div id="wrapper" class="wrapper"><div class="controlCss isControl" id="wrappercss">WRAPPER CSS</div><div class="clr"></div></div></div>';
			$this->_data->theme = null;
			$this->_data->cssparams = null;
			$this->_data->htmlcode_responsive = json_encode('');
		}

		if (!$this->_data->htmlcode_responsive)
			$this->_data->htmlcode_responsive = $this->genHtmlcoderesponsive($this->_data->htmlcode);

		return $this->_data;
	}

	/**
	 * Method to get the code for mobile
	 * @return object with data
	 */
	function &getMobilecode() {
		// Load the data
		if (empty($this->_datamobile)) {
			$query = ' SELECT * FROM #__templateck_mobile' .
					' WHERE templateid = ' . $this->_templateid;
			$this->_db->setQuery($query);
			$this->_datamobile = $this->_db->loadObject();
		}

		if (!$this->_datamobile) {
			$this->_datamobile = new stdClass();
			$this->_datamobile->id = 0;
			$this->_datamobile->templateid = $this->_templateid;
			$this->_datamobile->resolution1 = null;
			$this->_datamobile->resolution2 = null;
			$this->_datamobile->resolution3 = null;
			$this->_datamobile->resolution4 = null;
		} else {
			$id = $this->_datamobile->id;
			$templateid = $this->_datamobile->templateid;
			$this->_datamobile = $this->uncompressData($this->_datamobile);
			$this->_datamobile->templateid = $templateid;
			$this->_datamobile->id = $id;
		}

		return $this->_datamobile;
	}

	/**
	 * Method to transform the html code to responsive interface
	 *
	 * @access	public
	 * @return	string	html code
	 */
	private function uncompressData($data) {
		$resolutions = array('1', '2', '3', '4');
		$fdata = new stdClass();
		foreach ($resolutions as $resolution) {
			$val = 'resolution' . $resolution;
			$data->$val = Json_decode($data->$val);
			$fdata->$val = new stdClass();
			foreach ($data->$val as $row) {
				$ckid = $row->ckid;
				$fdata->$val->$ckid = $row->ckmobile;
			}
		}
		return $fdata;
	}

	/**
	 * Method to transform the html code to responsive interface
	 *
	 * @access	public
	 * @return	string	html code
	 */
	function genHtmlcoderesponsive($html) {
		return $html;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store() {

		$row = $this->getTable();
		$data = JRequest::get('post');

		// needed for html encoding in table
		//$data['htmlcode_responsive'] = JRequest::getVar('htmlcode_responsive', '', 'post', 'string', JREQUEST_ALLOWRAW);
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($row->getErrorMsg());
			return false;
		}

		$this->setId($row->id);

		return true;
	}

}
