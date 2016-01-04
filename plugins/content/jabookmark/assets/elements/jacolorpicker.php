<?php
/**
 * ------------------------------------------------------------------------
 * JA Bookmark plugin for J2.5 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
//Upgraded by ThangNN
class JFormFieldJacolorpicker extends JFormField
{
    /*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
    var $type = 'Jacolorpicker';


    function getInput()
    {

        $uri = $this->getCurrentURL();
        $this->loadjscss($uri);
        $value = $this->value ? $this->value : (string) $this->element['default'];
        $string = '<input class="color" value="' . $value . '" name="' . $this->name . '" >';
        return $string;
    }


    /**
     * get current url
     */
    function getCurrentURL()
    {
        $uri = str_replace(DS, "/", str_replace(JPATH_SITE, JURI::base(), dirname(__FILE__)));
        $uri = str_replace("/administrator", "", $uri);
        return $uri;
    }


    /**
     * load css and js file
     */
    function loadjscss($uri)
    {
        if (!defined('_JA_PARAM_HELPER_RAINBOW_')) {
            define('_JA_PARAM_HELPER_RAINBOW_', 1);
            JHTML::script($uri . "/" . 'jacolorpicker/jscolor.js');
        }

    }
}
?>