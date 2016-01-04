<?php
/**
 * @version SVN: $Id: header.php 18 2010-11-08 01:10:19Z elkuku $
 * @package    M2C
 * @subpackage Models
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//-- Import Joomla formrule library
jimport('joomla.form.formrule');

/**
 * Form Rule class for the  M2C Component.
 *
 * @package M2C
 */
class JFormRuleGreeting extends JFormRule
{
    /**
     * The regular expression.
     *
     * @var string
     */
    protected $regex = '^[^0-9]+$';
}//class
