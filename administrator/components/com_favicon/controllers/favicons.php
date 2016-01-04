<?php
/**
 * @version		$Id: default.php 16398 2010-04-24 00:28:26Z mrichey $
 * @copyright	Copyright (C) 2005 - 2011 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Favicon list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_favicon
 * @since		1.6
 */
class FaviconControllerFavicons extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_FAVICON_FAVICONS';
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Favicons', $prefix = 'FaviconModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

        public function setdefault() {
            $id = JRequest::getInt('id',0);
            $model = $this->getModel();
            $return = $model->setdefault($id);
            if($return) {
                $this->setMessage(JText::_('COM_FAVICON_NEW_DEFAULT_SET'));
            } else {
                $this->setMessage(JText::_('COM_FAVICON_NEW_DEFAULT_SET_FAILED'),'error');
            }
            $this->setRedirect('index.php?option=com_favicon');
        }
        public function deleteicon() {
            $id = JRequest::getInt('id',0);
            $model = $this->getModel();
            $return = $model->deleteIcon($id);
            if($return) {
                $this->setMessage(JText::_('COM_FAVICON_ICON_DELETED'));
            } else {
                $this->setMessage(JText::_('COM_FAVICON_ICON_NOT_DELETED'),'error');
            }
            $this->setRedirect('index.php?option=com_favicon');

        }
}
