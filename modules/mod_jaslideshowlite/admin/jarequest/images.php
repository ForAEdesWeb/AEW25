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

defined('_JEXEC') or die( 'Restricted access' );

class images{   
    /**
	* Load images from folder and match them
	*
	*/
    public function loadImages(&$params) {
    	$type = JRequest::getString('type','')?JRequest::getString('type',''):(isset($params->type)?$params->type:'');
		$folder = JRequest::getString('path', '');		
		$folder = JRequest::getString('path', '');
		$images = $this->getListImages($folder,$type);
		
		return $images;
    }
	
	/**
     *
     * Get all image from image source and render them
     * @param object $params
     * @return array image list
     */
    function getListImages($folder='',$type)
    {	
		if(!$folder){
			return null;
		}      

        $images = $this->readDirectory($folder);
        $data = array();
		$data['success'] = false;	
		$data['images'] = array();
		
		if(empty($images)){
			return $data;
		}
		$i = 0 ;
		
			
        foreach ($images as $k => $img) {
        	if($type == 'custom' && !(strpos($img, '-first') === false && strpos($img, '-second') === false && strpos($img,'-thumb') === false)){				
				continue;
			}
        	$data['images'][$i] = new stdClass();
			$data['images'][$i]->image 						= $img;						
			$data['images'][$i]->imageSrc 					= JURI::root() . $folder . $img;			
			$data['images'][$i]->title 						= '';			
            $data['images'][$i]->link 						= '';
			$data['images'][$i]->description 				= '';
			$data['images'][$i]->class 						= '';
			$data['images'][$i]->show 						= true;
			$i++ ;
        	
        }
        
		$data['success'] = true;
        return $data;
    }	
	
	/**
     *
     * Get all image from resource
     * @param strinh $folder folder path
     * @param string $orderby
     * @param string $sort
     * @return array images
     */
    function readDirectory($folder)
    {
        $imagePath = JPATH_SITE . "/" . $folder;
        $imgFiles = JFolder::files($imagePath);
		
        $folderPath = $folder;
        $images = array();
        $i = 0;
		if (empty($imgFiles)){
			return $images;
		}
        foreach ($imgFiles as $file) {		
            if (preg_match("/\.(bmp|gif|jpg|png|jpeg)$/i", $file) && is_file($imagePath.$file)) {
                $images[$i] = $file;                
                $i++;
            }
        }  
		
        return $images;
    }
	
	/**
	* Check data for edit 
	*
	*/
    public function validData() {
		$img = new stdClass;
		$data = trim(JRequest::getVar('data', '', 'POST', 'STRING', JREQUEST_ALLOWRAW));
		$imgName = trim(JRequest::getString('imgname', ''));
		if(!empty($data)){
			$check = 0; // data for image: 1 existed, 0 empty
			$data = json_decode($data);	
				
			foreach($data as $key=>$v){
				
				if($v->image == $imgName){					
					$img->image 					 = 	$imgName;
					$img->title 					 = 	isset($v->title)?$v->title:'';
					$img->link 						 = 	isset($v->link)?$v->link:'';
					$img->description 				 = 	isset($v->description)?$v->description:'';	
					$img->class						 =	isset($v->class)?$v->class:'';	
					$img->show						 =	isset($v->show)?$v->show:true;	
					$check = 1;
					break;		
				}
			}
			if(!$check){
				$img->image 						= 	'';
				$img->title 						= 	'';
				$img->link 							= 	'';
				$img->description 					= 	'';			
				$img->class 						= 	'';	
				$img->show 							= 	false;	
			}
		}else{		
			$img->image 					= 	'';
			$img->title 					= 	'';
			$img->link	 					=	'';
			$img->description 				= 	'';
			$img->class 					= 	'';		
			$img->show 						= 	false;		
		}
		
		return $img;
    }
	
	/**
	* Update data of images param
	*
	*/
    public function updateData() { 		
		$data 						= 	trim(JRequest::getVar('data', '', 'POST', 'STRING', JREQUEST_ALLOWRAW));
		$title 						= 	JRequest::getString('title', '');
		$link 						= 	JRequest::getString('link', '');
		$description 				= 	JRequest::getVar('description', '', 'POST', 'STRING', JREQUEST_ALLOWRAW);
		$imgName 					= 	trim(JRequest::getString('imgname', ''));
		$class						= 	trim(JRequest::getString('class',''));
		$show						= 	trim(JRequest::getString('show',true));
		if($imgName==''){
			if(!$data==''){
				$data = array();
			}else{
				$data = json_decode($data);
			}
			return $data;
		}
		
		//update data param			
		if(!empty($data) && !$data ==''){
			$action = 0; // 1 is update, 0 is add new			
			$data = json_decode($data);	
					
			foreach($data as $key=>$v){
				if($v->image == $imgName){					
					$data[$key]->image 						= 	$imgName;
					$data[$key]->title	 					= 	$title;
					$data[$key]->link 						= 	$link;
					$data[$key]->description 				= 	$description;
					$data[$key]->class						=	$class;			
					$data[$key]->show						=	$show;			
					$action = 1;
					break;		
				}
			}
			if(!$action){
				$count = count($data);
				$data[$count]->image 						= 	$imgName;
				$data[$count]->title 						= 	$title;
				$data[$count]->link 						= 	$link;
				$data[$count]->description 					= 	$description;
				$data[$count]->class 						=	$class;	
				$data[$count]->show 						=	$show;	
			}
		}else{
			$data = array();
			$data[0] = new stdClass();
			$data[0]->image							= 	$imgName;
			$data[0]->title 						= 	$title;
			$data[0]->link 							= 	$link;
			$data[0]->description		 			= 	$description;
			$data[0]->class 						=	$class;
			$data[0]->show	 						=	$show;
		}
		return $data;
    }
    
}