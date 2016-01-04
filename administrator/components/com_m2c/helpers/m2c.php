<?php
/**
 * @version SVN: $Id: header.php 18 2010-11-08 01:10:19Z elkuku $
 * @package    M2C
 * @subpackage Helpers
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * M2C component helper.
 */
class M2CHelper
{
    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($submenu)
    {
        $document = JFactory::getDocument();

        $document->addStyleDeclaration('.icon-48-com_m2c '
        .'{background-image: url(../media/com_m2c/images/com_m2c-48x48.png);}');

    }//function
}//class
