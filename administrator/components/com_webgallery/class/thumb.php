<?php
/**
 * @package     AKHelper
 * @subpackage  main
 *
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// No direct access
defined('_JEXEC') or die;


class WebgalleryThumb
{
    protected $id;
    
    protected $path;
    
    protected $bluga;
    
    protected $apikey = 'ad73670b39bce221a561943e0db1024c';
    
    /**
     * function __construct
     */
    public function __construct($itemid)
    {
        $this->id = $itemid;
        $this->path = 'images/webgallery/' . $itemid ;
        
        $this->initBluga();
    }
    
    /**
     * function fetchImages
     */
    public function fetchImages()
    {
        $db     = JFactory::getDbo();
        $thumbs = $this->getThumbs();
        $dir    = JPATH_ROOT . '/' . $this->getPath();
        
        $urls   = array();
        $jobs   = array();
        
        if(!count($thumbs)) return ;
        
        try{
            // Set urls in Bluga
            foreach( $thumbs as $thumb ):
                $url = JFactory::getURI($thumb->a_url) ;
                
                // Set Default Scheme
                if(!$url->getScheme())
                {
                    $url->setScheme('http');
                }
                
                // Add Thumb
                $job = $this->bluga->addUrl($thumb->a_url,'custom', 1280, 960);
                
                $job->options->customThumbnail = array('width' => 800, 'height' => 600);
                
                $job->options->fullthumb = 1;
                
                $jobs[$thumb->a_id] = $job;
            endforeach;
            
            // Send request
            $this->bluga->submitRequests();
            
            // Wait for download
            while (!$this->bluga->readyToDownload()) {
                sleep(5);
                $this->bluga->checkJobStatus();
            }
            
            if(JFolder::exists($dir))
            {
                JFolder::delete($dir);
            }
            
            if(!JFolder::exists($dir))
            {
                JFolder::create($dir);
            }
            
            // Fetch images
            // Jobs can't return right ids, do not save path in thumbs.
            $i = 1 ;
            foreach( $jobs as $key => $job ):
                $this->bluga->fetchToFile($job, null, null, $dir);
                
                // Save to SQL
                $q = $db->getQuery(true) ;
                
                $q->update('#__webgallery_thumbnails')
                    ->set("path = '{$this->path}/{$key}.jpg'")
                    ->set('scanned=1')
                    ->where("id = '{$key}'")
                    ;
                
                $db->setQuery($q);
                //$db->execute();
                
            endforeach;
            
        }
        catch( Exception $e)
        {
            echo $e->getMessage();
        }
    }
    
    /**
     * function getItem
     */
    public function getThumbs()
    {
        JModelLegacy::addIncludePath(WEBGALLERY_ADMIN . '/models');
        $model = JModelLegacy::getInstance('Thumbnails', 'WebgalleryModel', array('ignore_request' => true));
        $model->setState('filter', array('item_id' => $this->id));
        
        $item = $model->getItems();
        
        return $item;
    }
    
    /**
     * function getPath
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * function initBluga
     */
    public function initBluga()
    {
        require_once WEBGALLERY_ADMIN . '/class/Bluga/Autoload.php';
        
        $this->bluga = new Bluga_Webthumb();
        $this->bluga->setApiKey($this->apikey);
    }
}