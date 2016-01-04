<?php
/**
 * @copyright	Copyright (C) 2005 - 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'floIcon.php');
class FaviconViewFavicon extends JViewLegacy
 {
    public $iconfile;
    public $icon;
    public $iconid;
    public $model;
    public $context;
    public function display($tpl = null) {
        JHtml::_('behavior.modal');
        $app = JFactory::getApplication();
        $this->context = 'com_favicon.edit.favicon.icon';
        $this->iconid = $app->getUserState($this->context);
        $this->model = $this->getModel();
        $this->icon = $this->model->getIcon();
        $this->addToolbar($this->iconid);
        parent::display($tpl);
    }
    protected function addToolbar($iconid)
    {
        $subtitle = ($iconid>0)?'COM_FAVICON_EDIT_FAVICON':'COM_FAVICON_NEW_FAVICON';
        $subtitle = JText::_('COM_FAVICON').' - '.JText::_($subtitle);
        if($iconid > 0) {
            $subtitle.=' '.$iconid;
            JToolBarHelper::custom('favicon.apply', 'apply.png', 'apply_f2.png', 'COM_FAVICON_APPLY', false);
        } else {
            $subtitle.=' '.$this->model->getNextId();
            JToolBarHelper::custom('favicon.save', 'save.png', 'save_f2.png', 'COM_FAVICON_SAVE', false);
        }
        JToolBarHelper::title($subtitle, 'faviconedit');
        JToolBarHelper::cancel();
    }
}