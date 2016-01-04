<?php
# HD-CustomCSS          	          	          	              
# Copyright (C) 2013 by Hyde-Design  	   	   	   	   
# Homepage   : www.hyde-design.co.uk		   	   	   
# Author     : Hyde-Design    		   	   	   	   
# Email      : rich@hyde-design.co.uk 	   	   	   
# Version    : 1.8                        	   	    	
# License    : http://www.gnu.org/copyleft/gpl.html GNU/GPL    

// no direct access
defined('_JEXEC') or die('Restricted access');

//get the params

	$custom_css    	= $params->get("customcss", "/* Custom CSS */");
	$browser_css    = $params->get("browsercss", "all");
	$browser        = strtolower($_SERVER['HTTP_USER_AGENT']);
	$activate_css   = $params->get("externalcss", "0");
	$file_css    	= $params->get("externalcssfile", "");

global $mainframe;
if ($browser_css=="all") { if ($activate_css=="0") { echo '<style type="text/css">
'.$custom_css.'
</style>';} else {echo '<link rel="stylesheet" href="'.$file_css.'" type="text/css" />';}}
                           
elseif (preg_match('~'.$browser_css.'~', $browser)) { if ($activate_css=="0") { echo '<style type="text/css">
'.$custom_css.'
</style>';} else {echo '<link rel="stylesheet" href="'.$file_css.'" type="text/css" />';}}