<?php
/**
 * @version		$Id: view.html.php 17071 2010-05-15 08:03:01Z chdemko $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.filesystem.folder');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'favicon.php');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'favicons.php');
/**
 * View to edit a style.
 */
class FaviconViewAssign extends JViewLegacy
{
	protected $form;
        public $id;
        public $icon;
        public $icons;
        public $iconkey;
        public $plugin;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
            if(JRequest::getVar('closewindow',false)=='true') {
                JFactory::getDocument()->addScriptDeclaration("window.addEvent('domready',function(){ window.parent.location.reload(true); });");
            }
                JRequest::setVar('tmpl','component');
                $this->id = JRequest::getVar('id');
                $this->icons = JFolder::folders(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'com_favicon'.DIRECTORY_SEPARATOR.'icons');
                $faviconmodel = new FaviconModelFavicon;
                $this->iconkey = $faviconmodel->get16($faviconmodel->getIcon($this->id));
                $faviconsmodel = new FaviconModelFavicons;
                $this->plugin = json_decode($faviconsmodel->plugin->params,true);
		$this->form = $this->get('Form');
                $this->colorizeAssignments();
		parent::display($tpl);
	}
        
        private function colorizeAssignments() {
            $assigned = new JObject;
            if(!is_array($this->plugin['assignments'])) $this->plugin['assignments']=array();
            foreach($this->plugin['assignments'] as $icon=>$assignment) {
                $propname='icon'.$icon;
                $assignments=array();
                foreach($assignment as $menuitem) {
                    $assignments[]=$menuitem;
                }
                $assigned->set($propname,$assignments);
            }
            $doc = JFactory::getDocument();
            $default = isset($this->plugin['default'])?$this->plugin['default']:false;
            $assignments = $this->plugin['assignments'];
            $script=array();
            $script[]='var icons = '.json_encode($this->icons).';';
            $script[]='var current_icon = '.$this->id.';';
            $script[]='var assignments = '.json_encode($assigned).';';
            $script[]="window.addEvent('domready',function(){";
            $script[]="\tvar options = document.id('jform_menus').getElements('option');";
            $script[]="\toptions.each(function(el){";
            $script[]="\t\tel.setStyles({'padding-left':'18px','height':'20px','display':'block'});";
            $script[]="\t\tel.setProperty('id','item' + el.value);";
            $script[]="\t});";
            $script[]="\ticons.each(function(icon){";
            $script[]="\t\tvar propname = 'icon' + icon;";
            $script[]="\t\tif(Object.keys(assignments).contains(propname)) {";
            $script[]="\t\t\tvar itemlist = assignments[propname];";
            $script[]="\t\t\titemlist.each(function(item){";
            $script[]="\t\t\t\tvar itemid = 'item' + item;";
            $script[]="\t\t\t\tvar option = document.id(itemid);";
            $script[]="\t\t\t\tif(typeof(option) != 'undefined') {";
            $script[]="\t\t\t\t\toption.setStyles({'background-image':'url(\'".JURI::base(true)."/index.php?option=com_favicon&task=favicon.image&id=' + icon  + '&key=0\')','background-repeat':'no-repeat'});";
            $script[]="\t\t\t\t\tif(current_icon != icon) option.disabled=true;";
            $script[]="\t\t\t\t}";
            $script[]="\t\t\t});";
            $script[]="\t\t}";
            $script[]="\t});";
            $script[]="});";
            $doc->addScriptDeclaration(implode("\n",$script));
        }

}
