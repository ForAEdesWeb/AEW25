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

//-- Import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * M2C item Controller.
 *
 * @package M2C
 */
class M2CControllerM2C extends JControllerForm
{
    protected $view_list = 'M2CList';

    /**
     * Constructor.
     */
    public function __construct($config = array())
    {
        $foo = 'Do something here..';

        parent::__construct($config);
    }//function
}//class
