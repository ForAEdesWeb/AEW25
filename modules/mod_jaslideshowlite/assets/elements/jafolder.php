<?php

/**
 * ------------------------------------------------------------------------
 * JA Slideshow Lite Module for J25 & J3.2
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
jimport('joomla.filesystem.folder');

class JFormFieldJafolder extends JFormField {

    protected $type = 'jafolder';

    public function getInput() {        
        $jaFolder = array();
		$jaFolder[0] = new stdClass();
        $jaFolder[0]->name = 'images';
        $jaFolder[0]->text = 'images';
        $jaFolder[0]->value = 'images/';
        
        $this->buildTree('images', 0, '', $jaFolder);

        // Initialize field attributes.
        $class = $this->element['class'] ? (string) $this->element['class'] : '';
        $attr = '';
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
        $attr .= ' class="inputbox ' . $class . '"'; 
        if(substr($this->value,0,1) =='/'){
        	$this->value = substr($this->value,1);
        }
		if(substr($this->value, -1) != '/'){
			$this->value = $this->value . '/';
		}
        return JHTML::_('select.genericlist', $jaFolder, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);        
    }

    public function buildTree($folder, $depth, $path, &$jaFolder) {
        if($path){
			$folder = $path . '/' . $folder;
		}
        $subs = JFolder::folders(JPATH_ROOT . '/' . $folder);
		if(!empty($subs)){
			foreach ($subs as $sub) {
				$obj = new stdClass();
				$obj->name = $sub;
				$obj->text = str_repeat('- - ', $depth + 1) . $sub;
				$obj->value = $folder . '/' . $sub . '/';
				$jaFolder[] = $obj;
				$this->buildTree($sub, $depth + 1, $folder, $jaFolder);
			}
		}
    }

}