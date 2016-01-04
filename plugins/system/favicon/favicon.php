<?php
/**
 * @version		$Id: favicon.php 20196 2011-03-04 02:40:25Z mrichey $
 * @package		plg_system_favicon
 * @copyright	Copyright (C) 2005 - 2011 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemFavicon extends JPlugin
 {
    	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemFavicon( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}

        function onAfterRender()
        {
            $app = JFactory::getApplication();
            $doc = JFactory::getDocument();
            // we don't care about /administrator or pages that aren't html
            if($app->isAdmin() || JRequest::getVar('format') == 'raw' || ($app->isSite() && $doc->getType() != 'html')) return true;

            // default favicon and individual menu-item assignments
            $default = (int)$this->params->get('default',0);
            $assignmentsobject = (array)$this->params->get('assignments');
            $assignments=array();
            foreach($assignmentsobject as $key=>$assignment) {
                $assignments[$key]=(array)$assignment;
            }

            // no default and no assignments = no point
            if (!$default && !count($assignments)) return true;

            $favicon = $this->getFavicon($default,$assignments);
            
            // no favicon found and no valid default
            if(!$favicon) return true;
            
            $buffer = JResponse::getBody();
            $link = '<link href="'.$favicon.'" rel="shortcut icon" type="image/vnd.microsoft.icon" />';
            preg_match('/<link href=.* rel="shortcut icon" type=.*\/>/',$buffer,$position,PREG_OFFSET_CAPTURE);
            if(isset($position[0]) && isset($position[0][0]) && strlen($position[0][0])) {
                $buffer = str_replace($position[0][0],$link,$buffer);
            } else {
                $buffer = str_replace("</head>","\t$link\n</head>",$buffer);
            }
            JResponse::setBody($buffer);
        }

        public function getFavicon($default,$assignments)
	{
                // set the default favicon
                $iconpath = JURI::root(true).'/media/com_favicon/icons/';
                $favicon = $iconpath.$default.'/favicon.ico';

                // if there are no assignments, we stop processing and return the default
                if(!count($assignments)) return $favicon;

                // get the current Itemid.
                $menu = JSite::getMenu();
                $itemid = @$menu->getActive()->id;

                // look for a favicon assignment for this itemid
                $found=false;
                foreach($assignments as $key=>$assignment) {
                    if(in_array($itemid,$assignment)) {
                        $found=true;
                        $favicon = $iconpath.$key.'/favicon.ico';
                        continue;
                    }
                }
                /* didn't find a match for this itemid, now checking parent itemids
                 * selecting the first favicon assignment found
                 */
                if(!$found) {
                    $tree = @$menu->getActive()->tree;
                    /* tree is returned in forward order so we reverse it so it starts with the current
                     * and we work our way to the end of the array - which, once reversed, is the menu root
                     */
                    if(is_array($tree)) {
                        array_reverse($tree);
                        if(count($tree) >= 1) {
                            foreach($tree as $item) {
                                if($found) continue;
                                foreach($assignments as $key=>$assignment) {
                                    if(in_array($item,$assignment)) {
                                        $found=true;
                                        $favicon = $iconpath.$key.'/favicon.ico';
                                        continue 2;
                                    }
                                }
                            }
                        }
                    }
                }
                if(!$found && $default == 0) return false;
                return $favicon;
	}
}