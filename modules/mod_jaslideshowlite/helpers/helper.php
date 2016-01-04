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

require_once (JPATH_SITE . '/components/com_content/helpers/route.php');
jimport('joomla.application.component.model');
jimport('joomla.html.parameter');
jimport('joomla.filesystem.folder');
if (file_exists(JPATH_SITE . '/components/com_k2/helpers/route.php')) {
    require_once (JPATH_SITE  . '/components/com_k2/helpers/route.php');
}

/**
 * mod JA Silde Show Helper class.
 */
class ModJASlideshowLite
{

    /**
     * @var string $condition;
     *
     * @access private
     */
    var $conditons = '';

    /**
     * @var string $order
     *
     * @access private
     */
    var $order = 'a.ordering';
    /**
     * @var string $mode
     *
     * @access private
     */
    var $mode = 'DESC';
    /**
     * @var object $mod_params
     *
     * @access private
     */
    var $mod_params = null;
    /**
     * @var object $_params
     *
     * @access private
     */
    var $_params = null;

    /**
     * @var string $limit
     *
     * @access private
     */
    var $limit = '5';


    /**
     *
     * reference to the global SlideShowHelper object
     * @Returns a reference to the global SlideShowHelper object
     */
    public static function &getInstance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new ModJASlideshowLite();
        }
        return $instance;
    }
	
    /**
     * magic method
     *
     * @param string method  method is calling
     * @param string $params.
     * @return unknown
     */
    function callMethod($method, $params)
    {
		if (method_exists($this, $method)) {
            if (is_callable(array($this, $method))) {
                return call_user_func(array($this, $method), $params);
            }
        }
        return false;
    }   
    

    /**
     * trim string with max specify
     *
     * @param string $title
     * @param integer $max.
     */
    function trimString($title, $maxchars = 60, $includeTags = NULL)
    {
        if (!empty($includeTags)) {
            $title = $this->trimIncludeTags($title, $this->buildStrTags($includeTags));
        }
        if (function_exists('mb_substr')) {
            $doc = JDocument::getInstance();
            return SmartTrim::mb_trim(($title), 0, $maxchars, $doc->_charset);
        } else {
            return SmartTrim::trim(($title), 0, $maxchars);
        }
    }


    /**
     *
     * Build Tags
     * @param unknown_type $strTags
     * @return string
     */
    public function buildStrTags($strTags = "")
    {
        $strOut = "";
        if (!empty($strTags) && !is_array($strTags)) {
            $arrStr = explode(",", $strTags);
            if (!empty($arrStr)) {
                foreach ($arrStr as $key => $item) {
                    $strOut .= "<" . $item . ">";
                }
            }
        } elseif (!empty($strTags) && is_array($strTags)) {
            $strOut = implode(",", $strTags);
            $strOut = str_replace(",", "", $strOut);
        }
        return $strOut;
    }


    /**
     *
     * Clear space in tags
     * @param string $strContent
     * @param string $listTags
     * @return string the stripped string.
     */
    function trimIncludeTags($strContent, $listTags = "")
    {
        $strOut = strip_tags($strContent, $listTags);
        return $strOut;
    }
   

    /**
     * get parameters from configuration string.
     *
     * @param string $string;
     * @return array.
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
     * parser a image in the content of article.
     * @param object $row article
     * @param object $params
     * @return unknown
     */
    function parseImages(&$row, $params)
    {
        $row->link = $this->getLink($row);
        $text = $row->introtext . $row->fulltext;
        $row->date = strtotime($row->modified) ? $row->created : $row->modified;
        $row->thumbnail = '';
        $row->mainImage = '';	
		
		//check introimage va fullimage
		$images = json_decode($row->images);		
		
		if((isset($images->image_fulltext) and !empty($images->image_fulltext)) || (isset($images->image_intro) and !empty($images->image_intro))){
			 $row->mainImage = (isset($images->image_fulltext) and !empty($images->image_fulltext))?$images->image_fulltext:((isset($images->image_intro) and !empty($images->image_intro))?$images->image_intro:"");
			 $row->thumbnail = (isset($images->image_intro) and !empty($images->image_intro))?$images->image_intro:((isset($images->image_fulltext) and !empty($images->image_fulltext))?$images->image_fulltext:"");
		}
		else {
			$data = $this->parseImageNew($text);
			if (!empty($data) && !empty($data["mainImage"])) {
				$row->mainImage = isset($data["mainImage"]) ? $data["mainImage"] : "";
				$row->thumbnail = isset($data["thumbnail"]) ? $data["thumbnail"] : $row->mainImage;
			} else {
				$data = $this->parserCustomTag($text);
				if (isset($data[1][0])) {
					$tmp = $this->parseParams($data[1][0]);
					$row->mainImage = isset($tmp['main']) ? $tmp['main'] : '';
					$row->thumbnail = isset($tmp['thumb']) ? $tmp['thumb'] : '';
				} else{
					$regex = "/\<img.+src\s*=\s*\"([^\"]*)\"[^\>]*\>/";
					preg_match($regex, $text, $matches);
					$images = (count($matches)) ? $matches : array();
					if (count($images)) {
						$row->mainImage = $images[1];
						$row->thumbnail = $images[1];
					}
				}
			}
		}
    }  


    /**
     *
     * render image from image source.
     * @param string $title
     * @param string $image image path
     * @param object $params
     * @param int $width
     * @param int $height
     * @param string $attrs attributes of image
     * @param boolean $returnURL
     * @return string image path
     */
    function renderImage($title, $image, $params, $width = 0, $height = 0, $attrs = '', $returnURL = false, $main = false)
    {
        if ($image) {
            $title = strip_tags($title);
			if($main){
				$thumbnailMode = $params->get('source-articles-images-main_mode', 'crop');
			} else {
				$thumbnailMode = $params->get('source-articles-images-thumbnail_mode', 'crop');
			}
			
			$aspect = $params->get('source-articles-images-thumbnail_mode-resize-use_ratio', '1');
            $aspect = $aspect == '1' ? true : false;
            $crop = $thumbnailMode == 'crop' ? true : false;
			
            $jaimage = JAImage::getInstance();
			
            if ($thumbnailMode != 'none' && $jaimage->sourceExited($image)) {
                $imageURL = $jaimage->resize($image, $width, $height, $crop, $aspect);
				
                if ($returnURL) {
                    return $imageURL;
                }
				
                if ($imageURL == $image) {
                    $width = $width ? "width=\"$width\"" : "";
                    $height = $height ? "height=\"$height\"" : "";
                    $image = "<img src=\"$imageURL\"   alt=\"{$title}\" title=\"{$title}\" $width $height $attrs />";
                } else {
                    $image = "<img src=\"$imageURL\"  $attrs  alt=\"{$title}\" title=\"{$title}\" />";
                }
            } else {
                if ($returnURL) {
                    return $image;
                }
                $width = "";
				$height = "";
				if ($params->get('source-articles-images-thumbnail_mode', 'crop')!='none') {
					$width = $width ? "width=\"$width\"" : "";
					$height = $height ? "height=\"$height\"" : "";
				}
                $image = "<img $attrs src=\"$image\" alt=\"{$title}\" title=\"{$title}\" $width $height />";
            }
        } else {
            $image = '';
        }
        // clean up globals
        return $image;
    }


    /**
     *
     * Get all image from image source and render them
     * @param object $params
     * @return array image list
     */
    function getListImages($params)
    {
        $folder = $params->get('folder', 'images/stories/fruit');
        $orderby = '0';
        $sort = '0';
        $descriptions = $params->get('description', "");
        $thumbWidth = $params->get('thumbWidth', 60);
        $thumbHeight = $params->get('thumbHeight', 60);
        $mainWidth = $params->get('mainWidth', 360);
        $mainHeight = $params->get('mainHeight', 240);
		$descriptionArr = '';
		$descriptionArray = array();
		if ($descriptions && !$this->isJson($descriptions)) {
			$descriptionArr = preg_split('/<lang=([^>]*)>/', $descriptions, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			$description = '';

			if (count($descriptionArr) > 1) {
				for ($i = 0; $i < count($descriptionArr); $i = $i + 2) {
					if ($descriptionArr[$i] == $iso_client_lang) {
						$description = $descriptionArr[($i + 1)];
						break;
					}
				}
				if (!$description) {
					$description = $descriptionArr[1];
				}
			} else if (isset($descriptionArr[0])) {
				$description = $descriptionArr[0];
			}

			//Parse description. Description in format: [desc img="imagename" url="link"]Description goes here[/desc]
			$descriptionArray = $this->parseDescNew($description);
		}
        else {
        	$descriptionArrays = json_decode($descriptions);
        	
			$i=0;
			if(!empty($descriptionArrays)){
				foreach($descriptionArrays as $des){
					if(!isset($des->show) || (isset($des->show) && $des->show == 'true')){ // only display the images are showed in the module setting
						$descriptionArray[$des->image] = array();
						$descriptionArray[$des->image]['url'] = isset($des->link)?$des->link:$des->link;
						$descriptionArray[$des->image]['title'] = isset($des->title)?$des->title:'';
						$descriptionArray[$des->image]['target'] = "";
						$descriptionArray[$des->image]['class'] = isset($des->class)?$des->class:'';
						$descriptionArray[$des->image]['description'] = isset($des->description)?$des->description:'';
						$i++;
					}
				}
			}
        }
		
        $images = $this->readDirectory($folder, $orderby, $sort);
        $data = array();
        //		echo $folder ; die;
        foreach ($images as $k => $img) {
			if(!isset($descriptionArray[$img])){ // only display the images are showed in the module setting
				continue; 
			}
            $items[] = $k;
            if ($img) {
                $data['captionsArray'][] = (isset($descriptionArray[$img]) && isset($descriptionArray[$img]['description'])) ? $descriptionArray[$img]['description'] : '';
            }
            // URL of image proccess
            $url = JRoute::_((isset($descriptionArray[$img]) && isset($descriptionArray[$img]['url'])) ? $descriptionArray[$img]['url'] : '');
            $target = JRoute::_((isset($descriptionArray[$img]) && isset($descriptionArray[$img]['target'])) ? $descriptionArray[$img]['target'] : '');
			
			$id = (isset($descriptionArray[$img]) && isset($descriptionArray[$img]['id'])) ? $descriptionArray[$img]['id'] : '';
            if ($id) {
                $url = JRoute::_(ContentHelperRoute::getArticleRoute($id));
				$target = JRoute::_(ContentHelperRoute::getArticleRoute($id));
			}
            $data['urls'][] = $url;
            $data['targets'][] = $target;
            $data['classes'][] = isset($descriptionArray[$img]['class']) ? $descriptionArray[$img]['class'] : '';
            $data['titles'][] = isset($descriptionArray[$img]['title'])?$descriptionArray[$img]['title']:'';
            if(substr($folder,0,1) =='/'){
	        	$folder = substr($folder,1);
	        }
			if(substr(trim($folder), -1) != '/'){
				$folder = trim($folder) . '/';
			}
			
            $data['thumbArray'][] = $this->renderImage('', $folder . $img, $params, $thumbWidth, $thumbHeight, '', true, false);
            $data['mainImageArray'][] = $this->renderImage('', $folder . $img, $params, $mainWidth, $mainHeight, '', true, true);
        }
		
        return $data;
    }
	/*
	* Check data format for update data type from old version to json format
	* @string data string 
	* @return boolean
	*/
	function isJson($string) 
	{
		return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
	}

    /**
     *
     * Get all image from resource
     * @param strinh $folder folder path
     * @param string $orderby
     * @param string $sort
     * @return array images
     */
    function readDirectory($folder, $orderby, $sort)
    {
        $imagePath = JPATH_SITE . "/" . $folder;
        $imgFiles = JFolder::files($imagePath);
        $folderPath = $folder . '/';
        $imageFile = array();
        $i = 0;
		if (empty($imgFiles)){
			$imgFiles = array();
		}
        foreach ($imgFiles as $file) {
            $i_f = $imagePath . '/' . $file;
            if (preg_match("/bmp|gif|jpg|png|jpeg/", $file) && is_file($i_f)) {
                $imageFile[$i][0] = $file;
                $imageFile[$i][1] = filemtime($i_f);
                $i++;
            }
        }

        $images = $this->sortImage($imageFile, $orderby, $sort);
        return $images;
    }


    /**
     *
     * Get file path
     * @param string $name name of file
     * @param string $modPath path of module
     * @param string $tmplPath path of template
     * @return string path of file
     */
    function getFile($name, $modPath, $tmplPath = '')
    {
        if (!$tmplPath) {
            $mainframe = JFactory::getApplication();
            $tmplPath = 'templates/' . $mainframe->getTemplate() . '/css/';
        }
        if (file_exists(JPATH_SITE . '/' . $tmplPath . $name)) {
            return $tmplPath . $name;
        }
        return $modPath . $name;
    }


    /**
     *
     * Sort images
     * @param array $image
     * @param string $orderby
     * @param string $sort
     * @return array image that is sorted
     */
    function sortImage($image, $orderby, $sort)
    {
        $sortObj = array();
        $imageName = array();
        if ($orderby == 1) {
            for ($i = 0; $i < count($image); $i++) {
                $sortObj[$i] = $image[$i][1];
                $imageName[$i] = $image[$i][0];
            }
        } else {
            for ($i = 0; $i < count($image); $i++) {
                $sortObj[$i] = $image[$i][0];
            }
            $imageName = $sortObj;
        }
        if ($sort == 1)
            array_multisort($sortObj, SORT_ASC, $imageName);
        elseif ($sort == 2)
            array_multisort($sortObj, SORT_DESC, $imageName);
        //else
        //    shuffle($imageName);
        return $imageName;
    }


    /**
     *
     * Parse description
     * @param string $description
     * @return array
     */
    function parseDescNew($description)
    {

        $regex = '#\[desc ([^\]]*)\]([^\[]*)\[/desc\]#m';
        $description = str_replace(array("{{", "}}"), array("<", ">"), $description);
        preg_match_all($regex, $description, $matches, PREG_SET_ORDER);

        $descriptionArray = array();
        foreach ($matches as $match) {
            $params = $this->parseParams($match[1]);
            if (is_array($params)) {
                $img = isset($params['img']) ? trim($params['img']) : '';
                if (!$img)
                    continue;
                $url = isset($params['url']) ? trim($params['url']) : '';
                $target = isset($params['target']) ? trim($params['target']) : '';
                $class = isset($params['class']) ? trim($params['class']) : '';
                $descriptionArray[$img] = array('url' => $url, 'description' => str_replace("\n", "<br />", trim($match[2])), 'target' => $target, 'class' => $class);
            }
        }

        return $descriptionArray;
    }	
	

}
?>

<?php
if (!class_exists('SmartTrim')) {
    /**
     * Smart Trim String Helper
     *
     */
    class SmartTrim
    {


        /**
         *
         * process string smart split
         * @param string $strin string input
         * @param int $pos start node split
         * @param int $len length of string that need to split
         * @param string $hiddenClasses show and redmore with property display: none or invisible
         * @param string $encoding type of string endcoding
         * @return string string that is smart splited
         */
        public static function mb_trim($strin, $pos = 0, $len = 10000, $hiddenClasses = '', $encoding = 'utf-8')
        {
            mb_internal_encoding($encoding);
            $strout = trim($strin);

            $pattern = '/(<[^>]*>)/';
            $arr = preg_split($pattern, $strout, -1, PREG_SPLIT_DELIM_CAPTURE);
            $left = $pos;
            $length = $len;
            $strout = '';
            for ($i = 0; $i < count($arr); $i++) {
                /*$arr [$i] = trim ( $arr [$i] );*/
                if ($arr[$i] == '')
                    continue;
                if ($i % 2 == 0) {
                    if ($left > 0) {
                        $t = $arr[$i];
                        $arr[$i] = mb_substr($t, $left);
                        $left -= (mb_strlen($t) - mb_strlen($arr[$i]));
                    }

                    if ($left <= 0) {
                        if ($length > 0) {
                            $t = $arr[$i];
                            $arr[$i] = mb_substr($t, 0, $length);
                            $length -= mb_strlen($arr[$i]);
                            if ($length <= 0) {
                                $arr[$i] .= '...';
                            }

                        } else {
                            $arr[$i] = '';
                        }
                    }
                } else {
                    if (SmartTrim::isHiddenTag($arr[$i], $hiddenClasses)) {
                        if ($endTag = SmartTrim::getCloseTag($arr, $i)) {
                            while ($i < $endTag)
                                $strout .= $arr[$i++] . "\n";
                        }
                    }
                }
                $strout .= $arr[$i] . "\n";
            }
            //echo $strout;
            return SmartTrim::toString($arr, $len);
        }


        /**
         *
         * process simple string split
         * @param string $strin string input
         * @param int $pos start node
         * @param int $len length of string that need to split
         * @param string $hiddenClasses show and redmore with property display: none or invisible
         * @return string
         */
        public static function trim($strin, $pos = 0, $len = 10000, $hiddenClasses = '')
        {
            $strout = trim($strin);

            $pattern = '/(<[^>]*>)/';
            $arr = preg_split($pattern, $strout, -1, PREG_SPLIT_DELIM_CAPTURE);
            $left = $pos;
            $length = $len;
            $strout = '';
            for ($i = 0; $i < count($arr); $i++) {
                /*$arr [$i] = trim ( $arr [$i] );*/
                if ($arr[$i] == '')
                    continue;
                if ($i % 2 == 0) {
                    if ($left > 0) {
                        $t = $arr[$i];
                        $arr[$i] = substr($t, $left);
                        $left -= (strlen($t) - strlen($arr[$i]));
                    }

                    if ($left <= 0) {
                        if ($length > 0) {
                            $t = $arr[$i];
                            $arr[$i] = substr($t, 0, $length);
                            $length -= strlen($arr[$i]);
                            if ($length <= 0) {
                                $arr[$i] .= '...';
                            }

                        } else {
                            $arr[$i] = '';
                        }
                    }
                } else {
                    if (SmartTrim::isHiddenTag($arr[$i], $hiddenClasses)) {
                        if ($endTag = SmartTrim::getCloseTag($arr, $i)) {
                            while ($i < $endTag)
                                $strout .= $arr[$i++] . "\n";
                        }
                    }
                }
                $strout .= $arr[$i] . "\n";
            }
            //echo $strout;
            return SmartTrim::toString($arr, $len);
        }


        /**
         * Check is Hidden Tag
         * @param string tag
         * @param string type of hidden
         * @return boolean
         */
        public static function isHiddenTag($tag, $hiddenClasses = '')
        {
            //By pass full tag like img
            if (substr($tag, -2) == '/>')
                return false;
            if (in_array(SmartTrim::getTag($tag), array('script', 'style')))
                return true;
            if (preg_match('/display\s*:\s*none/', $tag))
                return true;
            if ($hiddenClasses && preg_match('/class\s*=[\s"\']*(' . $hiddenClasses . ')[\s"\']*/', $tag))
                return true;
        }


        /**
         *
         * Get close tag from content array
         * @param array $arr content
         * @param int $openidx
         * @return int 0 if find not found OR key of close tag
         */
        public static function getCloseTag($arr, $openidx)
        {
            /*$tag = trim ( $arr [$openidx] );*/
            $tag = $arr[$openidx];
            if (!$openTag = SmartTrim::getTag($tag))
                return 0;

            $endTag = "<$openTag>";
            $endidx = $openidx + 1;
            $i = 1;
            while ($endidx < count($arr)) {
                if (trim($arr[$endidx]) == $endTag)
                    $i--;
                if (SmartTrim::getTag($arr[$endidx]) == $openTag)
                    $i++;
                if ($i == 0)
                    return $endidx;
                $endidx++;
            }
            return 0;
        }


        /**
         *
         * Get tag in content
         * @param string $tag
         * @return string tag
         */
        public static function getTag($tag)
        {
            if (preg_match('/\A<([^\/>]*)\/>\Z/', trim($tag), $matches))
                return ''; //full tag
            if (preg_match('/\A<([^ \/>]*)([^>]*)>\Z/', trim($tag), $matches)) {
                //echo "[".strtolower($matches[1])."]";
                return strtolower($matches[1]);
            }
            //if (preg_match ('/<([^ \/>]*)([^\/>]*)>/', trim($tag), $matches)) return strtolower($matches[1]);
            return '';
        }


        /**
         *
         * convert array to string
         * @param array $arr
         * @param int $len
         * @return string
         */
        public static function toString($arr, $len)
        {
            $i = 0;
            $stack = new JAStack();
            $length = 0;
            while ($i < count($arr)) {
                /*$tag = trim ( $arr [$i ++] );*/
                $tag = $arr[$i++];
                if ($tag == '')
                    continue;
                if (SmartTrim::isCloseTag($tag)) {
                    if ($ltag = $stack->getLast()) {
                        if ('</' . SmartTrim::getTag($ltag) . '>' == $tag)
                            $stack->pop();
                        else
                            $stack->push($tag);
                    }
                } else if (SmartTrim::isOpenTag($tag)) {
                    $stack->push($tag);
                } else if (SmartTrim::isFullTag($tag)) {
                    //echo "[TAG: $tag, $length, $len]\n";
                    if ($length < $len)
                        $stack->push($tag);
                } else {
                    $length += strlen($tag);
                    $stack->push($tag);
                }
            }

            return $stack->toString();
        }


        /**
         *
         * Check is open tag
         * @param string $tag
         * @return boolean
         */
        public static function isOpenTag($tag)
        {
            if (preg_match('/\A<([^\/>]+)\/>\Z/', trim($tag), $matches))
                return false; //full tag
            if (preg_match('/\A<([^ \/>]+)([^>]*)>\Z/', trim($tag), $matches))
                return true;
            return false;
        }


        /**
         *
         * Check is full tag
         * @param string $tag
         * @return boolean
         */
        public static function isFullTag($tag)
        {
            //echo "[Check full: $tag]\n";
            if (preg_match('/\A<([^\/>]*)\/>\Z/', trim($tag), $matches))
                return true; //full tag
            return false;
        }


        /**
         *
         * Check is close tag
         * @param string $tag
         * @return boolean
         */
        public static function isCloseTag($tag)
        {
            if (preg_match('/<\/(.*)>/', $tag))
                return true;
            return false;
        }
    }
}
if (!class_exists('JAStack')) {

    /**
     * News Pro Module JAStack Helper
     */
    class JAStack
    {
        /*
         * array
         */
        var $_arr = null;


        /**
         * Constructor
         *
         * For php4 compatability we must not use the __constructor as a constructor for plugins
         * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
         * This causes problems with cross-referencing necessary for the observer design pattern.
         *
         */
        function JAStack()
        {
            $this->_arr = array();
        }


        /**
         *
         * Push item value into array
         * @param observe $item value of item that will input to stack
         * @return unknown
         */
        function push($item)
        {
            $this->_arr[count($this->_arr)] = $item;
        }


        /**
         *
         * Pop item value from array
         * @param observe $item value of item that will pop from stack
         * @return unknow value of item that is pop from array
         */
        function pop()
        {
            if (!$c = count($this->_arr))
                return null;
            $ret = $this->_arr[$c - 1];
            unset($this->_arr[$c - 1]);
            return $ret;
        }


        /**
         *
         * Get value of last element in array
         * @return unknown value of last element in array
         */
        function getLast()
        {
            if (!$c = count($this->_arr))
                return null;
            return $this->_arr[$c - 1];
        }


        /**
         *
         * Convert array to string
         * @return string
         */
        function toString()
        {
            $output = '';
            foreach ($this->_arr as $item) {
                $output .= $item;
            }
            return $output;
        }
    }
}
?>
