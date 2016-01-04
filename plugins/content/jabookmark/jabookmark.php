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

/**
 * JA Bookmark Plugin is a module using for Display icons for your online social networking sites.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// include libs.
jimport('joomla.plugin.plugin');
jimport('joomla.application.module.helper');

/**
 * JA Bookmark plugin class
 */
class plgContentJabookmark extends JPlugin
{

    /**
     * @var JParameter $params.
     *
     * @access public.
     */
    var $params = null;

    /**
     * @var stdClass store Plugin Information
     *
     * @access public
     */
    var $plugin;

    /**
     * var $string $__output store html output.
     *
     * @access private
     */
    var $__output = null;


    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments, NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param	object	$subject The object to observe
     * @param	object 	$params  The object to observe
     */
    function plgContentJabookmark(&$subject, $params = null)
    {

        if (!$subject)
            return;
        parent::__construct($subject, $params);

        $this->plugin = JPluginHelper::getPlugin('content', 'jabookmark');

        $this->params = new JRegistry();
        $this->params->loadString($this->plugin->params);

    }


    /**
     * check valid showing addthis service.
     *
     * @param JParameter $params.
     * @param stdClass $article.
     * @return boolean.
     */
    function isValidShowing($context,$params, $article, $mode)
    {
		if(!property_exists($article,'text')) $article->text = '';
		$checked = false;
		if($context != 'com_k2.item' && !$this->isDetailPage()) {
    		return;
    	}	
        if ($mode != 'auto') {
            return true;
        }
		$source = $params->get('source','com_content');
		
		$contextArray = explode('.', $context);
		if($source != 'both' && $source != $contextArray[0]){
			return;
		}
		
		switch ($source){
			case 'com_content':		
				$checked = $this->checkCatids($params,$article,'mode-auto-category');
				break;
			case 'com_k2':
				$checked = $this->checkCatids($params,$article,'mode-automatic-k2catsid');
				break;
			case 'both':
			default:
				if ($context == 'com_content.article' || $context == 'com_content.featured' || $context =='com_content.category') {
					$checked = $this->checkCatids($params,$article,'mode-auto-category');
				}
				if ($context == 'com_k2.itemlist' || $context == 'com_k2.item') {
					$checked = $this->checkCatids($params,$article,'mode-automatic-k2catsid');
				}
				break;	
		}
        return $checked;
    }
    /**
     *
     * Detect page detail for disqus
     * @return boolean
     */
    function isDetailPage()
    {
        $option = JRequest::getVar('option');
        $view = JRequest::getVar('view');
        //if its a detail page
        if (($option == 'com_k2' && $view == 'item') || ($option == 'com_content' && $view == 'article')) {
            return true;
        }
        return false;
    }
    /**
     * Check categories id
     * */
    function checkCatids($params,$article,$key){
    	if ($params->get($key, '') == '') return ;
    	$categories = $params->get($key);
    	
        if (!is_array($categories)) {
            $categories = split(',', $params->get($key));
        } else {
            if ($categories[0] == '') {
                return true;
            }
        }
        if (!empty($categories)) {
        	if(isset($article->catid)){
            	return in_array($article->catid, $categories);
        	}
        }
    }
    /**
     * only render button some postions such as TOP, BOTTOM, CUSTOM LOCATION.
     */
    function onContentPrepare($context, &$article, &$params, $limitstart = 0)
    {
    	$isK2 = (strpos($context, 'com_k2') !== false);
		
    	if($this->params->get('mode') == 'auto'){
			if($this->params->get('location') == 'BeforeContent'){
				$text = $this->render($this->params, $context,$article);
				
				if($isK2) {
	    			if($params->get('itemIntroText', 1) && isset($article->introtext)) {
	    				$article->introtext = $text.$article->introtext;
	    			} elseif($params->get('itemFullText', 1) && isset($article->fulltext)) {
	    				$article->fulltext = $text.$article->fulltext;
	    			} else {
	    				$article->text = $text.$article->text;
	    			}
	    		} else {
					$article->text = $text.$article->text;	
	    		}	
				//var_dump($article->text);jexit();	
			}
			if($this->params->get('location')=='AfterContent'){
				$text = $this->render($this->params, $context,$article);
				if($isK2) {
	    			if($params->get('itemFullText', 1) && isset($article->fulltext)) {
	    				$article->fulltext = $article->fulltext.$text;
	    			} elseif($params->get('itemIntroText', 1) && isset($article->introtext)) {
	    				$article->introtext = $article->introtext.$text;
	    			} else {
	    				$article->text = $article->text.$text;
	    			}
	    		} else {
					$article->text = $article->text.$text;
	    		}
			}
	        return true;
    	}else if($this->params->get('mode') == 'manual'){
			$this->render($this->params,$context, $article);
			return true;
		}else{
			if(isset($article->text)){
				$article->text = $this->removeConfigString($article->text);
				return true;
			}
		}
    }


    /**
     *
     * Only render button at postion after content.
     * @param string $context
     * @param object $article
     * @param object $params
     * @param int $page
     * @return string text of content
     */
    function onContentAfterDisplay($context, &$article, &$params, $limitstart = 0)
    {
	
        if ($this->params->get('location') == 'onContentAfterDisplay' && $this->params->get('mode') == 'auto') {
            $text = $this->render($this->params,$context, $article);
            return $text;
        }
    }


    /**
     *
     * Only render button at postion before content.
     * @param string $context
     * @param object $article
     * @param object $params
     * @param int $page
     * @return string text of content
     */
    function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 0)
    {
        if ($this->params->get('location') == 'onContentBeforeDisplay' && $this->params->get('mode') == 'auto') {
            $text = $this->render($this->params, $context,$article);
            return $text;
        }
    }
	
    /**
     *
     * Process content after display
     * @param string $context key type component
     * @param object $article
     * @param object $params
     * @param int $limitstart
     * @return unknown
     */
	function onContentAfterTitle($context, &$article, &$params, $limitstart = 0){
		
        if ($this->params->get('location') == 'onContentAfterTitle' && $this->params->get('mode') == 'auto') {
            $text = $this->render($this->params, $context,$article);
            return $text;
        }
	}
    

    /**
     * processing render service layout.
     */
    function onAfterRender()
    {
        if ($this->__output != null) {
            $body = JResponse::getBody();
            $body = str_replace('</body>', $this->__output . "\n</body>", $body);
            JResponse::setBody($body);
            unset($this->__output);
        }
    }


    /**
     * render button service
     *
     * @param JParameter $params
     * @array stdClass $article
     * @return string.
     */
    function render($params,$context, &$article)
    {
        $overrideLocation = false;
        $mode = $params->get('mode');

        // only show JA BOOKMARK with content components and article view.
        if (!($this->isValidShowing($context,$params, $article, $mode))) {
            if (isset($article->text)) {
                $article->text = $this->removeConfigString($article->text);
            }
            return;
        }

        // get configuration string from Article's content.
        $configs = $this->parserConfigString($article->text);
        // if use "turnoff" mode


        if ($mode == 'turnoff') {
            $article->text = $this->removeConfigString($article->text);
            return '';
        } else if ($mode == 'manual' && ($configs == null || empty($configs))) {
            return '';
        }

        //find "off" mode in a article

        if (isset($configs[0][0])) {
            if (strpos($configs[0][0], "off")) {
                $article->text = $this->removeConfigString($article->text);
                return '';
            } else {
                $overrideLocation = true;
            }
        }

        // load css file
        $system = $params->get('system', 'addthis');
        // get HTML output from system view
        $content_layout = $this->renderLayout($system, compact('system', 'configs'));
        // render a centain layout and this system view
        //

        // it's wapper would be replace by other.
        // 
        // if using override location, this function would be apply for all "display mode" options.
        if ($overrideLocation) {
        	
        	//$this->__output = $this->renderLayout('common', compact('content_layout'));
        	
        	$wapper = '<div id="jabookmarkWapper">'.$content_layout.'</div>';
        	
            $article->text = $this->removeConfigString($article->text, $wapper);
            
            return '';
        } else {

            return $content_layout;
        }

    }


    /**
     * render layout, allow override layout by owner.
     *
     * @param stdClass $plugin plugin information
     * @param string layout
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
     * render layout.
     *
     * @param string $layout layout's name.
     * @param array data passed.
     * @return string is HTML.
     */
    function renderLayout($layout, $data = array())
    {

        extract($data);
        // include  processor file to render User inteface
        $pathFile = $this->getLayoutPath($this->plugin, $layout);
        if ($pathFile != '') {
            ob_start();
            require_once ($pathFile);
            $content = ob_get_contents();

            ob_end_clean();

            return $content;
        }
        return '';
    }


    function stylesheet($plugin)
    {
        $mainframe = JFactory::getApplication();
        JHTML::stylesheet('plugins/' . $plugin->type . '/' . $plugin->name . '/' . 'style.css');
        if (is_file(JPATH_SITE . '/templates/' . $mainframe->getTemplate() . '/css/' . $plugin->name . ".css"))
            JHTML::stylesheet('templates/' . $mainframe->getTemplate() . '/css/' . $plugin->name . ".css");
    }


    /**
     * parser string in the article's content, check having configuration social existed?
     *
     * @param string $text.
     * @return array if having, equal null if not.
     */
    function parserConfigString($text)
    {
        if (preg_match("#{jabookmark(.*)}#s", $text, $matches, PREG_OFFSET_CAPTURE)) {
            return $matches;
        }
        return null;
    }


    /**
     * remove configuration string in the artilce's content.
     *
     * @param string $text.
     * @return boolean
     */
    function removeConfigString($text, $string = '')
    {
        return preg_replace('#{jabookmark[^{}]*}#i', $string, $text);
    }

}
?>