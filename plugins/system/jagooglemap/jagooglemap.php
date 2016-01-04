<?php
/**
 * ------------------------------------------------------------------------
 * JA System Google Map plugin for J2.5 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
/**
 *
 * JA GOOGLE MAP PLUGIN SYSTEM CLASS
 * @author JoomlArt
 *
 */
class plgSystemJagooglemap extends JPlugin
{
    protected $_plgCode = "#{jamap(.*?)}#i";
    protected $mapSetting = array();
    protected $mapId = null;


    /**
     *
     * Construct JA Googla Map
     * @param object $subject
     * @param object $config
     */
    function plgSystemJagooglemap(&$subject, $config)
    {
        $mainframe = JFactory::getApplication();
        parent::__construct($subject, $config);

        $this->plugin = JPluginHelper::getPlugin('system', 'jagooglemap');
        $this->plgParams = new JRegistry();
        $this->plgParams->loadString($this->plugin->params);
    }
    
    function onBeforeRender() {
    	JHtml::_('behavior.framework');
    }

    /**
     *
     * Process data after render
     * @return string
     */
    function onAfterRender()
    {
        $mainframe = JFactory::getApplication();
        global $option;

        if ($mainframe->isAdmin()) {
            return;
        }
        $body = JResponse::getBody();

        $plgParams = $this->plgParams;
        $disable_map = $plgParams->get('disable_map', 0);

        if ($disable_map) {
            $body = $this->removeCode($body);
            JResponse::setBody($body);
            return;
        }

        if (!preg_match($this->_plgCode, $body)) {
            return;
        }
        
        $body = $this->stylesheet($this->plugin, $body);
        
        $body = preg_replace_callback($this->_plgCode, array($this, 'genMap'), $body);

        JResponse::setBody($body);
    }
    
    function genMap($matches) {
    	static $mapid = 0;
    	$mapid++;
    	
        $this->mapId = $mapid;
    	$this->mapSetting = $this->parseParams($matches[0]);
        $output = $this->loadLayout($this->plugin, 'default');
        return $output;
    }
    
    /**
     *
     * Parse Params to array
     * @param string $string
     * @return array
     */
    function parseParams($string)
    {
        $string = html_entity_decode($string, ENT_QUOTES);
        $regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
        $params = null;
        if (preg_match_all($regex, $string, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $key = $matches[1][$i];
                $value = $matches[3][$i] ? $matches[3][$i] : ($matches[4][$i] ? $matches[4][$i] : $matches[5][$i]);
                $params[$key] = $value;
            }
        }
        return $params;
    }


    /**
     *
     * Remove map code tag
     * @param string $content
     * @return string
     */
    function removeCode($content)
    {
        return preg_replace($this->_plgCode, '', $content);
    }


    /**
     *
     * Get layout for display
     * @param object $plugin
     * @param string $layout
     * @return string
     */
    function getLayoutPath($plugin, $layout = 'default')
    {

        $mainframe = JFactory::getApplication();

        // Build the template and base path for the layout
        $tPath = JPATH_BASE . '/templates/' . $mainframe->getTemplate() . '/html/' . $plugin->name . '/' . $layout . '.php';
        $bPath = JPATH_BASE . '/plugins/' . $plugin->type . '/' . $plugin->name . '/tmpl/' . $layout . '.php';
        // If the template has a layout override use it
        if (file_exists($tPath)) {
            return $tPath;
        } elseif (file_exists($bPath)) {
            return $bPath;
        }
        return '';
    }


    /**
     *
     * Load content into layout
     * @param object $plugin
     * @param string $layout
     * @return string
     */
    function loadLayout($plugin, $layout = 'default')
    {
        $layout_path = $this->getLayoutPath($plugin, $layout);
        if ($layout_path) {
            ob_start();
            require $layout_path;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        return '';
    }


    /**
     *
     * Set style for map display
     * @param object $plugin
     * @param string $bodyString
     * @return string
     */
    function stylesheet($plugin, $bodyString)
    {
        $mainframe = JFactory::getApplication();
        $params = new JRegistry();
        $params->loadString($this->plugin->params);

        $assets_url = JURI::base() . 'plugins/' . $plugin->type . '/' . $plugin->name . '/';
        $headtag = array();
        $headtag[] = '<link href="' . $assets_url . 'assets/style.css" type="text/css" rel="stylesheet" />';
        $headtag[] = '<script src="' . $assets_url . 'assets/script.js" type="text/javascript" ></script>';

        //google map
        $api_version = $params->get('api_version', '2');
        $api_key = $params->get('api_key', '');
        $sensor = ($params->get('sensor', 1) == 1) ? 'true' : 'false';

        //$map_js = 'http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=' . $sensor . '&amp;key=' . $api_key;
        $map_js = 'https://maps.googleapis.com/maps/api/js?sensor=' . $sensor . '&amp;key=' . $api_key;//v3
        $headtag[] = '<script src="' . $map_js . '" type="text/javascript" ></script>';

        $bodyString = str_replace('</head>', "\t" . implode("\n", $headtag) . "\n</head>", $bodyString);
        return $bodyString;
    }
}