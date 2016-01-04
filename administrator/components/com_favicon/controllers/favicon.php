<?php
/**
 * @version		$Id: favicon.php 16398 2010-04-24 00:28:26Z mrichey $
 * @copyright	Copyright (C) 2005 - 2011 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');
jimport('joomla.filesystem.file');

/**
 * Favicon list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_favicon
 * @since		1.6
 */
class FaviconControllerFavicon extends JControllerAdmin
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
		$this->registerTask('create','edit');
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Favicon', $prefix = 'FaviconModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

        public function edit() {
            $app = JFactory::getApplication();
            $context = 'com_favicon.edit.favicon.icon';
            $path=JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_favicon'.DIRECTORY_SEPARATOR;
            $tempfile = $path.'temp'.DIRECTORY_SEPARATOR.'favicon.ico';
            if($this->task == 'edit') {
                $id=JRequest::getInt('id');
                $iconfile = $path.'icons'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'favicon.ico';
                JFile::copy($iconfile,$tempfile);
                $app->setUserState($context, $id);
            } else {
                $model = $this->getModel();
                if(JFile::exists($tempfile)) JFile::delete($tempfile);
                $icon = $model->getIcon(0);
                $model->saveTemp($icon);
                $app->setUserState($context, 0);
            }
            $this->setRedirect('index.php?option=com_favicon&view=favicon');
        }
        
        public function addimage() {
            $result = $this->getModel()->addImage();
            if($result) {
                $this->setMessage(JText::_('COM_FAVICON_IMAGE_ADDED'));
            } else {
                $this->setMessage(JText::_('COM_FAVICON_FAILED_TO_ADD_IMAGE'),'error');
            }
            $this->setRedirect('index.php?option=com_favicon&view=favicon');
        }
        
        public function save() {
            $model = $this->getModel();
            $icon = $model->getIcon();
            if($model->get16($icon) === false){
                $this->setMessage(JText::_('COM_FAVICON_ICON_CONTAINS_NO_16X16'),'error');
                $this->setRedirect('index.php?option=com_favicon&view=favicon');
            } else {
                $result = $this->getModel()->save();
                if($result) {
                    $this->setMessage(JText::_('COM_FAVICON_NEW_ICON_SAVED'));
                    $this->setRedirect('index.php?option=com_favicon');
                } else {
                    $this->setMessage(JText::_('COM_FAVICON_FAILED_TO_SAVE_NEW_ICON'),'error');
                    $this->setRedirect('index.php?option=com_favicon&view=favicon');
                }
            }
        }

        public function apply() {
            $model = $this->getModel();
            $icon = $model->getIcon();
            if($model->get16($icon) === false){
                $this->setMessage(JText::_('COM_FAVICON_ICON_CONTAINS_NO_16X16'),'error');
                $this->setRedirect('index.php?option=com_favicon&view=favicon');
            } else {
                $result = $this->getModel()->apply();
                if($result) {
                    $this->setMessage(JText::_('COM_FAVICON_ICON_SAVED'));
                    $this->setRedirect('index.php?option=com_favicon');
                } else {
                    $this->setMessage(JText::_('COM_FAVICON_FAILED_TO_SAVE_ICON'),'error');
                    $this->setRedirect('index.php?option=com_favicon&view=favicon');
                }
            }
        }

        public function template() {
                $model = $this->getModel();
                if($model->getTemplate()) $model->backupTemplate();
                $result = $model->saveTemplate();
                if($result) {
                    $this->setMessage(JText::_('COM_FAVICON_ICON_APPLIED_TO_TEMPLATE'));
                    $this->setRedirect('index.php?option=com_favicon');
                } else {
                    $this->setMessage(JText::_('COM_FAVICON_FAILED_TO_APPLY_TO_TEMPLATE'),'error');
                    $this->setRedirect('index.php?option=com_favicon&view=favicon');
                }
        }

        public function remove() {
            $result = $this->getModel()->removeImages();
            if($result) {
                $this->setMessage(JText::_('COM_FAVICON_IMAGES_REMOVED'));
            } else {
                $this->setMessage(JText::_('COM_FAVICON_FAILED_TO_REMOVE_IMAGES'));
            }
            $this->setRedirect('index.php?option=com_favicon&view=favicon');
        }

        public function image() {
            $model = $this->getModel();
            $iconid = JRequest::getInt('id');
            $iconkey = JRequest::getInt('key');
            $iconobj = $model->getIcon($iconid);
            $image = $iconobj->getImage($iconkey);
            $imageinfo = $iconobj->images[$iconkey]->_entry;
            header('Content-Disposition: Attachment;filename='.$imageinfo['Height'].'x'.$imageinfo['Width'].'.png');
            header("Content-Type: image/png");
            imagepng($image);
            exit();
        }
}
