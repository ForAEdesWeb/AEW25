<?php
/**
 * @version SVN: $Id: header.php 18 2010-11-08 01:10:19Z elkuku $
 * @package    M2C
 * @subpackage Controllers
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//-- Import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * M2C List controller.
 *
 * @package M2C
 */
class M2CControllerM2CList extends JController
{
    /**
     * Remove record(s).
     *
     * @return void
     */
    public function remove()
    {
        JRequest::checkToken() || jexit(JText::_('JInvalid_Token'));

        $model = $this->getModel('M2CList');

        if($model->remove())
        {
            $msg = JText::_('Records have been removed');
            $type = 'message';
        }
        else
        {
            $msg = JText::sprintf('One or more records could not be deleted %s', implode('<br />', $model->getErrors()));
            $type = 'error';
        }

        $this->setRedirect('index.php?option=com_m2c', $msg, $type);
    }//function
}//class
