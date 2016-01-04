<?php
/**
 * ------------------------------------------------------------------------
 * JA Bookmark plugin for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');
class JFormFieldJAAddtoany extends JFormField
{
    /*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
    var $type = 'JAAddtoany';

    function getInput()
    {
		$value = $this->value ? $this->value : (string) $this->element['default'];       
        ob_start();
        require_once(dirname(__FILE__).'/jaaddtoany/layout.html');
        $string = ob_get_contents();
        ob_end_clean();
		
        return $string;
    }
}
?>