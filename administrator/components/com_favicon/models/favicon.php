<?php

/**
 * @copyright	Copyright (C) 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'floIcon.php');

class FaviconModelFavicon extends JModelAdmin {

    public $iconpath;
    public $tempicon;
    public $supportedBitCounts = array(32, 24, 8, 4, 1);
    public $standardSizes = array(128, 48, 32, 24, 16);

    public function __construct($config = array()) {
        $this->iconpath = JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_favicon' . DIRECTORY_SEPARATOR . 'icons';
        $this->tempicon = JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_favicon' . DIRECTORY_SEPARATOR . 'temp'.DIRECTORY_SEPARATOR.'favicon.ico';
        parent::__construct($config);
    }
    public function getForm($data = array(), $loadData = true) {
        return;
    }
    public function getIcon($id=0) {
        $icon = new floIcon();
        if ($id > 0) {
            $icon->readICO($this->iconpath . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'favicon.ico');
        } else {
                $icon->readICO($this->tempicon);
        }
        return $icon;
    }

    public function addImage() {
        $icon = $this->getIcon();
        $imagecount = $icon->countImages();
        $file = JRequest::getVar('ico_file',null, 'files', 'array' );
        $src = $file['tmp_name'];
        if(
                    ($tmp_image = @imagecreatefrompng($src)) ||
                    ($tmp_image = @imagecreatefromgif($src)) ||
                    ($tmp_image = @imagecreatefromjpeg($src)) ||
                    ($tmp_image = @imagecreatefromwbmp($src))
            ) {
                // it's a valid image - add it to the icon
                $desired_bit_count = JRequest::getInt('desired_bit_count');
                $icon->addImage($tmp_image, $desired_bit_count);
            } else {
                // otherwise process it as an icon;
                $imagecount = 0;
                $icon->readICO($src);
            }
            if($icon->countImages()>$imagecount) {
                return $this->saveTemp($icon);
            } else {
                return false;
            }
    }

    public function removeImages() {
        $remove = JRequest::getVar('remove_images');
        if(count($remove)) {
            $icon=$this->getIcon();
            foreach($remove as $key) {
                unset($icon->images[$key]);
            }
            return $this->saveTemp($icon);
        }
        return false;
    }
    
    public function saveTemp($icon) {
        $icon->sortImagesBySize();
	return JFile::write($this->tempicon, $icon->formatICO());
    }

    public function save() {
        $id=$this->getNextId();
        $newfile = $this->iconpath.DIRECTORY_SEPARATOR.$id;
        if(JFolder::create($newfile)) {
            $newfile.=DIRECTORY_SEPARATOR.'favicon.ico';
            return JFile::copy($this->tempicon,$newfile);
        } else {
            return false;
        }
    }

    public function apply() {
        $context = 'com_favicon.edit.favicon.icon';
        $app = JFactory::getApplication();
        $id = $app->getUserState($context);
        $newfile = $this->iconpath.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'favicon.ico';
        return JFile::copy($this->tempicon,$newfile);
    }
    
    public function getTemplate($path=false) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('template')->from('#__template_styles')->where('client_id = 0')->where('home = 1');
        $db->setQuery($query);
        $template = $db->loadResult();
        if(strlen($template)) {
            $favicon = JPATH_ROOT.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'favicon.ico';
            if(!$path) {
                if(JFile::exists($favicon)) {
                    return $favicon;
                }
            } else {
                return $favicon;
            }
        }        
        return false;
    }

    public function saveTemplate() {
        $template = $this->getTemplate(true);
        if(JFile::exists($template)) JFile::delete($template);
        return JFile::copy($this->tempicon,$template);
    }
    
    public function backupTemplate() {
        $template = $this->getTemplate();
        if($template) {
            $newicon = $this->iconpath.DIRECTORY_SEPARATOR.$this->getNextId();
            if(JFolder::create($newicon)) {
                return JFile::copy($template,$newicon.DIRECTORY_SEPARATOR.'favicon.ico');
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function get16($icon) {
        $returnkey = false;
        if ($icon->countImages()) {
            foreach ($this->supportedBitCounts as $supportedBitCount) {
                if($returnkey !== false) continue;
                foreach ($icon->images as $key => $image) {
                    if($returnkey !== false) continue;
                    $bitCount = $image->_entry["BitCount"] ? $image->_entry["BitCount"] : $image->_header["BitCount"];
                    if ($bitCount == $supportedBitCount && $image->_entry["Height"] == 16 && $image->_entry["Width"] == 16) {
                        $returnkey=$key;
                    }
                }
            }
        } else {
            return false;
        }
        return $returnkey;
    }

    public function getNextId() {
        $ids = JFolder::folders($this->iconpath);
        asort($ids);
        $id = array_pop($ids);
        return $id+1;
    }

    public function getSupportedImageTypes() {
        $aSupportedTypes = array();

        $aPossibleImageTypeBits = array(
            IMG_GIF => 'GIF',
            IMG_JPG => 'JPG',
            IMG_PNG => 'PNG',
            IMG_WBMP => 'BMP'
        );

        foreach ($aPossibleImageTypeBits as $iImageTypeBits => $sImageTypeString) {
            if (imagetypes() & $iImageTypeBits) {
                $aSupportedTypes[] = $sImageTypeString;
            }
        }

        return $aSupportedTypes;
    }

}